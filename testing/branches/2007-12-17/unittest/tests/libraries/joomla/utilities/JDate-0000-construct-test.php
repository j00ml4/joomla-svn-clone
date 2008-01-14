<?php
/**
 * JDate constructor tests
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id: $
 * @author Alan Langford <instance1@gmail.com>
 */

// Call JDateTest::main() if this source file is executed directly.
if (! defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JDateTest::main');
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

jimport('joomla.utilities.date');

class JDateTest_Construct extends PHPUnit_Framework_TestCase
{
	/**
	 * Runs the test methods of this class.
	 */
	static function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	function testConstruct() {
		/*
		 * Allow one tick in difference just in case the second rolls over mid-
		 * test.
		 */
		$jd = new JDate();
		$now = gmdate('U');
		$delta = $now - $jd -> toUnix();
		$this -> assertTrue(abs($delta) < 1,
			'gmdate= ' . $now . ' toUnix=' . $jd -> toUnix() . ' Delta is ' . $delta
		);
		$jd = new JDate('now', 1);
		$now = gmdate('U');
		$delta = $now - $jd -> toUnix();
		$this -> assertTrue(abs($delta) < 1,
			'gmdate= ' . $now . ' toUnix=' . $jd -> toUnix() . ' Delta is ' . $delta
		);
	}

}

// Call JDateTest::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JDateTest::main') {
	JDateTest::main();
}

