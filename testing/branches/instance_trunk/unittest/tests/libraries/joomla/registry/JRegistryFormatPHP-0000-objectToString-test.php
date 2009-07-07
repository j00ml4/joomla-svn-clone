<?php
/**
 * JFilterInput clean tests
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id: $
 * @author Jui-Yu Tsai <raytsai@gmail.com>
 */

// Call JRegistryFormatPHPTest_ObjectToString::main() if this source file is executed directly.
if (! defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JRegistryFormatPHPTest_ObjectToString::main');
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

/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

jimport( 'joomla.registry.format' );
jimport( 'joomla.registry.format.php' );
require_once JPATH_LIBRARIES . '/joomla/registry/format.php';
require_once JPATH_LIBRARIES . '/joomla/registry/format/php.php';

class JRegistryFormatPHPTest_ObjectToString extends PHPUnit_Framework_TestCase {

	var $instance = null;

	/**
	 * Runs the test methods of this class.
	 */
	static function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	function setUp() {
		$this->instance = new JRegistryFormatPHP;
	}

	function objectFactory($properties) {
		$obj = new stdClass();
		foreach($properties AS $k => $v) {
			$obj->{$k} = $v;
		}
		return $obj;
	}

	static function dataSet() {
		$params = array('class' => 'testClassName');

		$cases = array(
			'Regular Object' => array(
				JRegistryFormatPHPTest_ObjectToString::objectFactory(array('test1' => 'value1', 'test2' => 'value2')),
				array('class' => 'myClass'),
				'<?php'."\n".'class myClass {'."\n\t".'var $test1 = \'value1\';'."\n\t".'var $test2 = \'value2\';'."\n}\n".'?>'
			),
			'Object with Double Quote' => array(
				JRegistryFormatPHPTest_ObjectToString::objectFactory(array('test1' => 'value1"', 'test2' => 'value2')),
				array('class' => 'myClass'),
				'<?php'."\n".'class myClass {'."\n\t".'var $test1 = \'value1"\';'."\n\t".'var $test2 = \'value2\';'."\n}\n".'?>'
			)

		);
		$tests = $cases;

		return $tests;
	}

	/**
	 * Execute a test case on clean().
	 *
	 * The test framework calls this function once for each element in the array
	 * returned by the named data provider.
	 *
	 * @dataProvider dataSet
	 * @param string The type of input
	 * @param string The input
	 * @param string The expected result for this test.
	 */
	function testObjectToString(&$object, $params, $expect) {
		$this->assertEquals($expect, $this->instance->objectToString($object, $params));
	}

}

// Call JFilterInputTest::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JRegistryFormatPHPTest_ObjectToString::main') {
	JRegistryFormatPHPTest_ObjectToString::main();
}
