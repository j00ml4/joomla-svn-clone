<?php
/**
 * Test class for JFrameworkConfig.
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id$
 */

// Call JFrameworkConfigTest::main() if this source file is executed directly.
if (! defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JFrameworkConfigTest::main');
	$JUnit_home = DIRECTORY_SEPARATOR . 'unittest' . DIRECTORY_SEPARATOR;
	if (($JUnit_posn = strpos(__FILE__, $JUnit_home)) === false) {
		die('Unable to find ' . $JUnit_home . ' in path.');
	}
	$JUnit_posn += strlen($JUnit_home) - 1;
	$JUnit_root = substr(__FILE__, 0, $JUnit_posn);
	$JUnit_start = substr(
		__FILE__,
		$JUnit_posn + 1,
		strlen(__FILE__) - strlen(basename(__FILE__)) - $JUnit_posn - 2
	);
	require_once $JUnit_root . DIRECTORY_SEPARATOR . 'setup.php';
}

/*
 * Now load the Joomla environment
 */
if (! defined('_JEXEC')) {
	define('_JEXEC', 1);
}
require_once JPATH_BASE . '/includes/defines.php';
/*
 * Mock classes
 */
// Include mocks here
/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

/* class to test */
require_once JPATH_LIBRARIES . DS . 'joomla' . DS . 'config.php';

/**
 * Test class for JFrameworkConfig.
 * Generated by PHPUnit_Util_Skeleton on 2007-02-21 at 23:19:20.
 */
class JFrameworkConfigTest extends PHPUnit_Framework_TestCase {

	var $fixture;
	var $proto = array(
		'dbtype'=>'mysql',
		'host'=>'localhost',
		'user'=>'',
		'password'=>'',
		'db'=>'',
		'dbprefix'=>'jos_',
		'ftp_host'=>'127.0.0.1',
		'ftp_port'=>21,
		'ftp_user'=>'',
		'ftp_pass'=>'',
		'ftp_root'=>'',
		'ftp_enable'=>0,
		'tmp_path'=>'/tmp',
		'log_path'=>'/var/logs',
		'mailer'=>'mail',
		'mailfrom'=>'admin@localhost.home',
		'fromname'=>'',
		'sendmail'=>'/usr/sbin/sendmail',
		'smtpauth'=>'0',
		'smtpuser'=>'',
		'smtppass'=>'',
		'smtphost'=>'localhost',
		'debug'=>0,
		'caching'=>0,
		'cachetime'=>900,
		'language'=>'en-GB',
		'secret'=>null,
		'editor'=>'none',
		'offset'=>0,
		'lifetime'=>15
	);

	/**
	 * Runs the test methods of this class.
	 */
	function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	function setUp() {
		$this->fixture = new JFrameworkConfig();
	}

	function tearDown() {
		$this->fixture = null;
		unset($this->fixture);
	}

	function test_inheritance() {
		// it should not inherit from anything
		$this->assertTrue($this->fixture instanceof JFrameworkConfig);
		$this->assertFalse(get_parent_class($this->fixture));
	}

	function test_property_names() {
		$compare = (array) $this->fixture;
		foreach (array_keys($this->proto) as $expect) {
			$this->assertTrue(array_key_exists($expect, $compare), $expect . ' not found.');
		}
	}

	function test_property_defaults() {
		$compare = (array) $this->fixture;
		foreach ($this->proto as $prop => $expect) {
			$this->assertEquals($expect, $compare[$prop]);
		}
	}

}

// Call JFrameworkConfigTest::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JFrameworkConfigTest::main') {
	JFrameworkConfigTest::main();
}
