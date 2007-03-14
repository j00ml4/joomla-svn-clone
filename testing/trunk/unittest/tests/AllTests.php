<?php
/**
 * Joomla! v1.5 UnitTest Platform.
 *
 * @version    $Id$
 * @package    Joomla
 * @subpackage UnitTest
 */

// Call AllTests::main() if this source file is executed directly.
if (!defined('JUNITTEST_MAIN_METHOD')) {
    define('JUNITTEST_MAIN_METHOD', 'AllTests::main');
    $JUNITTEST_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNITTEST_ROOT.'/unittest/prepend.php');
}

require_once JUNITTEST_BASE .'/libraries/joomla/AllTests.php';

class AllTests
{
    function main()
    {
		$self =& new AllTests;
		$suite =& $self->suite();
		$suite->run( UnitTestHelper::getReporter() );
    }

    function &suite()
    {
        $suite =& new TestSuite( 'Joomla! Framework' );

        $suite->addTestFile( 'libraries/JLoaderTest.php' );

        $suite->addTestCase( Joomla_AllTests::suite() );

        return $suite;
    }
}

if (JUNITTEST_MAIN_METHOD == 'AllTests::main') {
    AllTests::main();
}
