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

require_once JUNITTEST_BASE .'/libraries/joomla/AllTests.php';

class Libraries_AllTests
{
	function main()
	{
		$self  =& new Libraries_AllTests;
		$suite =& $self->suite();
		$suite->run( UnitTestHelper::getReporter() );
	}

	function &suite()
	{
		$suite =& new JoomlaTestSuite( 'Joomla! Framework' );

		$suite->addClassTest( 'libraries/JLoaderTest.php' );
		$suite->addTestCase( Joomla_Libraries_AllTests::suite() );

		return $suite;
	}
}

if (JUNITTEST_MAIN_METHOD == 'Joomla_AllTests::main') {
	Joomla_AllTests::main();
}
