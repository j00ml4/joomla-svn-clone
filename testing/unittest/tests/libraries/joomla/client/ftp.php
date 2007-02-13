<?php
class TestOfJFTP extends UnitTestCase
{
	var $_credentials = null;

	function TestOfJFTP()
	{
		$this->UnitTestCase();

		// Include custom ftp.conf file to allow overriding of FTP_NATIVE mode
		/** @TODO Overriding of FTP_NATIVE does currently not work, as the file which should be tested is loaded first by the UnitTestController */
		@include('ftp.conf');

		/** @TODO */
		// temporary workaround, this should be included in a JoomlaTestCase which extends the UnitTestCase
		jimport('joomla.utilities.error');
		JError::setErrorHandling(E_NOTICE, 'trigger');
		JError::setErrorHandling(E_WARNING, 'trigger');
		JError::setErrorHandling(E_ERROR, 'trigger');

		/** @TODO: Fix this in JFTP!!! */
		// temporary workaround!!!! otherwise the destructor test will fail, as there is a reference to the object laying around in the unit tester's error handler
		!defined('JPATH_ISWIN') && define('JPATH_ISWIN', true);

		jimport('joomla.client.ftp');
	}

	function tearDown()
	{
		$this->assertNoErrors();
	}

	// Not a real test. Only used to display some general information
	function testCredentialsAvailable()
	{
		if (($conf = $this->getCredentials()) === false)
		{
			$this->sendMessage('You need to set correct FTP credentials either in your global configuration, or in a file called "ftp.conf" in the directory of this test case. You may also override the FTP_NATIVE option in this file (comment out line 27 (include_once) in UnitTestController if you wanna use this feature). This is how the file could look like:');
			$msg = "<?php\nif (isset(\$config)) {\n  \$config->ftp_user = 'test';\n  etc....\n}\n\n!defined('FTP_NATIVE') && define('FTP_NATIVE', false);\n?>";
			echo "<pre>".htmlspecialchars($msg)."</pre>"; // ugly, i know ;)
		}
		else
		{
			$msg = 'Testing host ['.$conf['host'].'] on port ['.$conf['port'].'] with user ['.$conf['user'].'] and pass ['.str_repeat('*', strlen($conf['pass'])).']. FTP Root Path set to ['.$conf['root'].']';
			$this->sendMessage($msg);
			$this->sendMessage('FTP_NATIVE mode is switched ' . (FTP_NATIVE? 'ON' : 'OFF'));
		}
	}

	function test__construct()
	{
		$ftp = new JFTP();
		$this->assertIsA($ftp, 'JFTP');
	}

	function testConnectFailed()
	{
		$ftp = new JFTP(array('timeout'=>1));

		$before	= microtime_float();
		$return	= $ftp->connect('127.0.0.1', '1');
		$after	= microtime_float();

		$this->assertIdenticalFalse($return);
		$this->assertIdenticalTrue(($after - $before) < 1.2, '%s - Connect timeout?');
		$this->assertNotA($ftp->_conn, 'resource');
		$this->assertError('JFTP::connect: Could not connect to host "127.0.0.1" on port 1');
	}

	function testConnectSuccess()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$return = $ftp->connect($conf['host'], $conf['port']);

		$pass  = $this->assertIdenticalTrue($return);
		$pass &= $this->assertIdenticalTrue(is_resource($ftp->_conn));

