<?php
/**
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

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

jimport('joomla.utilities.utility');
jimport('joomla.utilities.date');

require_once 'JDate-helper-dataset.php';

class JDateTest_SetDate extends PHPUnit_Framework_TestCase
{
	static public function linearizeDataSet() {
		$cases = array();
		foreach (JDateTest_DataSet::$tests as $dataSet) {
			/*
			 * Check versions
			 */
            if (!JUnit_Setup::isTestEnabled(JVERSION, $dataSet)) {
				continue;
			}
			/*
			 * Make an entry to each type in the results.
			 */
			if (is_null($dataSet['utc'])) {
				$cases[] = array($dataSet, false, 'utc');
				continue;
			}
			foreach ($dataSet['utc'] as $type => $expect) {
				$cases[] = array($dataSet, false, $type);
			}
			if (isset($dataSet['local'])) {
				foreach ($dataSet['local'] as $type => $expect) {
					$cases[] = array($dataSet, true, $type);
				}
			}
		}
		return $cases;
	}

	function setUp() {
	}

	/**
	 * @dataProvider linearizeDataSet
	 */
	function testSetDate($dataSet, $local, $type) {
		$jd = new JDate(
			$dataSet['src'],
			isset($dataSet['srcOffset']) ? $dataSet['srcOffset'] : 0
		);
		if (is_null($dataSet['utc'])) {
			/*
			 * If a null result is expected, just verify that the Unix timestamp
			 * is null. Verifying that null is returned by the other formats
			 * should be another test that runs once.
			 */
			$this->assertTrue(
				is_null($jd->toUnix()),
				JDateTest_DataSet::message($jd, 'utc', 'ts', $dataSet, $jd->toUnix())
			);
			return;
		}
		if ($local) {
			$subset = 'local';
			$offset = $dataSet['localOffset'];
		} else {
			$subset = 'utc';
			$offset = 0;
		}
		$expect = $dataSet[$subset][$type];
		switch ($type) {
			case 'ts': {
				$actual = $jd->toUnix($offset);
			}
			break;

			case 'Format': {
				$actual = $jd->toFormat('', $offset);
			}
			break;

			case 'ISO8601': {
				$actual = $jd->toISO8601($offset);
			}
			break;

			case 'MySql': {
				$actual = $jd->toMySql($offset);
			}
			break;

			case 'RFC822': {
				$actual = $jd->toRFC822($offset);
			}
			break;
		}
		$this->assertEquals(
			$expect,
			$actual,
			JDateTest_DataSet::message($jd, $subset, $type, $dataSet, $actual)
		);
	}


}
