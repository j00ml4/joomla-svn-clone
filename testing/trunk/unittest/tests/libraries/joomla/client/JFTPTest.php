<?php
/**
 * Test class for JRouter.
 * Handcrafted by friesengeist ;)
 *
 * @version	$Id$
 * @package 	Joomla
 * @subpackage 	UnitTest
 */

// Call TestOfJFTP::main() if this source file is executed directly.
if (!defined('JUNITTEST_MAIN_METHOD')) {
    define('JUNITTEST_MAIN_METHOD', 'TestOfJFTP::main');
    $JUNITTEST_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNITTEST_ROOT.'/unittest/prepend.php');
}

class TestOfJFTP extends UnitTestCase
{
var $_credentials = null;

	/**
	 * Runs the test methods of this class.
	 *
	 * @access public
	 * @static
	 */
	function main() {
		$self = new TestOfJFTP;
		$self->run( UnitTestHelper::getReporter(null, __FILE__) );
	}

	function x__TestOfJFTP()
	{
		$this->UnitTestCase();
		FtpTestHelper::setUpTestCase();
	}

	// Not a real test. Only used to display some general information
	function testCredentialsAvailable()
	{
		if (($conf = $this->getCredentials()) === false)
		{
			$this->sendMessage('You need to set correct FTP credentials either in your global'
				.' configuration, or in a file called "ftp.conf" in the directory of this test'
				.' case. You may also override the FTP_NATIVE option in this file (comment out'
				.' line 27 (include_once) in UnitTestController if you wanna use this feature).'
				.' This is how the file could look like:'
			);
			$msg = "<?php\nif (isset(\$config)) {\n  \$config->ftp_user = 'test';\n  etc....\n}'
				.'\n\n!defined('FTP_NATIVE') && define('FTP_NATIVE', false);\n?>"
			;
			$this->skip();
			SimpleTest::ignore( get_class($this) );

			$this->sendMessage("<pre>".htmlspecialchars($msg)."</pre>"); // ugly, i know ;)
		}
		else
		{
			$msg = 'Testing host ['.$conf['host'].'] on port ['.$conf['port'].'] with user ['
				.$conf['user'].'] and pass ['.str_repeat('*', strlen($conf['pass'])).']. FTP'
				.' Root Path set to ['.$conf['root'].']'
			;
			$this->sendMessage($msg);
			$this->sendMessage('FTP_NATIVE mode is switched ' . (FTP_NATIVE? 'ON' : 'OFF'));
		}
	}

//	function tearDown()
//	{
//		$this->assertNoErrors();
//	}

	function test__construct()
	{
		// we must as least use an empty array or PHP will throw
		// E_WARNING: Missing argument 1 for JFTP::__construct()
		$ftp = new JFTP( array() );
		$this->assertIsA($ftp, 'JFTP');
	}

	function testConnectFailed()
	{
		$ftp = new JFTP(array('timeout'=>1));

		$before	= FtpTestHelper::microtime_float();
		$return	= $ftp->connect('127.0.0.1', '1');
		$after	= FtpTestHelper::microtime_float();

		$this->assertIdenticalFalse($return);
		$this->assertIdenticalTrue(($after - $before) < 1.5, '%s - Connect timeout?');
		$this->assertNotA($ftp->_conn, 'resource');
		$this->assertError('JFTP::connect: Could not connect to host "127.0.0.1" on port 1');
	}

