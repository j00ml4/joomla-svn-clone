<?php
/**
 * Joomla! v1.5 UnitTest Platform.
 *
 * @version    $Id$
 * @package    Joomla
 * @subpackage UnitTest
 */

// Call Joomla_AllTests::main() if this source file is executed directly.
if (!defined('JUNITTEST_MAIN_METHOD')) {
    define('JUNITTEST_MAIN_METHOD', 'Joomla_AllTests::main');
    $JUNITTEST_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNITTEST_ROOT.'/unittest/prepend.php');
}

class Joomla_AllTests
{
    function main()
    {
		$self  =& new Joomla_AllTests;
		$suite =& $self->suite();
		$suite->run( UnitTestHelper::getReporter() );
    }

    function &suite()
    {
        $suite =& new JoomlaTestSuite( 'Joomla! Framework' );

        $suite->addClassTest( 'libraries/JLoaderTest.php' );

        return $suite;
    }
}

if (JUNITTEST_MAIN_METHOD == 'Joomla_AllTests::main') {
    Joomla_AllTests::main();
}
