<?php
/**
 * Joomla! v1.5 UnitTest Platform.
 *
 * @version    $Id$
 * @package    Joomla.Framework
 * @subpackage UnitTest
 */

// Call Joomla_Client_AllTests::main() if this source file is executed directly.
if (!defined('JUNITTEST_MAIN_METHOD')) {
    define('JUNITTEST_MAIN_METHOD', 'Joomla_Client_AllTests::main');
    $JUNITTEST_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNITTEST_ROOT.'/unittest/prepend.php');
}

class Joomla_Client_AllTests
{
    function main()
    {
		$self =& new Joomla_Client_AllTests;
		$suite =& $self->suite();
		$suite->run( UnitTestHelper::getReporter() );
    }

    function &suite()
    {
        $suite =& new JoomlaTestSuite( 'Joomla! Framework' );
		# see TODO 2
        $suite->addTestFile( 'libraries/joomla/client/JFTPTest.php' );

        return $suite;
    }
}

if (JUNITTEST_MAIN_METHOD == 'Joomla_Client_AllTests::main') {
    Joomla_Client_AllTests::main();
}
