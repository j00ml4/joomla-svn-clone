<?php
/**
 * Joomla! v1.5 Unit Test Facility
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright Copyright (C) 2005 - 2008 Open Source Matters, Inc.
 * @version $Id: $
 *
 */

if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JFolderTest_static::main');
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
// (no mocks for this test)

/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

jimport( 'joomla.filesystem.folder' );

require_once 'JFolder-helper-dataset.php';

/**
 * A unit test class for JRequest
 */
class JFolderTest_static extends PHPUnit_Framework_TestCase
{
	/**
	 * Runs the test methods of this class.
	 *
	 * @access public
	 * @static
	 */
	static function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	/**
	 * Clear the cache
	 */
	function setUp() {

	}

	static public function makeSafeData() {
		return JFolderTest_DataSet::$makeSafeTests;
	}

	/**
	 * @dataProvider makeSafeData
	 */
	function testMakeSafeFromDataSet($path, $expect) {
		if (!JUnit_Setup::isTestEnabled(JVERSION, array('jver_min' => '1.5.0'))) {
			$this -> markTestSkipped('These tests are designed for J1.5+');
			return;
		}
		$actual = JFolder::makeSafe($path);
		$this->assertEquals($expect, $actual);
	}

}

// Call main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JFolderTest_static::main') {
	JRequestTest_GetMethod::main();
}
