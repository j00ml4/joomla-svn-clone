<?php
/**
 * Joomla Unit tests
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id: $
 * @author Alan Langford <instance1@gmail.com>
 */

// Call JArrayHelperToStringTest::main() if this source file is executed directly.
if (! defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JArrayHelperToStringTest_Construct::main');
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

jimport('joomla.utilities.array');


class JArrayHelperToStringTest_Construct extends PHPUnit_Framework_TestCase
{
	/**
	 * Skipped based on version message.
	 */
	const VERSION_SKIP_MSG = 'Test disabled for this version of Joomla!';

	/**
	 * A set of test cases
	 */
	public $testCases;

	/**
	 * Mask for tests that should work in 1.5 and above
	 *
	 * @var array
	 */
	public static $version15up = array('jver_min' => '1.5');

	/**
	 * Version mask. This should track the current version.
	 *
	 * @var array
	 */
	public static $version16up = array('jver_min' => '1.6');

	/**
	 * Return a set of test cases.
	 */
	static function dataSet() {
		$simple = array(
			'key1' => 'value1',
			'html' => 'this<that',
			'html2' => '±',
			'quote1' => 'that\'s it',
			'quote2' => '"trouble"',
			'linefeed' => "line1\nline2"
		);
		$nested = array(
			'pre' => 'first',
			'nest' => array(
				'inner1' => 'one',
				'inner2' => 'two',
			),
			'post' => 'last'
		);
		/*
		 * All test cases, array of (version mask, data set, options array (or
		 * false for 1.5 mode), result.
		 */
		$fullSet = array(
			'simple_1.5' => array(
				self::$version15up,
				$simple,
				false,
				'key1="value1" html="this&lt;that" html2="±"'
					. ' quote1="that\'s it"'
					. ' quote2="&quot;trouble&quot;" linefeed="line1' . chr(10) . 'line2"'
			),
			'simple_innerGlue' => array(
				self::$version16up,
				$simple,
				array('innerGlue' => '=>'),
				'key1=>"value1" html=>"this&lt;that" html2=>"±"'
					. ' quote1=>"that\'s it"'
					. ' quote2=>"&quot;trouble&quot;" linefeed=>"line1' . chr(10) . 'line2"'
			),
            'simple_outerGlue' => array(
                self::$version16up,
                $simple,
                array('outerGlue' => ', '),
                'key1="value1", html="this&lt;that", html2="±",'
                    . ' quote1="that\'s it",'
                    . ' quote2="&quot;trouble&quot;", linefeed="line1' . chr(10) . 'line2"'
            ),
			'simple_quote1' => array(
				self::$version16up,
				$simple,
				array('quoteChar' => '\''),
				"key1='value1' html='this&lt;that' html2='±'"
					. " quote1='that&#039;s it'"
					. " quote2='&quot;trouble&quot;' linefeed='line1\nline2'"
			),
			'simple_trans_none' => array(
				self::$version16up,
				$simple,
				array('transform' => 'none'),
				'key1="value1" html="this<that" html2="±"'
					. ' quote1="that\'s it"'
					. ' quote2=""trouble"" linefeed="line1' . chr(10) . 'line2"'
			),
			'simple_trans_slashes' => array(
				self::$version16up,
				$simple,
				array('transform' => 'slashes'),
				'key1="value1" html="this<that" html2="±"'
					. ' quote1="that\'s it"'
					. ' quote2="\"trouble\"" linefeed="line1\\nline2"'
			),
			'simple_trans_entities' => array(
				self::$version16up,
				$simple,
				array('transform' => 'entities'),
				'key1="value1" html="this&lt;that" html2="&plusmn;"'
					. ' quote1="that\'s it"'
					. ' quote2="&quot;trouble&quot;" linefeed="line1' . chr(10) . 'line2"'
			),
			'simple_trans_callback' => array(
				self::$version16up,
				$simple,
				array(
					'transform' => 'callback',
					'transformFunction' => 'strtoupper',
				),
				'key1="VALUE1" html="THIS<THAT" html2="±"'
					. ' quote1="THAT\'S IT"'
					. ' quote2=""TROUBLE"" linefeed="LINE1' . chr(10) . 'LINE2"'
			),
			'nested_1.5' => array(
				self::$version15up,
				$nested,
				false,
				'pre="first" inner1="one" inner2="two" post="last"'
			),
            'nested_keepOuterKey' => array(
                self::$version15up,
                $nested,
                array('keepOuterKey' => true),
                'pre="first" nest inner1="one" inner2="two" post="last"'
            ),
            'nested_nestMode' => array(
                self::$version15up,
                $nested,
                array(
                    'nestMode' => true, 'nestOpen' => '{', 'nestClose' => '}'
                ),
                'pre="first" nest={inner1="one" inner2="two"} post="last"'
            ),
		);
		$selected = array();
		foreach ($fullSet as $id => $data) {
			if (!JUnit_Setup::isTestEnabled(JVERSION, $data[0])) {
				continue;
			}
			unset($data[0]);
			$data[] = $id;
			$selected[] = $data;
		}
		return $selected;
	}

	/**
	 * Runs the test methods of this class.
	 */
	static function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	function setUp() {
	}

	/**
	 *
	 * @dataProvider dataSet
	 */
	function testToString($data, $options, $expect, $testId) {
		if ($options === false) {
			$actual = JArrayHelper::toString($data);
		} else {
			$actual = JArrayHelper::toString($data, $options);
		}
		$this -> assertEquals(
			$actual,
			$expect,
			'Test ID: ' . $testId . chr(10)
				. JUnit_TestCaseHelper::stringDiff($actual, $expect)
		);
	}

}

// Call JArrayHelperToStringTest::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JArrayHelperToStringTest_Construct::main') {
	JArrayHelperToStringTest_Construct::main();
}