		if (!$pass) {
			$this->invalidateCredentials();
		}
		@$ftp->quit();
	}

	function test__destruct()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$ftp->connect($conf['host'], $conf['port']);
		$conn =& $ftp->_conn;

		$this->assertIdenticalTrue(is_resource($conn));
		unset($ftp);

		if (version_compare(PHP_VERSION, '5', '>=')) {
			$this->assertIdenticalFalse(is_resource($conn));
		} else {
			// PHP4 does not have destructors, so the object and thus the connection should still be there...
			$this->assertIdenticalTrue(is_resource($conn), '%s - ONLY PHP4');
		}
	}

	function testSetOptions()
	{
		$options1 = array('type'=>FTP_ASCII, 'timeout'=>10);
		$options2 = array('type'=>FTP_BINARY, 'timeout'=>'abc');

		$ftp = new JFTP($options1);
		$this->assertIdentical($ftp->_type, FTP_ASCII);
		$this->assertIdentical($ftp->_timeout, 10);

		$ftp->setOptions($options2);
		$this->assertIdentical($ftp->_type, FTP_BINARY);
		$this->assertIdentical($ftp->_timeout, 0);
	}

	function testIsConnected()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$return1 = $ftp->isConnected();
		$return2 = $ftp->connect($conf['host'], $conf['port']);
		$return3 = $ftp->isConnected();

		$pass  = $this->assertIdenticalFalse($return1);
		$pass &= $this->assertIdenticalTrue($return2);
		$pass &= $this->assertIdenticalTrue($return3);

		if (!$pass) {
			$this->invalidateCredentials();
		}
		@$ftp->quit();
	}

	function testLoginFailure()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$ftp->connect($conf['host'], $conf['port']);
		$return = $ftp->login($conf['user'], $conf['pass'].'_will_not_work');

		$this->assertErrorPattern('/JFTP::login: (Bad Password|Unable to login)/');
		$this->assertIdenticalFalse($return);
		@$ftp->quit();
	}

	function testLoginSuccess()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$ftp->connect($conf['host'], $conf['port']);
		$return = $ftp->login($conf['user'], $conf['pass']);

		if (!$this->assertIdenticalTrue($return)) {
			$this->invalidateCredentials();
		}
		@$ftp->quit();
	}

	function testQuit()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$ftp->connect($conf['host'], $conf['port']);

		$this->assertIdenticalTrue(is_resource($ftp->_conn));
		$ftp->quit();
		$this->assertIdenticalFalse(is_resource($ftp->_conn));
	}

	function testSyst()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);
		$return = $ftp->syst();

		if (isset($conf['syst'])) {
			$this->assertIdentical($return, $conf['syst']);
		} else {
			$this->sendMessage("SYST command returned [$return], please evaluate manually if this is correct! You may also set a value for 'ftp_syst' in your configuration.");
		}
		$this->assertIdenticalTrue($return=='UNIX' || $return=='WIN' || $return=='MAC');
		$ftp->quit();
	}

	function testPwd()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);

		$return = $ftp->pwd();

		$this->assertIsA($return, 'string');
		$this->assertTrue($return{0} == '/');
		$ftp->quit();
	}

	function testChdirFailure()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);

		$return = $ftp->chdir('this_is_probably_not_a_real_path');

		$this->assertError('JFTP::chdir: Bad response');
		$this->assertFalse($return);
		$ftp->quit();
	}

	function testChdirSuccess()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);

		$return1 = $ftp->pwd();
		$return2 = $ftp->chdir($conf['root']);
		$return3 = $ftp->pwd();
		$return4 = $ftp->chdir();
		$return5 = $ftp->pwd();
		$return6 = $ftp->chdir($conf['root'].'/');
		$return7 = $ftp->pwd();

		$this->assertIdentical($return1, $return5);
		$this->assertIdentical($return3, $return7);
		$this->assertNotEqual($return1, $return3);
		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalTrue($return4);
		$this->assertIdenticalTrue($return6);
		$this->assertEqual($return3, $conf['root']);
		$this->assertIdenticalFalse(strpos($return3, '\\'), "Dirname contains wrong DIRECTORY_SEPARATOR? [$return3]");

		if (!$return2===true) {
			$this->invalidateCredentials();
			$this->sendMessage('Please set your ftp_root path correctly in your configuration.');
		}
		$ftp->quit();
	}

	function testListNames()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);

		$return1 = $ftp->listNames($conf['root']);
		$return2 = $ftp->listNames($conf['root'].'/');
		$ftp->chdir($conf['root']);
		$return3 = $ftp->listNames();
		
		/** @TODO: Shouldn't the return values of both calls be the same, not only the count? */
		$this->assertIdentical($return1, $return2);
		$this->assertIdentical(count($return1), count($return3));
		$this->assertIsA($return1, 'array');
		$this->assertIdenticalFalse(in_array('.', $return1), 'Directory listing contains [.]?');
		$this->assertIdenticalFalse(in_array('..', $return1), 'Directory listing contains [..]?');
		$this->assertNoErrors();

		/** @TODO: What is this? Backslahes allowed in FTP paths? I thought they should be / always? */
		$return1 = (array) $return1;
		$test = true;
		foreach($return1 as $file) {
			if (strpos($file, '\\') !== false) {
				$test = false;
				break;
			}
		}
		$this->assertIdenticalTrue($test, "Filename contains wrong DIRECTORY_SEPARATOR? [$file]");

		$return3 = $ftp->listNames($conf['root'].'/blablabla');

		$this->assertErrorPattern('/JFTP::listNames: (Transfer Failed|Bad response)/');
		$this->assertIdenticalFalse($return3);
		$ftp->quit();
	}

	function testListDetails()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);

		$return1 = $ftp->listDetails($conf['root']);
		$return2 = $ftp->listDetails($conf['root'].'/');
		$ftp->chdir($conf['root']);
		$return3 = $ftp->listDetails();

		$this->assertIsA($return1, 'array');
		$this->assertIdentical($return1, $return2);
		$this->assertIdentical($return1, $return3);
		$this->assertNoErrors();

		/** @TODO: What is this? Backslahes allowed in FTP paths? I thought they should be / always? */
		$test1 = $test2 = $test3 = true;
		$filename = '';
		$return1 = (array) $return1;
		foreach($return1 as $file) {
			if (strpos($file['name'], '\\') !== false) {
				$test1 = false;
				$filename = $file['name'];
			}
			$test2 &= !($file['name'] == '.');
			$test3 &= !($file['name'] == '..');
		}
		$this->assertIdenticalTrue($test1, "Filename contains wrong DIRECTORY_SEPARATOR? [$filename]");
		$this->assertTrue($test2, "Directory listing contains [.]?");
		$this->assertTrue($test3, "Directory listing contains [..]?");

		$return3 = $ftp->listDetails($conf['root'].'/blablabla');

		$this->assertErrorPattern('/JFTP::listDetails: (Transfer Failed|Bad response)/');
		$this->assertIdenticalFalse($return3);
		$ftp->quit();
	}

	function testCreate()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);

		$return1 = $ftp->listNames($conf['root'].'/');
		$return2 = $ftp->create($conf['root'].'/testfile');
		$return3 = $ftp->listNames($conf['root'].'/');

		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalFalse(in_array($conf['root'].'/testfile', $return1));
		$this->assertIdenticalTrue(in_array($conf['root'].'/testfile', $return3));
		$this->assertIdentical(count($return1), count($return3)-1);
		$this->assertNoErrors();

		@$ftp->delete($conf['root'].'/testfile');
		$ftp->chdir($conf['root']);

		$return1 = $ftp->listNames();
		$return2 = $ftp->create('testfile');
		$return3 = $ftp->listNames();

		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalFalse(in_array('testfile', $return1));
		$this->assertIdenticalTrue(in_array('testfile', $return3));
		$this->assertIdentical(count($return1), count($return3)-1);
		$this->assertNoErrors();

		$return4 = $ftp->create($conf['root'].'/blablabla/testfile');

		$this->assertError('JFTP::create: Bad response');
		$this->assertIdenticalFalse($return4);
		
		@$ftp->delete($conf['root'].'/testfile');
		$ftp->quit();
	}

	function testDelete()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);
		$ftp->create($conf['root'].'/testfile');

		$return1 = $ftp->listNames($conf['root'].'/');
		$return2 = $ftp->delete($conf['root'].'/testfile');
		$return3 = $ftp->listNames($conf['root'].'/');

		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalTrue(in_array($conf['root'].'/testfile', $return1));
		$this->assertIdenticalFalse(in_array($conf['root'].'/testfile', $return3));
		$this->assertIdentical(count($return1), count($return3)+1);
		$this->assertNoErrors();

		$ftp->chdir($conf['root']);
		$ftp->create('testfile');

		$return1 = $ftp->listNames();
		$return2 = $ftp->delete('testfile');
		$return3 = $ftp->listNames();

		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalTrue(in_array('testfile', $return1));
		$this->assertIdenticalFalse(in_array('testfile', $return3));
		$this->assertIdentical(count($return1), count($return3)+1);
		$this->assertNoErrors();

		$return4 = $ftp->delete('testfile');

		$this->assertError('JFTP::delete: Bad response');
		$this->assertIdenticalFalse($return4);

		$ftp->quit();
	}

	function testRename()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);
		$ftp->chdir($conf['root']);
		$ftp->create('testfile');

		$return1 = $ftp->listNames();
		$return2 = $ftp->rename('testfile', 'testfile2');
		$return3 = $ftp->listNames();

		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalTrue(in_array('testfile', $return1));
		$this->assertIdenticalFalse(in_array('testfile', $return3));
		$this->assertIdenticalTrue(in_array('testfile2', $return3));
		$this->assertNoErrors();

		$return4 = $ftp->rename('testfile', 'testfile3');

		$this->assertError('JFTP::rename: Bad response');
		$this->assertIdenticalFalse($return4);
		$this->assertNoErrors();

		$return5 = $ftp->rename('testfile2', $conf['root'].'/testfile4');
		$return6 = $ftp->listNames();

		$this->assertIdenticalTrue($return5);
		$this->assertIdenticalTrue(in_array('testfile4', $return6));
		$this->assertIdenticalFalse(in_array('testfile2', $return6));
		$this->assertIdenticalFalse(in_array('testfile3', $return6));

		$ftp->delete('testfile4');
		$ftp->quit();
	}

	function testChmod()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);
		$ftp->chdir($conf['root']);
		$ftp->create('testfile');

		$return1 = $ftp->chmod('testfile', 0666);
		$return2 = $ftp->chmod('testfile', '0777');

		// Not supported on all systems. Print message instead of failure when not supported
		if ($return1 === false && $return2 === false) {
			$this->sendMessage('Your server does not support the CHMOD command! Since this is'
				.' nothing we rely on in Joomla!, and since there are probably more servers which'
				.' do not support it, this is just displayed as a notice, not as a failure.'
			);
			/**
			 * @TODO: can we maybe even supress these errors in JFTP? Would probably better if the user
			 * does not see such a notice/warning. Return code from chmod should be enough...
			 */
			$this->assertError('JFTP::chmod: Bad response');
			$this->assertError('JFTP::chmod: Bad response');
		} else {
			$this->assertIdenticalTrue($return1);
			$this->assertIdenticalTrue($return2);
		}

		$ftp->delete('testfile');
		$ftp->quit();
	}

	function testMkdir()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>1));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);

		$return1 = $ftp->listNames($conf['root'].'/');
		$return2 = $ftp->mkdir($conf['root'].'/testdir');
		$return3 = $ftp->listNames($conf['root'].'/');

		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalFalse(in_array($conf['root'].'/testdir', $return1));
		$this->assertIdenticalTrue(in_array($conf['root'].'/testdir', $return3));
		$this->assertIdentical(count($return1), count($return3)-1);
		$this->assertNoErrors();

		$ftp->delete($conf['root'].'/testdir');
		$ftp->chdir($conf['root']);

		$return1 = $ftp->listNames();
		$return2 = $ftp->mkdir('testdir');
		$return3 = $ftp->listNames();

		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalFalse(in_array('testdir', $return1));
		$this->assertIdenticalTrue(in_array('testdir', $return3));
		$this->assertIdentical(count($return1), count($return3)-1);
		$this->assertNoErrors();

		$return4 = $ftp->mkdir($conf['root'].'/blablabla/testdir');

		$this->assertError('JFTP::mkdir: Bad response');
		$this->assertIdenticalFalse($return4);

		$ftp->delete($conf['root'].'/testdir');
		$ftp->quit();
	}

