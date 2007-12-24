<?php
/**
 * Test class for JFrameworkConfig.
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id$
 */

// Call TestOfJFrameworkConfig::main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'TestOfJFrameworkConfig::main');
	$JUNIT_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNIT_ROOT.'/unittest/setup.php');
}

/* class to test */
require_once('libraries/joomla/config.php');

/**
 * Test class for JFrameworkConfig.
 * Generated by PHPUnit_Util_Skeleton on 2007-02-21 at 23:19:20.
 */
class TestOfJFrameworkConfig extends UnitTestCase {

var $instance; // Hi!
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
	 *
	 * @access public
	 * @static
	 */
	function main() {
		$self = new TestOfJFrameworkConfig;
		$self->run(UnitTestHelper::getReporter());
	}

	function setUp() {
		$this->instance = new JFrameworkConfig();
	}

	function tearDown() {
		$this->instance = null;
		unset($this->instance);
	}

	function test_inheritance() {
		$this->sendMessage("There isn't much this TestCase can actually test. However, let´s try...");

		// it should not inherit from anything
		$parent = get_parent_class($this->instance);
		$this->assertIsA($this->instance, 'JFrameworkConfig');

		$this->assertFalse(is_subclass_of($this->instance, 'JObject'));
	}

	function test_property_names() {
		$compare = (array) $this->instance;
		foreach (array_keys($this->proto) as $expect) {
			$this->assertTrue(array_key_exists($expect, $compare), "{$expect} not found");
		}
	}

	function test_property_defaults() {
		$compare = (array) $this->instance;
		foreach ($this->proto as $prop => $expect) {
			$this->assertEqual($expect, $compare[$prop], $prop.' %s');
		}
	}

}

// Call TestOfJFrameworkConfig::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == "TestOfJFrameworkConfig::main") {
	TestOfJFrameworkConfig::main();
}
