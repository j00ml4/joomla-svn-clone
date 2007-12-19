<?php
/**
 * Joomla! v1.5 UnitTest Platform.
 *
 * @version    $Id$
 * @package    Joomla.Framework
 * @subpackage UnitTest
 */

// Call Framework_AllTests::main() if this source file is executed directly.
if (!defined('JUNITTEST_MAIN_METHOD')) {
	define('JUNITTEST_MAIN_METHOD', 'Framework_AllTests::main');
	$JUNITTEST_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNITTEST_ROOT.'/unittest/prepend.php');
}

require_once JUNITTEST_BASE .'/libraries/AllTests.php';

class UnitTests_AllTests
{
	function main()
	{
		$self =& new UnitTests_AllTests;
		$suite =& $self->suite();
		$suite->run( UnitTestHelper::getReporter() );
	}

	function &suite()
	{
		$suite =& new JoomlaTestSuite( 'Joomla! Framework' );

		$suite->addTestCase( Joomla_AllTests::suite() );

		return $suite;
	}
}

if (JUNITTEST_MAIN_METHOD == 'Framework_AllTests::main') {
	Framework_AllTests::main();
}