/*
	function testRead()
	{
		$this->assertTrue( false );
	}

	function testGet()
	{
		$this->assertTrue( false );
	}

	function testStore()
	{
		$this->assertTrue( false );
	}

	function testWrite()
	{
		$this->assertTrue( false );
	}
*/

	function test_findMode()
	{
		$ftp = new JFTP();

		$return1 = $ftp->_findMode('testfile.html');
		$return2 = $ftp->_findMode('testfile.html.ext');
		$return3 = $ftp->_findMode('testfile.bin');
		$return4 = $ftp->_findMode('testfile');

		$this->assertIdentical($return1, FTP_ASCII);
		$this->assertIdentical($return2, FTP_BINARY);
		$this->assertIdentical($return3, FTP_BINARY);
		$this->assertIdentical($return4, FTP_BINARY);

		$ftp->setOptions(array('type'=>FTP_ASCII));

		$return1 = $ftp->_findMode('testfile.html');
		$return2 = $ftp->_findMode('testfile.bin');

		$this->assertIdentical($return1, FTP_ASCII);
		$this->assertIdentical($return2, FTP_ASCII);

		$ftp->setOptions(array('type'=>FTP_BINARY));

		$return1 = $ftp->_findMode('testfile.html');
		$return2 = $ftp->_findMode('testfile.bin');

		$this->assertIdentical($return1, FTP_BINARY);
		$this->assertIdentical($return2, FTP_BINARY);
	}

	function testGetInstance()
	{
		$ftp =& JFTP::getInstance('127.0.0.1', '1', array('timeout'=>0));
		$this->assertError('JFTP::connect: Could not connect to host "127.0.0.1" on port 1');
		$this->assertIsA($ftp, 'JFTP');
	}

	function testGetInstanceReference()
	{
		$before = microtime_float();
		$ftp1 =& JFTP::getInstance('127.0.0.1', '1', array('timeout'=>0));
		$ftp2 =& JFTP::getInstance('127.0.0.1', '1', array('timeout'=>0));
		$ftp3 =& JFTP::getInstance('127.0.0.1', '1', array('test'));
		$ftp4 =& JFTP::getInstance('127.0.0.1', '2', array('timeout'=>0));
		$ftp5 =& JFTP::getInstance('127.0.0.1', '1', array('timeout'=>0), 'username', 'password');
		$ftp6 =& JFTP::getInstance('127.0.0.1', '1', null, 'username', 'password');
		$after = microtime_float();

		$this->assertError('JFTP::connect: Could not connect to host "127.0.0.1" on port 1');
		$this->assertError('JFTP::connect: Could not connect to host "127.0.0.1" on port 1');
		$this->assertError('JFTP::connect: Could not connect to host "127.0.0.1" on port 1');
		$this->assertError('JFTP::connect: Could not connect to host "127.0.0.1" on port 2');
		$this->assertError('JFTP::connect: Could not connect to host "127.0.0.1" on port 1');
		$this->assertError('JFTP::connect: Could not connect to host "127.0.0.1" on port 1');
		if (version_compare(PHP_VERSION, '5', '>=')) {
			$this->assertTrue(($after - $before) < 1, 'Connect timeout?');
		} else {
			$this->assertTrue(($after - $before) < 6.2, 'Connect timeout?');
		}
		$this->assertReference($ftp1, $ftp2);
		$this->assertReference($ftp1, $ftp3);
		$this->assertCopy($ftp1, $ftp4);
		$this->assertCopy($ftp1, $ftp5);
		$this->assertReference($ftp5, $ftp6);
	}

	function getCredentials()
	{
		if ($this->_credentials !== null) {
			return $this->_credentials;
		}

		// Load global configuration
		@include_once(JPATH_CONFIGURATION.DS.'configuration.php');
		if (!class_exists('JConfig')) {
			$this->_credentials = false;
			return false;
		}

		// Allow overriding of credentials from custom file, placed in the directory of this file
		$config = new JConfig();
		@include('ftp.conf');
		$return = array(
			'enabled'	=> 1,
			'host'		=> $config->ftp_host,
			'port'		=> $config->ftp_port,
			'user'		=> $config->ftp_user,
			'pass'		=> $config->ftp_pass,
			'root'		=> $config->ftp_root,
			'syst'		=> isset($config->ftp_syst)? $config->ftp_syst : null
		);
		unset($config);

		// Credentials valid?
		if (!($return['host'] && $return['port'] && $return['user'] && $return['pass'])) {
			$return = false;
		}

		$this->_credentials = $return;
		return $this->_credentials;
	}

	function invalidateCredentials()
	{
		$this->sendMessage('Invalidating Credentials due to previous error');
		$this->_credentials = false;
	}

	function assertIdenticalFalse($value, $message='%s')
	{
		return $this->assertIdentical($value, false, $message);
	}

	function assertIdenticalTrue($value, $message='%s')
	{
		return $this->assertIdentical($value, true, $message);
	}

/**
 * Leaving these functions for later...
 * Most of them are also already indirectly tested when using the Non-Native mode
 */
/*
	function testReinit()
	{
		$this->assertTrue( false );
	}

	function testRestart()
	{
		$this->assertTrue( false );
	}

	function test_putCmd()
	{
		$this->assertTrue( false );
	}

	function test_verifyResponse()
	{
		$this->assertTrue( false );
	}

	function test_passive()
	{
		$this->assertTrue( false );
	}

	function test_mode()
	{
		$this->assertTrue( false );
	}
*/
}

/** @TODO: Move to appropriate place */
// Helper function, PHP4
function microtime_float()
{
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}
?>