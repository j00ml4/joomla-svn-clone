<?php
/**
 * Joomla! Unit Test Facility.
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id$
 */

// Call JUnit_Client_AllTests::main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
    define('JUNIT_MAIN_METHOD', 'JUnit_Client_AllTests::main');
    $JUNIT_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
    require_once($JUNIT_ROOT.'/unittest/setup.php');
}

class JUnit_Client_AllTests
{
    function main()
    {
        $self =& new JUnit_Client_AllTests;
        $suite =& $self->suite();
        $suite->run(UnitTestHelper::getReporter());
    }

    function &suite()
    {
        $suite =& new JoomlaTestSuite('Joomla! Framework');
        # see TODO 2
        $suite->addTestFile('libraries/joomla/client/JFTPTest.php');

        return $suite;
    }
}

if (JUNIT_MAIN_METHOD == 'JUnit_Client_AllTests::main') {
    JUnit_Client_AllTests::main();
}
