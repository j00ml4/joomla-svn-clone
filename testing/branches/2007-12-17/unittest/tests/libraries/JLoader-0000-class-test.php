<?php
/**
 * Description
 *
 * @package Joomla
 * @subpackage UnitTest
 * @author         Rene Serradeil <serradeil@webmechanic.biz>
 * @version $Id$
 */

// Call JLoaderTest::main() if this source file is executed directly.
if (! defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JLoaderTest_Class::main');
	$JUnit_home = DIRECTORY_SEPARATOR . 'unittest' . DIRECTORY_SEPARATOR;
	if (($JUnit_posn = strpos(__FILE__, $JUnit_home)) === false) {
		die('Unable to find ' . $JUnit_home . ' in path.');
	}
	$JUnit_posn += strlen($JUnit_home) - 1;
	$JUnit_root = substr(__FILE__, 0, $JUnit_posn);
	$JUnit_start = substr(
		__FILE__,
		$JUnit_posn + 1,
		strlen(__FILE__) - strlen(basename(__FILE__)) - $JUnit_posn - 2
	);
	require_once $JUnit_root . DIRECTORY_SEPARATOR . 'setup.php';
}

/*
 * Now load the Joomla environment
 */
if (! defined('_JEXEC')) {
	define('_JEXEC', 1);
}
require_once JPATH_BASE . '/includes/defines.php';
/*
 * Mock classes
 */
// Include mocks here
/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

class JLoaderTest_Class extends PHPUnit_Framework_TestCase
{
	/**
	 * Runs the test methods of this class.
	 */
	static function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	/** function import($filePath, $base = null, $key = null) */
	function test_import()
	{
		$r = JLoader::import('joomla.factory');
		$this -> assertTrue($r);
	}

	/** function import($filePath, $base = test dir, $key = null) */
	function test_import_base()
	{
		$testLib = 'joomla._testdata.loader-data';
		$this -> assertFalse(defined('JUNIT_DATA_JLOADER'), 'Test set up failure.');
		$r = JLoader::import($testLib, dirname(__FILE__));
		if ($this -> assertTrue($r)) {
			$this -> assertTrue(defined('JUNIT_DATA_JLOADER'));
		}

		// retry
		$r = JLoader::import($testLib, dirname(__FILE__));
		$this->assertTrue($r);
	}

	/** function import($filePath, $base = null, $key = null) */
	function test_import_key()
	{
		// Remove the following line when you implement this test.
		return $this -> markTestSkipped();
	}

	/** function &factory($class, $options=null) */
	function test_factory()
	{
		// Remove the following line when you implement this test.
		return $this -> markTestSkipped();
	}

}

// Call JLoaderTest::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JLoaderTest::main') {
	JLoaderTest::main();
}
