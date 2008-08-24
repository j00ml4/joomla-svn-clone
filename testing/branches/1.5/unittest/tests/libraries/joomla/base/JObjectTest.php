<?php
/**
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

require 'j.php';


class JObjectTest extends PHPUnit_Framework_TestCase
{
	var $instance = null;

	function setUp()
	{
		// Load the Joomla environment
		if (!defined( '_JEXEC' ) ) {
			define( '_JEXEC', 1 );
		}
		require_once JPATH_BASE . '/includes/defines.php';
		require_once JPATH_LIBRARIES . '/joomla/import.php';

		require_once 'JObject-helper.php';

		$this->instance = new DerivedFromJObject;
	}

	function tearDown()
	{
		$this->instance = null;
	}

	function testNewJobjectReturnsCorrectType()
	{
		$this->assertThat(
			new JObject,
			$this->isInstanceOf( 'JObject' )
		);
		$this->assertThat(
			$this->instance,
			$this->isInstanceOf( 'DerivedFromJObject' )
		);
	}

	function testJobjectConstructorArgumentsSetInternalProperties()
	{
		$obj = new DerivedFromJObject(
			array( 'construcVar' => 'tested' )
		);
		$this->assertThat(
			$obj->construcVar,
			$this->equalTo( 'tested' )
		);
		$this->assertThat(
			$obj->get( 'construcVar' ),
			$this->equalTo( 'tested' )
		);
	}

	function testSetMethodReturnsPreviousPropertyState()
	{
		$this->assertNull(
			$this->instance->set( 'newvar', 'data' )
		);
	}

	function testGetMethodReturnsPreviouslySetData()
	{
		$this->instance->set( 'testvar', 'data' );

		$this->assertThat(
			$this->instance->get( 'testvar' ),
			$this->equalTo( 'data' )
		);
	}

	function testGetMethodReturnsDefaultForUnsetProperties()
	{
		$this->assertEquals(
			$this->instance->get( 'notset', 'string' ),
			'string'
		);
	}

	function testGetPropertiesReturnsAllProperties()
	{
		$expect = array(
			'_privateVar' => 'Private',
			'publicVar' => 'Public',
			'constructVar' => 'Constructor',
			'_errors' => array(),
		);
		$this->assertThat(
			$this->instance->getProperties( false ),
			$this->equalTo( $expect )
		);
	}

	function testGetPropertiesReturnsPublicProperties()
	{
		$expect = array(
			'publicVar' => 'Public',
			'constructVar' => 'Constructor',
		);
		$this->assertThat(
			$this->instance->getProperties(),
			$this->equalTo( $expect )
		);
	}

	function testToString()
	{
		$string = $this->instance->toString();
		if ((int) PHP_VERSION >= 5) {
			$this->assertEquals($string, 'DerivedFromJObject');
		}
		else {
			$this->assertEquals($string, strtolower('DerivedFromJObject'));
		}
	}
}