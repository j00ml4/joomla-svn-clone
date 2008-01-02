<?php



// Call JObjectTest::main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JObjectTest::main');
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

require_once 'JObject-helper.php';

class JObjectTest extends PHPUnit_Framework_TestCase
{
	var $instance = null;

	/**
	 * Runs the test methods of this class.
	 *
	 * @access public
	 * @static
	 */
	function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	function setUp()
	{
		$this->instance = new JObjectExtend;
	}

	function tearDown()
	{
		$this->instance = null;
	}

	function testJObject()
	{
		$this->assertType('JObject', new JObject);
		$this->assertType('JObjectExtend', $this->instance);
	}

	function test__construct()
	{
		$obj = new JObjectExtend(array('construcVar'=>'tested'));
		// $publicVar, $construcVar
		$this->assertEquals($obj->construcVar, 'tested');
		$this->assertEquals($obj->get('construcVar'), 'tested');
	}

	function testSet()
	{
		$old = $this->instance->set('test', 'data');
		$this->assertNull($old);
	}

	function testGet()
	{
		$this->instance->set('test', 'data');

		$compare = $this->instance->get('test');
		$this->assertEquals($compare, 'data');

		$compare = $this->instance->get('text', 'string');
		$this->assertEquals($compare, 'string');
	}

	function testGetProperties()
	{
		$properties = $this->instance->getProperties(false);
		$expect = array(
			'_privateVar' => 'Private',
			'publicVar' => 'Public',
			'constructVar' => 'Constructor',
			'_errors' => array(),
		);
		$this->assertEquals($properties, $expect);
	}

	function testGetPublicProperties()
	{
		$properties = $this->instance->getPublicProperties();
		$expect = array(
			'publicVar' => 'Public',
			'constructVar' => 'Constructor',
		);
		$this->assertEquals($properties, $expect);
	}

	function testToString()
	{
		$string = $this->instance->toString();
		if ((int)PHP_VERSION >= 5) {
			$this->assertEquals($string, 'JObjectExtend');
		} else {
			$this->assertEquals($string, strtolower('JObjectExtend'));
		}
	}

}

// Call JObjectTest::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JObjectTest::main') {
	JObjectTest::main();
}
