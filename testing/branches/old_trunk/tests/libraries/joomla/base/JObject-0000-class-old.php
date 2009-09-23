<?php
/**
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

/*
 * Now load the Joomla environment
 */
if (!defined( '_JEXEC' ) ) {
	define( '_JEXEC', 1 );
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

require_once 'JObject-helper.php';

class JObjectTest extends PHPUnit_Framework_TestCase
{
	var $instance = null;

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
