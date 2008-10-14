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

 // Call main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JLanguageTest::main');
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
// Include mocks here
/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

jimport('joomla.registry.registry');
jimport('joomla.language.language');

class JLanguageTest extends PHPUnit_Framework_TestCase
{
	var $instance = null;

	/**
	 * Runs the test methods of this class.
	 *
	 */
	function main() {
		$suite = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	function setUp()
	{
		$this -> instance = JLanguage::getInstance(null);
	}

	function tearDown()
	{
		unset($this -> instance);
		$this -> instance = null;
	}

	function testGetLanguagePath() {
		$path = JLanguage::getLanguagePath(JPATH_BASE);
		$this -> assertEquals($path, JPATH_BASE . DS . 'language');
		$path = JLanguage::getLanguagePath(JPATH_BASE, 'foo-BAR');
		$this -> assertEquals($path, JPATH_BASE . DS . 'language' . DS . 'foo-BAR');
	}

	function testGetMetadataValid() {
		$data = JLanguage::getMetadata('en-GB');
		$this -> assertTrue(is_array($data));
	}

	function testGetMetadataInvalid() {
		$data = JLanguage::getMetadata('foo-BAR');
		$this -> assertNull($data);
	}

	function testClassType() {
		$this -> assertType('JLanguage', $this -> instance);
	}

	function testGetSetLanguageValid() {
		$prev = $this -> instance -> setLanguage('en-GB');
		$this -> assertEquals($prev, 'en-GB');
		if (JUnit_Setup::isTestEnabled(JVERSION, array('jver_min' => '1.6.0'))) {
			$this -> assertEquals($this -> instance -> getLanguageIdentifier(), 'en-GB');
		}
	}

	function testGetSetLanguageInvalid() {
		$prev = $this -> instance -> setLanguage('foo-BAR');
		$this -> assertFalse($prev);
		if (JUnit_Setup::isTestEnabled(JVERSION, array('jver_min' => '1.6.0'))) {
			$this -> assertEquals($this -> instance -> getLanguageIdentifier(), 'en-GB');
		}
	}

	function testload() {
		$this -> instance -> setLanguage('en-GB');
		// force a reload
		$result = $this -> instance -> load('joomla', JPATH_BASE, null, true);
		$this -> assertTrue($result);
	}

	function test_() {
		$lang = &JFactory::getLanguage();
	}

}

// Call JLanguageTest::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JLanguageTest::main') {
	JLanguageTest::main();
}