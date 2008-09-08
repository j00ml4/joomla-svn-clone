<?php
/**
 * Joomla! v1.5 Unit Test Facility
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright Copyright (C) 2005 - 2008 Open Source Matters, Inc.
 * @version $Id: $
 * @author John Wicks
 *
 */

 // Call main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JTextTest::main');
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
require_once JPATH_LIBRARIES . '/joomla/factory.php';

require_once JPATH_LIBRARIES . '/joomla/methods.php';

class JTextTest extends PHPUnit_Framework_TestCase
{
	var $instance = null;

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

	function setUp()
	{
		$this->instance = new JText;
	}

	function tearDown()
	{
		$this->instance = null;
		unset($this->instance);
	}

	function testJText()
	{
		$this->assertType('JText',$this->instance);
	}

	function test_()
	{
		$lang =& JFactory::getLanguage();
		$this->assertEquals($lang->_('Next'), $this->instance->_('Next'));

		$lang->setLanguage('es-ES');
		if($lang->load('es-ES')){
			$this->assertNotEquals('Next', $this->instance->_('Next'));
			$this->assertEquals($lang->_('Next'),$this->instance->_('Next'));
		}
	}

	function testSprintf()
	{
		// Remove the following line when you implement this test.
		return $this -> markTestSkipped();
	}

	function testPrintf()
	{
		// Remove the following line when you implement this test.
		return $this -> markTestSkipped();
	}
}

// Call JTextTest::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JTextTest::main') {
	JTextTest::main();
}