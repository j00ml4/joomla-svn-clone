<?php
/**
 * Joomla! v1.5 Unit Test Facility
 *
 * Template for a basic unit test
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright Copyright (C) 2005 - 2008 Open Source Matters, Inc.
 * @version $Id: $
 *
 */

if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JRequestTest_Clean::main');
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
	 * Runs the test methods of this class.
	 *
	 * @access public
	 * @static
	 */
	function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

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
		$this -> assertEquals($expect, count($_POST), '_POST[0] was modified.');
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
			$this -> fail('JRequest::clean() didn\'t die on a banned variable.');
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
			$this -> fail('JRequest::clean() didn\'t die on a banned variable.');
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
			$this -> fail('JRequest::clean() didn\'t die on a banned variable.');
		}
	}

}

// Call main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JRequestTest_Clean::main') {
	JRequestTest_Clean::main();
}
