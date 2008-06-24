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
	$JUnit_root = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once $JUnit_root . '/unittest/setup.php';
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

require_once JUNIT_BASE .'/libraries/joomla/base/AllTests.php';
require_once JUNIT_BASE .'/libraries/joomla/utilities/AllTests.php';
require_once JUNIT_BASE .'/libraries/joomla/client/AllTests.php';
require_once JUNIT_BASE .'/libraries/joomla/application/AllTests.php';

class PHPUnit_AllTests
{
	function main()
	{
		$self =& new PHPUnit_AllTests;
		$suite =& $self->suite();
		$suite->run(JUnit_Setup::getReporter());
	}

	function &suite()
	{
		$suite =& new JoomlaTestSuite('Joomla! Libraries');

		$suite->addClassTest('libraries/joomla/JVersionTest.php');
		$suite->addClassTest('libraries/joomla/JFrameworkConfigTest.php');
		$suite->addClassTest('libraries/joomla/JFactoryTest.php');

		$suite->addTestCase(PHPUnit_Base_AllTests::suite());
		$suite->addTestCase(PHPUnit_Utilities_AllTests::suite());
		$suite->addTestCase(PHPUnit_Client_AllTests::suite());
		$suite->addTestCase(PHPUnit_Application_AllTests::suite());

		return $suite;
	}
}

if (JUNIT_MAIN_METHOD == 'PHPUnit_AllTests::main') {
	PHPUnit_AllTests::main();
}
