<?php
/**
 * Joomla! Unit Test Facility.
 *
 * @version $Id: $
 * @package Joomla
 * @subpackage UnitTest
 */

// Call JUnit_Utilities_AllTests::main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JUnit_Utilities_AllTests::main');
	$JUNIT_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNIT_ROOT.'/unittest/setup.php');
}

class JUnit_Utilities_AllTests
{
	function main()
	{
		$self =& new JUnit_Utilities_AllTests;
		$suite =& $self->suite();
		$suite->run(UnitTestHelper::getReporter());
	}

	function &suite()
	{
		$suite =& new JoomlaTestSuite('Joomla! Framework');

		$suite->addTestFile('libraries/joomla/utilities/JArrayHelperTest.php');

		return $suite;
	}
}

if (JUNIT_MAIN_METHOD == 'JUnit_Utilities_AllTests::main') {
	JUnit_Utilities_AllTests::main();
}
