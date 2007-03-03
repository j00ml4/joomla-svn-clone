<?php
/**
 * Joomla! v1.5 UnitTest Platform.
 *
 * @version    $Id$
 * @package    Joomla
 * @subpackage UnitTest
 */

// Call Joomla_Base_AllTests::main() if this source file is executed directly.
if (!defined('JUNITTEST_MAIN_METHOD')) {
    define('JUNITTEST_MAIN_METHOD', 'Joomla_Base_AllTests::main');
    $JUNITTEST_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNITTEST_ROOT.'/unittest/prepend.php');
}

require_once 'libraries/joomla/base/JObjectTest.php';

class Joomla_Base_AllTests
{
    function main()
    {
		$self =& new Joomla_Base_AllTests;
		$suite =& $self->suite();
		$suite->run( UnitTestHelper::getReporter() );
    }

    function &suite()
    {
        $suite =& new TestSuite( 'Joomla! Framework' );

        $suite->addTestClass( 'TestOfJObject' );

        return $suite;
    }
}

if (JUNITTEST_MAIN_METHOD == 'Joomla_Base_AllTests::main') {
    Joomla_Base_AllTests::main();
}
