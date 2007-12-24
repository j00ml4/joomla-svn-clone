<?php
/**
 * Joomla! Unit Test Facility.
 *
 * @version $Id$
 * @package Joomla
 * @subpackage UnitTest
 */

// Call JUnit_Base_AllTests::main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JUnit_Base_AllTests::main');
	$JUNIT_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNIT_ROOT.'/unittest/setup.php');
}

class JUnit_Base_AllTests
{
	function main()
	{
		$self =& new JUnit_Base_AllTests;
		$suite =& $self->suite();
		$suite->run(UnitTestHelper::getReporter());
	}

	function &suite()
	{
		$suite =& new JoomlaTestSuite('Joomla! Framework');

		$suite->addTestFile('libraries/joomla/base/JObject.0.class.test.php');

		return $suite;
	}
}

if (JUNIT_MAIN_METHOD == 'JUnit_Base_AllTests::main') {
	JUnit_Base_AllTests::main();
}
