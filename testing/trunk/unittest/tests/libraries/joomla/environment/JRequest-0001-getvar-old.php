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
class JRequestTest_GetVar extends PHPUnit_Framework_TestCase
{
	static public function getVarData() {
		return JRequestTest_DataSet::$getVarTests;
	}

	/**
	 * Define some sample data
	 */
	function setUp() {
		JRequestTest_DataSet::initSuperGlobals();
		// Make sure the request hash is clean.
		$GLOBALS['_JREQUEST'] = array();
	}

	/**
	 * @dataProvider getVarData
	 */
	function testGetVarFromDataSet(
		$name, $default, $hash, $type, $mask, $expect, $filterCalls
	) {
		$filter = JFilterInput::getInstance();
		$filter->mockReset();
		if (count($filterCalls)) {
			foreach ($filterCalls as $info) {
				$filter->mockSetUp(
					$info[0], $info[1], $info[2], $info[3]
				);
			}
		}
		/*
		 * Get the variable and check the value.
		 */
		$actual = JRequest::getVar($name, $default, $hash, $type, $mask);
		$this->assertEquals($expect, $actual, 'Non-cached getVar');
		/*
		 * Repeat the process to check caching (the JFilterInput mock should not
		 * get called unless the default is being used).
		 */
		$actual = JRequest::getVar($name, $default, $hash, $type, $mask);
		$this->assertEquals($expect, $actual, 'Cached getVar');
		if (($filterOK = $filter->mockTearDown()) !== true) {
			$this->fail(
				'JFilterInput not called as expected:'
				. print_r($filterOK, true)
			);
		}
	}

}