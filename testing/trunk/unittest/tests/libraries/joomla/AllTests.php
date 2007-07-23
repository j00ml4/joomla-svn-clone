<?php
/**
 * Joomla! v1.5 UnitTest Platform.
 *
 * @version    $Id$
 * @package    Joomla.Framework
 * @subpackage UnitTest
 */

// Call Joomla_AllTests::main() if this source file is executed directly.
if (!defined('JUNITTEST_MAIN_METHOD')) {
    define('JUNITTEST_MAIN_METHOD', 'Joomla_AllTests::main');
    $JUNITTEST_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNITTEST_ROOT.'/unittest/prepend.php');
}

require_once JUNITTEST_BASE .'/libraries/joomla/base/AllTests.php';
require_once JUNITTEST_BASE .'/libraries/joomla/utilities/AllTests.php';
require_once JUNITTEST_BASE .'/libraries/joomla/client/AllTests.php';
require_once JUNITTEST_BASE .'/libraries/joomla/application/AllTests.php';

class Joomla_AllTests
{
    function main()
    {
		$self =& new Joomla_AllTests;
		$suite =& $self->suite();
		$suite->run( UnitTestHelper::getReporter() );
    }

    function &suite()
    {
        $suite =& new JoomlaTestSuite( 'Joomla! Libraries' );

        $suite->addClassTest( 'libraries/joomla/JVersionTest.php' );
        $suite->addClassTest( 'libraries/joomla/JFrameworkConfigTest.php' );
        $suite->addClassTest( 'libraries/joomla/JFactoryTest.php' );

        $suite->addTestCase( Joomla_Base_AllTests::suite() );
        $suite->addTestCase( Joomla_Utilities_AllTests::suite() );
        $suite->addTestCase( Joomla_Client_AllTests::suite() );
        $suite->addTestCase( Joomla_Application_AllTests::suite() );

        return $suite;
    }
}

if (JUNITTEST_MAIN_METHOD == 'Joomla_AllTests::main') {
    Joomla_AllTests::main();
}
