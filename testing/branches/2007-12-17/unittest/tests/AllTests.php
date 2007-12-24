<?php
/**
 * Joomla! Unit Test Facility.
 *
 * @version $Id: $
 * @package Joomla
 * @subpackage UnitTest
 */

// Call Framework_AllTests::main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'Framework_AllTests::main');
	$JUNIT_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNIT_ROOT.'/unittest/setup.php');
}

require_once JUNIT_BASE .'/libraries/AllTests.php';

class UnitTests_AllTests
{
	function main()
	{
		$self =& new UnitTests_AllTests;
		$suite =& $self->suite();
		$suite->run(UnitTestHelper::getReporter());
	}

	function &suite()
	{
		$suite =& new JoomlaTestSuite('Joomla! Framework');

		$suite->addTestCase(JUnit_AllTests::suite());

		return $suite;
	}
}

if (JUNIT_MAIN_METHOD == 'Framework_AllTests::main') {
	Framework_AllTests::main();
}
