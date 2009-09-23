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
if (! defined('_JEXEC')) {
	define('_JEXEC', 1);
}
require_once JPATH_BASE . '/includes/defines.php';
/*
 * Mock classes
 */
// (no mocks for this test)

/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

jimport( 'joomla.environment.request' );

/**
 * A unit test class for JRequest
 */
class JRequestTest_GetMethod extends PHPUnit_Framework_TestCase
{
	/**
	 * Clear the cache
	 */
	function setUp() {
		// Make sure the request hash is clean.
		$GLOBALS['_JREQUEST'] = array();
	}

	function testGetMethod()
	{
		$_SERVER['REQUEST_METHOD'] = 'post';
		$this->assertEquals('POST', JRequest::getMethod());
		$_SERVER['REQUEST_METHOD'] = 'get';
		$this->assertEquals('GET', JRequest::getMethod());
	}

}