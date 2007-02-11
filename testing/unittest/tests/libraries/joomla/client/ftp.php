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

		$this->assertFalse($return);
		$this->assertTrue(($after - $before) < 1.5, 'Connect timeout?');
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
		$return	= $ftp->connect($conf['host'], $conf['port']);

		$pass  = $this->assertTrue($return);
		$pass &= $this->assertIsA($ftp->_conn, 'resource');

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

		$this->assertIsA($conn, 'resource');
		unset($ftp);

		if (version_compare(PHP_VERSION, '5', '>=')) {
			$this->assertNotA($conn, 'resource');
		} else {
			// PHP4 does not have destructors, so the object and thus the connection should still be there...
            $this->assertNotA($conn, 'resource', '%s - ONLY PHP4');
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

		$pass  = $this->assertFalse($return1);
		$pass &= $this->assertTrue($return2);
		$pass &= $this->assertTrue($return3);

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
		$this->assertFalse($return);
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

		if (!$this->assertTrue($return)) {
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

		$this->assertIsA($ftp->_conn, 'resource');
		$ftp->quit();
		$this->assertNotA($ftp->_conn, 'resource');
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

		$this->assertTrue($return=='UNIX' || $return=='WIN' || $return='MAC');
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

	function testChdir()
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
		$return4 = $ftp->chdir('');
		$return5 = $ftp->pwd();

		$this->assertIdentical($return1, $return5);
		$this->assertNotEqual($return1, $return3);
		$this->assertEqual($return3, $conf['root']);
		$this->assertTrue($return4);
		if (!$this->assertTrue($return2)) {
			$this->invalidateCredentials();
			$this->sendMessage('Please set your ftp_root path correctly in your configuration.');
			$ftp->quit();
			return;
		}

		$return6 = $ftp->chdir('this_is_probably_not_a_real_path');

		$this->assertError('JFTP::chdir: Bad response');
		$this->assertFalse($return6);
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
		$ftp->chdir($conf['root']);
		$return2 = $ftp->listNames();
		$return3 = $ftp->listNames($conf['root'].'/blablabla');

		$this->assertErrorPattern('/JFTP::listNames: (Transfer Failed|Bad response)/');
		$this->assertIdentical($return3, false);
		$this->assertEqual(count($return1), count($return2));
		$this->assertIsA($return1, 'array');

		$return1 = (array) $return1;
		$test = true;
		foreach($return1 as $file) {
			if (strpos($file, '\\') !== false) {
				$test = false;
				break;
			}
		}
		$this->assertTrue($test, "Filename contains wrong DIRECTORY_SPARATOR: [$file]");

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
		$ftp->chdir($conf['root']);
		$return2 = $ftp->listDetails();
		$return3 = $ftp->listDetails($conf['root'].'/blablabla');

		$this->assertErrorPattern('/JFTP::listDetails: (Transfer Failed|Bad response)/');
		$this->assertEqual(count($return1), count($return2));
		$this->assertIdentical($return3, false);
		$this->assertIsA($return1, 'array');

		/** @TODO: More examination of the result */

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

		@$ftp->delete($conf['root'].'/testfile');

		$return1 = $ftp->listDetails($conf['root']);
		$return2 = $ftp->create($conf['root'].'/testfile');
		$return3 = $ftp->listDetails($conf['root']);
		$return4 = $ftp->create($conf['root'].'/this_directory_does_not_exist/testfile');

		$this->assertError('JFTP::create: Bad response');
		$this->assertTrue($return2);
		$this->assertIdentical($return4, false);
		$this->assertEqual(count($return1), count($return3)-1);
		$ftp->quit();
	}

/*
	function testRename()
	{
		$this->assertTrue( false );
	}

	function testChmod()
	{
		$this->assertTrue( false );
	}

	function testDelete()
	{
		$this->assertTrue( false );
	}

	function testMkdir()
	{
		$this->assertTrue( false );
	}

	function testCreate()
	{
		$this->assertTrue( false );
	}

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

	function test_findMode()
	{
		$this->assertTrue( false );
	}

	function test_mode()
	{
		$this->assertTrue( false );
	}
*/

	function testGetInstance()
	{
		$ftp =& JFTP::getInstance('127.0.0.1', '1', array('timeout'=>0));
        $this->assertError('JFTP::connect: Could not connect to host "127.0.0.1" on port 1');
		$this->assertIsA($ftp, 'JFTP');
	}

	function testGetInstanceReference()
	{
		$ftp1 =& JFTP::getInstance('127.0.0.1', '1', array('timeout'=>0));
        $this->assertError('JFTP::connect: Could not connect to host "127.0.0.1" on port 1');

		$before	= microtime_float();
		$ftp2 =& JFTP::getInstance('127.0.0.1', '1');
		$after	= microtime_float();
        $this->assertError('JFTP::connect: Could not connect to host "127.0.0.1" on port 1');

		$this->assertTrue(($after - $before) < 1.5, 'Connect timeout?');
		$this->assertReference($ftp1, $ftp2);
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
			'root'		=> $config->ftp_root
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

/** Leaving these functions for later... */
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