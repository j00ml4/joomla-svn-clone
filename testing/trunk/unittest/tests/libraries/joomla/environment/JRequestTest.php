<?php
/**
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

require 'j.php';

// Now load the Joomla environment
if (! defined('_JEXEC')) {
	define('_JEXEC', 1);
}
require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_LIBRARIES . '/joomla/import.php';
jimport( 'joomla.environment.request' );
require_once 'JRequest-helper-dataset.php';

/**
 * A unit test class for JRequest
 */
class JRequestTest extends PHPUnit_Framework_TestCase
{
	static public function getVarData()
	{
		return JRequestTest_DataSet::$getVarTests;
	}

	/**
	 * Clear the cache and load sample data
	 */
	function setUp()
	{
		JRequestTest_DataSet::initSuperGlobals();

		// Make sure the request hash is clean.
		$GLOBALS['_JREQUEST'] = array();
	}

	function testGetMethodIsPost()
	{
		$_SERVER['REQUEST_METHOD'] = 'post';
		$this->assertEquals('POST', JRequest::getMethod());
	}

	function testGetMethodIsGet()
	{
		$_SERVER['REQUEST_METHOD'] = 'get';
		$this->assertEquals('GET', JRequest::getMethod());
	}

	/**
	 * @dataProvider getVarData
	 */
	function testGetVarFromDataSet( $name, $default, $hash, $type, $mask, $expect )
	{
		$filter = JFilterInput::getInstance();

		// Get the variable and check the value.
		$actual = JRequest::getVar($name, $default, $hash, $type, $mask);
		$this->assertEquals($expect, $actual, 'Non-cached getVar');

		// Repeat the process to check caching (the JFilterInput mock should not
		// get called unless the default is being used).
		$actual = JRequest::getVar($name, $default, $hash, $type, $mask);
		$this->assertEquals($expect, $actual, 'Cached getVar');
	}

	function testRequestClean()
	{
		$expect = count($_POST);
		JRequest::clean();
		$this->assertEquals($expect, count($_POST), '_POST[0] was modified.');
	}

	function testRequestCleanWithBanned()
	{
		try {
			$passed = false;
			$_POST['_post'] = 'This is banned.';
			JRequest::clean();
		}
		catch (Exception $e) {
			$passed = true;
		}
		if (! $passed) {
			$this->fail('JRequest::clean() didn\'t die on a banned variable.');
		}
	}

	function testRequestCleanWithNumeric()
	{
		try {
			$passed = false;
			$_POST[0] = 'This is invalid.';
			JRequest::clean();
		}
		catch (Exception $e) {
			$passed = true;
		}
		if (! $passed) {
			$this->fail('JRequest::clean() didn\'t die on a banned variable.');
		}
	}

	function testRequestCleanWithNumericString()
	{
		try {
			$passed = false;
			$_POST['0'] = 'This is invalid.';
			JRequest::clean();
		}
		catch (Exception $e) {
			$passed = true;
		}
		if (! $passed) {
			$this->fail('JRequest::clean() didn\'t die on a banned variable.');
		}
	}
}
