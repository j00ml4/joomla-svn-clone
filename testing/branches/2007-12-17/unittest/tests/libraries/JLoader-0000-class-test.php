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
	define('JUNIT_MAIN_METHOD', 'JLoaderTest::main');
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

class JLoaderTest extends PHPUnit_Framework_TestCase
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
		$this->assertEquals($r, true, '%s');
	}

	/** function import($filePath, $base = null, $key = null) */
	function test_import_base()
	{
		$r = JLoader::import('_files.loader', dirname(__FILE__));
		if ($this->assertEquals($r, 1, '%s')) {
			$this->assertTrue(defined('JLOADER_TEST_IMPORT_BASE'), '%s');
		}

		// retry
		$r = JLoader::import('_files.loader', dirname(__FILE__));
		$this->assertEquals($r, 1, '%s');
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

// Call JLoaderTest::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JLoaderTest::main') {
	JLoaderTest::main();
}
