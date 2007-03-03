<?php
/**
 * Joomla! v1.5 UnitTest Platform.
 *
 * @version    $Id$
 * @package    Joomla
 * @subpackage UnitTest
 */

// Call Joomla_Application_AllTests::main() if this source file is executed directly.
if (!defined('JUNITTEST_MAIN_METHOD')) {
    define('JUNITTEST_MAIN_METHOD', 'Joomla_Application_AllTests::main');
    $JUNITTEST_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNITTEST_ROOT.'/unittest/prepend.php');
}

require_once 'libraries/joomla/application/JRouterTest.php';
require_once 'libraries/joomla/application/JRouteTest.php';

class Joomla_Application_AllTests
{
    function main()
    {
		$self =& new Joomla_Application_AllTests;
		$suite =& $self->suite();
		$suite->run( UnitTestHelper::getReporter() );
    }

    function &suite()
    {
        $suite =& new TestSuite( 'Joomla! Framework' );

        $suite->addTestClass( 'TestOfJRouter' );
        $suite->addTestClass( 'TestOfJRoute' );

        return $suite;
    }
}

if (JUNITTEST_MAIN_METHOD == 'Joomla_Application_AllTests::main') {
    Joomla_Application_AllTests::main();
}
