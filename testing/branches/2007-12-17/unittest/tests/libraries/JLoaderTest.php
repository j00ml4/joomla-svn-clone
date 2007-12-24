<?php
/**
 * Description
 *
 * @package Joomla
 * @subpackage UnitTest
 * @author         Rene Serradeil <serradeil@webmechanic.biz>
 * @version $Id$
 */

// Call TestOfJLoader::main() if this source file is executed directly.
if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'TestOfJLoader::main');
	$JUNIT_ROOT = substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR.'unittest'));
	require_once($JUNIT_ROOT.'/unittest/setup.php');
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
		$self->run(UnitTestHelper::getReporter());
	}

	/** function import($filePath, $base = null, $key = null) */
	function test_import()
	{
		$r = JLoader::import('joomla.factory');
		$this->assertEqual($r, 1, '%s');
	}

	/** function import($filePath, $base = null, $key = null) */
	function test_import_base()
	{
		$r = JLoader::import('_files.loader', dirname(__FILE__));
		if ($this->assertEqual($r, 1, '%s')) {
			$this->assertTrue(defined('JLOADER_TEST_IMPORT_BASE'), '%s');
		}

		// retry
		$r = JLoader::import('_files.loader', dirname(__FILE__));
		$this->assertEqual($r, 1, '%s');
	}

	/** function import($filePath, $base = null, $key = null) */
	function test_import_key()
	{
		// Remove the following line when you implement this test.
		return $this->_reporter->setMissingTestCase();
	}

	/** function &factory($class, $options=null) */
	function test_factory()
	{
		// Remove the following line when you implement this test.
		return $this->_reporter->setMissingTestCase();
	}

}

// Call TestOfJLoader::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == "TestOfJLoader::main") {
	TestOfJLoader::main();
}
