<?php
/**
 * Description
 *
 * @package 	Joomla
 * @subpackage 	Unittest
 * @author 		Rene Serradeil <serradeil@webmechanic.biz>
 * @version 	$Id$
 */

// Call TestOfJLoader::main() if this source file is executed directly.
if (!defined('JUNITTEST_MAIN_METHOD')) {
    define('JUNITTEST_MAIN_METHOD', 'TestOfJLoader::main');
    $JUNITTEST_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNITTEST_ROOT.'/unittest/prepend.php');
}

class TestOfJLoader extends UnitTestCase
{
	/**
	 * Runs the test methods of this class.
	 *
	 * @access public
	 * @static
	 */
	function main() {
		$self = new TestOfJLoader;
		$self->run( UnitTestHelper::getReporter(null, __FILE__) );
	}

	/** function import($filePath) */
	function test_import()
	{
	}

	/** function &factory($class, $options=null) */
	function test_factory()
	{
	}

	/** function _requireOnce( $file ) */
	function test__requireOnce()
	{
	}

}

// Call TestOfJLoader::main() if this source file is executed directly.
if (JUNITTEST_MAIN_METHOD == "TestOfJLoader::main") {
	TestOfJLoader::main();
}