	function testConnectSuccess()
	{
		if (($conf = $this->getCredentials()) === false) {
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
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
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
		$ftp->connect($conf['host'], $conf['port']);
		$conn =& $ftp->_conn;

		$this->assertIdenticalTrue(is_resource($conn));
		unset($ftp);

		if (version_compare(PHP_VERSION, '5', '>=')) {
			$this->assertIdenticalFalse(is_resource($conn));
		} else {
			// PHP4 has no destructors, so the object and the connection should still be there...
			$this->assertIdenticalTrue(is_resource($conn), '%s - ONLY PHP4');
		}
	}

	function testSetOptions()
	{
		/** @TODO: Should we also test non-existing values, e.g. a string for the timeout? */
		$options1 = array('type'=>FTP_ASCII, 'timeout'=>10);
		$options2 = array('type'=>FTP_BINARY, 'timeout'=>0);

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
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
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
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
		$ftp->connect($conf['host'], $conf['port']);
		$return = $ftp->login($conf['user'], 'test'.$conf['pass'].'_will_not_work');

		$this->assertErrorPattern('/JFTP::login: (Bad Password|Unable to login)/');
		$this->assertIdenticalFalse($return);
		@$ftp->quit();
	}

	function testLoginSuccess()
	{
		if (($conf = $this->getCredentials()) === false) {
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
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
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
		$ftp->connect($conf['host'], $conf['port']);

		$this->assertIdenticalTrue(is_resource($ftp->_conn));
		$ftp->quit();
		$this->assertIdenticalFalse(is_resource($ftp->_conn));
	}

	function testSyst()
	{
		if (($conf = $this->getCredentials()) === false) {
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);
		$return = $ftp->syst();

		if (isset($conf['syst'])) {
			$this->assertIdentical($return, $conf['syst']);
		} else {
			$this->sendMessage("SYST command returned [$return], please evaluate manually if this"
				.' is correct! You may also set a value for \'ftp_syst\' in your configuration.'
			);
		}
		$this->assertIdenticalTrue($return=='UNIX' || $return=='WIN' || $return=='MAC');
		$ftp->quit();
	}

	function testPwd()
	{
		if (($conf = $this->getCredentials()) === false) {
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
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
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
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
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);

		$return1 = $ftp->pwd();
		$return2 = $ftp->chdir($conf['root']);
		$return3 = $ftp->pwd();
		$return4 = $ftp->chdir($return1);
		$return5 = $ftp->pwd();
		$return6 = $ftp->chdir($conf['root'].'/');
		$return7 = $ftp->pwd();
		$return8 = $ftp->chdir('/');
		$return9 = $ftp->pwd();

		$this->assertIdentical($return1, $return5);
		$this->assertIdentical($return3, $return7);
		$this->assertIdentical($return3, $conf['root']);
		$this->assertIdentical($return9, '/');
		$this->assertNotIdentical($return1, $return3, '%s - Please make sure to run this test'
			.' in a subdirectory of your FTP account' );
		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalTrue($return4);
		$this->assertIdenticalTrue($return6);
		$this->assertIdenticalTrue($return8);
		// Although not limited as per RFC959, we report all servers which send '\' instead of '/'
		$this->assertIdenticalFalse(strpos($return3, '\\'), 'Dirname contains wrong'
			." DIRECTORY_SEPARATOR? [$return3]"
		);

		if ($return1 !== $return9) {
			$this->sendMessage('Notice: the directory which is your Current Working Directory'
				.' directly after connecting is NOT the root directory [/].'
			);
		}

		if ($return2 !== true) {
			$this->invalidateCredentials();
			$this->sendMessage('Please set your ftp_root path correctly in your configuration.');
		}
		$ftp->quit();
	}

	function testListNames()
	{
		if (($conf = $this->getCredentials()) === false) {
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);

		$return1 = $ftp->listNames($conf['root']);
		$return2 = $ftp->listNames($conf['root'].'/');
		$ftp->chdir($conf['root']);
		$return3 = $ftp->listNames();

		$this->assertIdentical($return1, $return2);
		$this->assertIdentical($return1, $return3);
		$this->assertIsA($return1, 'array');
		$return1 = (array) $return1;
		$return2 = (array) $return2;
		$this->assertIdenticalFalse(in_array('.', $return1), 'Directory listing contains [.]?');
		$this->assertIdenticalFalse(in_array('..', $return1), 'Directory listing contains [..]?');
		$this->assertNoErrors();

		// Although not limited as per RFC959, we report all servers which send '\' instead of '/'
		$test = true;
		$file = '';
		foreach($return1 as $file) {
			if (strpos($file, '\\') !== false) {
				$test = false;
				break;
			}
		}
		$this->assertIdenticalTrue($test, "Filename contains wrong DIRECTORY_SEPARATOR? [$file]");

		/** FTP servers behave differently. No failing test for now, maybe for J! 1.6 */
		//$return3 = $ftp->listNames($conf['root'].'/blablabla');

		//$this->assertErrorPattern('/JFTP::listNames: (Transfer Failed|Bad response)/');
		//$this->assertIdenticalFalse($return3);
		$ftp->quit();
	}

	function testListDetails()
	{
		if (($conf = $this->getCredentials()) === false) {
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
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

		// Although not limited as per RFC959, we report all servers which send '\' instead of '/'
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

		/** FTP servers behave differently. No failing test for now, maybe for J! 1.6 */
		//$return3 = $ftp->listDetails($conf['root'].'/blablabla');

		//$this->assertErrorPattern('/JFTP::list(Names|Details): (Transfer Failed|Bad response)/');
		//$this->assertIdenticalFalse($return3);

		$ftp->quit();
	}

	function testCreate()
	{
		if (($conf = $this->getCredentials()) === false) {
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);

		$return1 = $ftp->listNames($conf['root']);
		$return2 = $ftp->create($conf['root'].'/testfile');
		$return3 = $ftp->listNames($conf['root']);

		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalFalse(in_array('testfile', $return1));
		$this->assertIdenticalTrue(in_array('testfile', $return3));
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

	function testListDetails2()
	{
		if (($conf = $this->getCredentials()) === false) {
			$this->fail('Credentials not set');
			return;
		}

		$ftp = new JFTP(array('timeout'=>5));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);

		$return1 = $ftp->listDetails($conf['root']);
		$ftp->create($conf['root'].'/testfile');
		$ftp->create($conf['root'].'/testfile2');
		$return2 = $ftp->listDetails($conf['root']);
		$ftp->delete($conf['root'].'/testfile');
		$ftp->delete($conf['root'].'/testfile2');

		$this->assertIsA($return1, 'array');
		$this->assertIsA($return2, 'array');
		$this->assertIdentical(count($return1), count($return2)-2);
		foreach ($return2 as $key => $file) {
			$files[] = $file['name'];
		}
		$this->assertIdenticalTrue(in_array('testfile', $files));
		$this->assertIdenticalTrue(in_array('testfile2', $files));

		// Although not limited as per RFC959, we report all servers which send '\' instead of '/'
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

		$ftp->quit();
	}

	function testDelete()
	{
		if (($conf = $this->getCredentials()) === false) {
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);
		$ftp->create($conf['root'].'/testfile');

		$return1 = $ftp->listNames($conf['root']);
		$return2 = $ftp->delete($conf['root'].'/testfile');
		$return3 = $ftp->listNames($conf['root']);

		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalTrue(in_array('testfile', $return1));
		$this->assertIdenticalFalse(in_array('testfile', $return3));
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
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
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
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
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
			 * @TODO: can we maybe even supress these errors in JFTP? Would probably better if the
			 * user does not see such a notice/warning. Return code from chmod should be enough...
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
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$ftp = new JFTP(array('timeout'=>5));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);

		$return1 = $this->listAllNames($ftp, $conf['root']);
		$return2 = $ftp->mkdir($conf['root'].'/testdir');
		$return3 = $this->listAllNames($ftp, $conf['root']);

		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalFalse(in_array('testdir', $return1));
		$this->assertIdenticalTrue(in_array('testdir', $return3));
		$this->assertIdentical(count($return1), count($return3)-1);
		$this->assertNoErrors();

		$ftp->delete($conf['root'].'/testdir');
		$ftp->chdir($conf['root']);

		$return1 = $this->listAllNames($ftp, null);
		$return2 = $ftp->mkdir('testdir');
		$return3 = $this->listAllNames($ftp, null);

		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalFalse(in_array('testdir', $return1));
		$this->assertIdenticalTrue(in_array('testdir', $return3));
		$this->assertIdentical(count($return1), count($return3)-1);
		$this->assertNoErrors();

		// No failing test here, as some servers create directories recursively
		$ftp->delete($conf['root'].'/testdir');
		$ftp->quit();
	}

	function testReadWrite()
	{
		if (($conf = $this->getCredentials()) === false) {
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$bufferRead = null;
		$buffer = '';
		for($i=0; $i<256; $i++) {
			$buffer .= chr($i);
		}
		$buffer .= $buffer;

		$ftp = new JFTP(array('timeout'=>5, 'type'=>FTP_BINARY));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);

		$return1 = $ftp->listNames($conf['root']);
		$return2 = $ftp->write($conf['root'].'/testfile', $buffer);
		$return3 = $ftp->listNames($conf['root']);
		$return4 = $ftp->read($conf['root'].'/testfile', $bufferRead);

		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalTrue($return4);
		$this->assertIdenticalFalse(in_array('testfile', $return1));
		$this->assertIdenticalTrue(in_array('testfile', $return3));
		$this->assertIdentical(count($return1), count($return3)-1);
		$this->assertIdentical(FtpTestHelper::binaryToString($buffer), FtpTestHelper::binaryToString($bufferRead),
			'%s - Write: [Binary], Read: [Binary]'
		);
		$this->assertNoErrors();

		$ftp->delete($conf['root'].'/testfile');
		$ftp->chdir($conf['root']);

		$return1 = $ftp->listNames();
		$return2 = $ftp->write('testfile', $buffer);
		$return3 = $ftp->listNames();
		$return4 = $ftp->read('testfile', $bufferRead);

		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalTrue($return4);
		$this->assertIdenticalFalse(in_array('testfile', $return1));
		$this->assertIdenticalTrue(in_array('testfile', $return3));
		$this->assertIdentical(count($return1), count($return3)-1);
		$this->assertIdentical(FtpTestHelper::binaryToString($buffer), FtpTestHelper::binaryToString($bufferRead),
			'%s - Write: [Binary], Read: [Binary]'
		);
		$this->assertNoErrors();

		$return4 = $ftp->write($conf['root'].'/blablabla/testfile', $buffer);

		$this->assertError('JFTP::write: Bad response');
		$this->assertIdenticalFalse($return4);

		$return5 = $ftp->read($conf['root'].'/blablabla/testfile', $buffer);

		$this->assertError('JFTP::read: Bad response');
		$this->assertIdenticalFalse($return5);

		$ftp->delete($conf['root'].'/testfile');
		$ftp->quit();
	}

	function disabledTestReadWriteModes()
	{
		if (($conf = $this->getCredentials()) === false) {
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$bufferRead = null;
		$buffer = '';
		for($i=0; $i<256; $i++) {
			$buffer .= chr($i);
		}
		$buffer = " \r \n \r\n ".$buffer.$buffer;

		$ftp = new JFTP(array('timeout'=>5));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);
		$ftp->chdir($conf['root']);
		$syst = $ftp->syst();

		$ftp->setOptions(array('type'=>FTP_BINARY));
		$return1 = $ftp->write('testfile', $buffer);
		$ftp->setOptions(array('type'=>FTP_ASCII));
		$return2 = $ftp->read('testfile', $bufferRead);

		$expected = $this->getExpected($buffer, FTP_BINARY, FTP_ASCII, $syst);
		$this->assertIdenticalTrue($return1);
		$this->assertIdenticalTrue($return2);
		$this->assertIdentical(FtpTestHelper::binaryToString($expected), FtpTestHelper::binaryToString($bufferRead),
			'%s - Write: [Binary], Read: [ASCII]'
		);
		$this->assertNoErrors();

		$ftp->delete($conf['root'].'/testfile');
		$ftp->setOptions(array('type'=>FTP_ASCII));
		$return1 = $ftp->write('testfile', $buffer);
		$ftp->setOptions(array('type'=>FTP_BINARY));
		$return2 = $ftp->read('testfile', $bufferRead);

		$expected = $this->getExpected($buffer, FTP_ASCII, FTP_BINARY, $syst);
		$this->assertIdenticalTrue($return1);
		$this->assertIdenticalTrue($return2);
		$this->assertIdentical(FtpTestHelper::binaryToString($expected), FtpTestHelper::binaryToString($bufferRead),
			'%s - Write: [ASCII], Read: [Binary]'
		);
		$this->assertNoErrors();

		$ftp->delete($conf['root'].'/testfile');
		$ftp->setOptions(array('type'=>FTP_ASCII));
		$return1 = $ftp->write('testfile', $buffer);
		$ftp->setOptions(array('type'=>FTP_ASCII));
		$return2 = $ftp->read('testfile', $bufferRead);

		$expected = $this->getExpected($buffer, FTP_ASCII, FTP_ASCII, $syst);
		$this->assertIdenticalTrue($return1);
		$this->assertIdenticalTrue($return2);
		$this->assertIdentical(FtpTestHelper::binaryToString($expected), FtpTestHelper::binaryToString($bufferRead),
			'%s - Write: [ASCII], Read: [ASCII]'
		);

		$ftp->delete($conf['root'].'/testfile');
		$ftp->quit();
	}

	function testGetStore()
	{
		if (($conf = $this->getCredentials()) === false) {
			return $this->_reporter->setMissingTestCase('Credentials not set');
		}

		$buffer = '';
		for($i=0; $i<256; $i++) {
			$buffer .= chr($i);
		}
		$buffer .= $buffer;
		$sendFile = dirname(__FILE__).DS.'_files'.DS.'testfile.bin';
		$bufferTest = file_get_contents($sendFile);
		if ($buffer !== $bufferTest) {
			$this->fail('The file '.$sendFile.' does not contain the expected content.'
				.' (If you have uploaded this unit test via FTP, please use binary transfer mode).'
				.' Skipping this test.'
			);
			return;
		}
		if (($returnedFile = tempnam('', 'JoomlaUnitTest_')) === false) {
			$this->fail('Could not create a temporary file for file downloads. Skipping this test');
			return;
		}
		unlink($returnedFile);

		$ftp = new JFTP(array('timeout'=>5, 'type'=>FTP_BINARY));
		$ftp->connect($conf['host'], $conf['port']);
		$ftp->login($conf['user'], $conf['pass']);

		$return1 = $ftp->listNames($conf['root']);
		$return2 = $ftp->store($sendFile, $conf['root'].'/testfile.bin');
		$return3 = $ftp->listNames($conf['root']);
		$return4 = $ftp->get($returnedFile, $conf['root'].'/testfile.bin');

		$bufferRead = file_get_contents($returnedFile);
		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalTrue($return4);
		$this->assertIdenticalFalse(in_array('testfile.bin', $return1));
		$this->assertIdenticalTrue(in_array('testfile.bin', $return3));
		$this->assertIdentical(count($return1), count($return3)-1);
		$this->assertIdentical(FtpTestHelper::binaryToString($buffer), FtpTestHelper::binaryToString($bufferRead),
			'%s - Write: [Binary], Read: [Binary]'
		);
		$this->assertNoErrors();

		unlink($returnedFile);
		$ftp->delete($conf['root'].'/testfile.bin');
		$ftp->chdir($conf['root']);

		$return1 = $ftp->listNames();
		$return2 = $ftp->store($sendFile);
		$return3 = $ftp->listNames();
		$return4 = $ftp->get($returnedFile, 'testfile.bin');

		$bufferRead = file_get_contents($returnedFile);
		$this->assertIdenticalTrue($return2);
		$this->assertIdenticalTrue($return4);
		$this->assertIdenticalFalse(in_array('testfile.bin', $return1));
		$this->assertIdenticalTrue(in_array('testfile.bin', $return3));
		$this->assertIdentical(count($return1), count($return3)-1);
		$this->assertIdentical(FtpTestHelper::binaryToString($buffer), FtpTestHelper::binaryToString($bufferRead),
			'%s - Write: [Binary], Read: [Binary]'
		);
		$this->assertNoErrors();

		$return4 = $ftp->store($sendFile, $conf['root'].'/blablabla/testfile');

		$this->assertError('JFTP::store: Bad response');
		$this->assertIdenticalFalse($return4);

		$return5 = $ftp->get($returnedFile, $conf['root'].'/blablabla/testfile');

		$this->assertError('JFTP::get: Bad response');
		$this->assertIdenticalFalse($return5);

		/*
		 * In native mode, the destination file gets deleted if it does not exist on the server
		 * It is accepted behaviour that the compatibility layer does not do so, therefore the @
		 */
		@unlink($returnedFile);
		$ftp->delete($conf['root'].'/testfile.bin');
		$ftp->quit();
	}

	function disabledTestGetStoreModes()
	{
		$this->assertTrue( false );
	}

	function test_findMode()
	{
		$ftp = new JFTP(array('type'=>FTP_AUTOASCII));

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
		$before = FtpTestHelper::microtime_float();
		$ftp1 =& JFTP::getInstance('127.0.0.1', '1', array('timeout'=>0));
		$ftp2 =& JFTP::getInstance('127.0.0.1', '1', array('timeout'=>0));
		$ftp3 =& JFTP::getInstance('127.0.0.1', '1', array('test'));
		$ftp4 =& JFTP::getInstance('127.0.0.1', '2', array('timeout'=>0));
		$ftp5 =& JFTP::getInstance('127.0.0.1', '1', array('timeout'=>0), 'username', 'password');
		$ftp6 =& JFTP::getInstance('127.0.0.1', '1', null, 'username', 'password');
		$after = FtpTestHelper::microtime_float();

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

		$config = UnitTestHelper::makeCfg(JUT_FTP_CREDENTIALS, false, true);

		// Credentials valid?
		if (!($config['host'] && $config['port'] && $config['user'] && $config['pass'])) {
			$this->_credentials = null;
		} else {
			$this->_credentials = $config;
		}

		return $this->_credentials;

	}

	function invalidateCredentials()
	{
		$this->sendMessage('Invalidating Credentials due to previous error');
		$this->_credentials = null;
	}

	function getExpected($buffer, $sendMode, $receiveMode, $systRemote)
	{
		// Temporarily disabled
		$this->_reporter->setMissingTestCase('Temporarily disabled');
		return $buffer;

		$systLocal = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')? 'WIN' : 'UNIX';
		$systLocal = (strtoupper(substr(PHP_OS, 0, 3)) === 'MAC')? 'MAC' : $systLocal;

		// [Read] transformations in ASCII mode, client->server
		$read = array(
			'WIN' => array(
				'WIN' => null,
//				'UNIX' => array("\n", "\r\n"),
				'UNIX' => array(array("\r\n", "\n"), array("\n", "\r\n")),
				'MAC' => null //???
			),
			'UNIX' => array(
				'WIN' => array("\r", ''),
				'UNIX' => array("\r", ''),
				'MAC' => null //???
			),
			'MAC' => array(
				'WIN' => null, //???
				'UNIX' => null, //???
				'MAC' => null //???
			)
		);

		// [Write] transformations in ASCII mode, client->server
		$write = array(
			'WIN' => array(
				'WIN' => array("\n", "\r\n"),
//				'UNIX' => array("\r", ''),
				'UNIX' => null,
				'MAC' => null //???
			),
			'UNIX' => array(
				'WIN' => array("\n", "\r\n"),
				'UNIX' => array("\r", ''),
				'MAC' => null //???
			),
			'MAC' => array(
				'WIN' => null, //???
				'UNIX' => null, //???
				'MAC' => null //???
			)
		);

		// Apply transformations
		if ($sendMode == FTP_ASCII) {
			if (is_array($replace = $write[$systLocal][$systRemote])) {
				$buffer = str_replace($replace[0], $replace[1], $buffer);
			}
		}
		if ($receiveMode == FTP_ASCII) {
			if (is_array($replace = $read[$systLocal][$systRemote])) {
				$buffer = str_replace($replace[0], $replace[1], $buffer);
			}
		}

		return $buffer;
	}

	function assertIdenticalFalse($value, $message='%s')
	{
		return $this->assertIdentical($value, false, $message);
	}

	function assertIdenticalTrue($value, $message='%s')
	{
		return $this->assertIdentical($value, true, $message);
	}

	function listAllNames(&$ftp, $path) {
		$return = $ftp->listDetails($path);
		for ($i=0, $n=count($return); $i<$n; $i++) {
			$return[$i] = $return[$i]['name'];
		}
		return $return;
	}

/**
 * Leaving these functions for later...
 * Most of them are also already indirectly tested when using the Non-Native mode
 */
/*
	function testReinit()
	{
		return $this->_reporter->setMissingTestCase();
	}

	function testRestart()
	{
		return $this->_reporter->setMissingTestCase();
	}

	function test_putCmd()
	{
		return $this->_reporter->setMissingTestCase();
	}

	function test_verifyResponse()
	{
		return $this->_reporter->setMissingTestCase();
	}

	function test_passive()
	{
		return $this->_reporter->setMissingTestCase();
	}

	function test_mode()
	{
		return $this->_reporter->setMissingTestCase();
	}
*/

}

// Call TestOfJFTP::main() if this source file is executed directly.
if (JUNITTEST_MAIN_METHOD == "TestOfJFTP::main") {
	TestOfJFTP::main();
}
