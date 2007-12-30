<?php



// Call JObjectTest::main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JObjectTest::main');
	$JUNIT_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNIT_ROOT.'/unittest/setup.php');
}

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
		require_once 'PHPUnit/TextUI/TestRunner.php';

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
