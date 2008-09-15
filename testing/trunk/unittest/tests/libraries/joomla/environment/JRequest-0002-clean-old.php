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
JLoader::injectMock(
	dirname(__FILE__) . DS . 'JFilterInput-mock-general.php',
	'joomla.filter.filterinput'
);

/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';
require_once 'JRequest-helper-dataset.php';


jimport( 'joomla.environment.request' );

/**
 * A unit test class for SubjectClass
 */
class JRequestTest_Clean extends PHPUnit_Framework_TestCase
{
	/**
	 * Define some sample data
	 */
	function setUp() {
		JRequestTest_DataSet::initSuperGlobals();
		// Make sure the request hash is clean.
		$GLOBALS['_JREQUEST'] = array();
	}

	function testRequestClean() {
		/*
		 * Call the method.
		 */
		$expect = count($_POST);
		JRequest::clean();
		$this->assertEquals($expect, count($_POST), '_POST[0] was modified.');
	}

	function testRequestCleanWithBanned() {
		try {
			$passed = false;
			$_POST['_post'] = 'This is banned.';
			/*
			 * Call the clean method.
			 */
			JRequest::clean();
		} catch (Exception $e) {
			$passed = true;
		}
		if (! $passed) {
			$this->fail('JRequest::clean() didn\'t die on a banned variable.');
		}
	}

	function testRequestCleanWithNumeric() {
		try {
			$passed = false;
			$_POST[0] = 'This is invalid.';
			/*
			 * Call the clean method.
			 */
			JRequest::clean();
		} catch (Exception $e) {
			$passed = true;
		}
		if (! $passed) {
			$this->fail('JRequest::clean() didn\'t die on a banned variable.');
		}
	}

	function testRequestCleanWithNumericString() {
		try {
			$passed = false;
			$_POST['0'] = 'This is invalid.';
			/*
			 * Call the clean method.
			 */
			JRequest::clean();
		} catch (Exception $e) {
			$passed = true;
		}
		if (! $passed) {
			$this->fail('JRequest::clean() didn\'t die on a banned variable.');
		}
	}

}