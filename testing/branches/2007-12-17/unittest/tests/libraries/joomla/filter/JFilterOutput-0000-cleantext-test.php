<?php
/**
 * JFilterOutput cleanText tests
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id: $
 * @author Jui-Yu Tsai <raytsai@gmail.com>
 */

// Call JFilterOutputTest_CleanText::main() if this source file is executed directly.
if (! defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JFilterOutputTest_CleanText::main');
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
 
/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

jimport( 'joomla.filter.filteroutput' );

class JFilterOutputTest_CleanText extends PHPUnit_Framework_TestCase {
	/**
	 * Runs the test methods of this class.
	 */
	static function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	function setUp() {
		
	}
	
	static function dataSet() {
	    $cases = array(
			'case_1' => array(
				'',
				''
			),
			'script_0' => array(
				'<script>alert(\'hi!\');</script>',
				''
			),
			
		);
		$tests = $cases;
		
		return $tests;
	}

    /**
	 * Execute a cleanText test case.
	 *
	 * The test framework calls this function once for each element in the array
	 * returned by the named data provider.
	 *
	 * @dataProvider dataSet
	 * @param string The original output 
	 * @param string The expected result for this test.
	 */
	function testCleanText($data, $expect) {
		$this->assertEquals($expect, JFilterOutput::cleanText($data));
	}

}

// Call JFilterOutputTest::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JFilterOutputTest_CleanText::main') {
	JFilterOutputTest_CleanText::main();
}