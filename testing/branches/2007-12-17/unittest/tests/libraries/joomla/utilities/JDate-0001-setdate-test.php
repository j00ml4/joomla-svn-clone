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

jimport('joomla.utilities.utility');
jimport('joomla.utilities.date');

require_once 'JDate-helper-dataset.php';

class JDateTest_SetDate implements PHPUnit_Framework_Test
{
	public function count() {
		$tests = 0;
		foreach (JDateTest_DataSet::$tests as $dataSet) {
			$tests += is_null($dataSet['utc']) ? 1 : count($dataSet['utc']);
		}
		return 1;
	}

	/**
	 * Runs this test.
	 */
	static function main() {
		$suite = new PHPUnit_Framework_TestSuite('JDate SetDate');
		$kludge = __CLASS__;
		$test  = new $kludge;
		$suite -> addTest($test);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	public function run(PHPUnit_Framework_TestResult $result = null) {
		if ($result === null) {
			$result = new PHPUnit_Framework_TestResult;
		}
		foreach (JDateTest_DataSet::$tests as $dataSet) {
			if (is_null($dataSet['utc'])) {
				/*
				 * If a null result is expected, just verify that the Unix timestamp
				 * is null. Verifying that null is returned by the other formats
				 * should be another test that runs once.
				 */
				$result -> startTest($this);
				PHPUnit_Util_Timer::start();
				try {
					$jd = new JDate(
						$dataSet['src'],
						isset($dataSet['srcOffset']) ? $dataSet['srcOffset'] : 0
					);
					PHPUnit_Framework_Assert::assertTrue(
						is_null($jd -> toUnix()),
						JDateTest_DataSet::message($jd, 'utc', 'ts', $dataSet, $jd -> toUnix())
					);
				}
				catch (PHPUnit_Framework_AssertionFailedError $e) {
					$result -> addFailure($this, $e, PHPUnit_Util_Timer::stop());
				}
				catch (Exception $e) {
					$result -> addError($this, $e, PHPUnit_Util_Timer::stop());
				}
				$result -> endTest($this, PHPUnit_Util_Timer::stop());
			} else {
				/*
				 * Check each type in the results.
				 */
				foreach ($dataSet['utc'] as $type => $expect) {
					$result = $this -> subTest($result, $dataSet, false, $type);
				}
				if (isset($dataSet['local'])) {
					foreach ($dataSet['local'] as $type => $expect) {
						$result = $this -> subTest($result, $dataSet, true, $type);
					}
				}
			}
		}
		return $result;
	}

	function subTest($result, $dataSet, $local, $type) {
		if ($local) {
			$subset = 'local';
			$offset = $dataSet['localOffset'];
		} else {
			$subset = 'utc';
			$offset = 0;
		}
		$result -> startTest($this);
		PHPUnit_Util_Timer::start();
		try {
			$expect = $dataSet[$subset][$type];
			$jd = new JDate(
				$dataSet['src'],
				isset($dataSet['srcOffset']) ? $dataSet['srcOffset'] : 0
			);
			switch ($type) {
				case 'ts': {
					$actual = $jd -> toUnix($offset);
				}
				break;

				case 'Format': {
					$actual = $jd -> toFormat('', $offset);
				}
				break;

				case 'ISO8601': {
					$actual = $jd -> toISO8601($offset);
				}
				break;

				case 'MySql': {
					$actual = $jd -> toMySql($offset);
				}
				break;

				case 'RFC822': {
					$actual = $jd -> toRFC822($offset);
				}
				break;
			}
			PHPUnit_Framework_Assert::assertEquals(
				$expect,
				$actual,
				JDateTest_DataSet::message($jd, $subset, $type, $dataSet, $actual)
			);
		}
		catch (PHPUnit_Framework_AssertionFailedError $e) {
			$result -> addFailure($this, $e, PHPUnit_Util_Timer::stop());
		}
		catch (Exception $e) {
			$result -> addError($this, $e, PHPUnit_Util_Timer::stop());
		}
		$result -> endTest($this, PHPUnit_Util_Timer::stop());
		return $result;
	}

}

// Call JDateTest::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JDateTest::main') {
	JDateTest_SetDate::main();
}

