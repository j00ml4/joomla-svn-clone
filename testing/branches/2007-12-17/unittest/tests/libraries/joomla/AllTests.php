<?php
/**
 * Joomla! Unit Test Facility.
 *
 * @version $Id$
 * @package Joomla
 * @subpackage UnitTest
 */

// Call JUnit_AllTests::main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JUnit_AllTests::main');
	$JUNIT_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNIT_ROOT.'/unittest/setup.php');
}

require_once JUNIT_BASE .'/libraries/joomla/base/AllTests.php';
require_once JUNIT_BASE .'/libraries/joomla/utilities/AllTests.php';
require_once JUNIT_BASE .'/libraries/joomla/client/AllTests.php';
require_once JUNIT_BASE .'/libraries/joomla/application/AllTests.php';

class JUnit_AllTests
{
	function main()
	{
		$self =& new JUnit_AllTests;
		$suite =& $self->suite();
		$suite->run(UnitTestHelper::getReporter());
	}

	function &suite()
	{
		$suite =& new JoomlaTestSuite('Joomla! Libraries');

		$suite->addClassTest('libraries/joomla/JVersionTest.php');
		$suite->addClassTest('libraries/joomla/JFrameworkConfigTest.php');
		$suite->addClassTest('libraries/joomla/JFactoryTest.php');

		$suite->addTestCase(JUnit_Base_AllTests::suite());
		$suite->addTestCase(JUnit_Utilities_AllTests::suite());
		$suite->addTestCase(JUnit_Client_AllTests::suite());
		$suite->addTestCase(JUnit_Application_AllTests::suite());

		return $suite;
	}
}

if (JUNIT_MAIN_METHOD == 'JUnit_AllTests::main') {
	JUnit_AllTests::main();
}
