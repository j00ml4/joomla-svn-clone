<?php
/**
 * Joomla! Unit Test Facility.
 *
 * @version $Id$
 * @package Joomla
 * @subpackage UnitTest
 */

// Call PHPUnit_AllTests::main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'PHPUnit_AllTests::main');
	$JUNIT_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNIT_ROOT.'/unittest/setup.php');
}

require_once JUNIT_BASE .'/libraries/joomla/AllTests.php';

class Libraries_AllTests
{
	function main()
	{
		$self  =& new Libraries_AllTests;
		$suite =& $self->suite();
		$suite->run(UnitTestHelper::getReporter());
	}

	function &suite()
	{
		$suite =& new JoomlaTestSuite('Joomla! Framework');

		$suite->addClassTest('libraries/JLoaderTest.php');
		$suite->addTestCase(PHPUnit_Libraries_AllTests::suite());

		return $suite;
	}
}

if (JUNIT_MAIN_METHOD == 'PHPUnit_AllTests::main') {
	PHPUnit_AllTests::main();
}
