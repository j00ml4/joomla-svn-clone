<?php
/**
 *
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id$
 * @author Alan Langford <instance1@gmail.com>
 */

// Call JDateTest::main() if this source file is executed directly.
if (! defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JHtmlSelectGroupedListTest::main');
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
class JText {
	static $lang = '';

	function _($msg) {
		return self::$lang . $msg;
	}
}

/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

jimport('joomla.filesystem.path');

require_once JPATH_LIBRARIES . '/joomla/html/html.php';

require_once JPATH_LIBRARIES . '/joomla/html/html/select.php';

require_once dirname(__FILE__) . DS . 'JHtmlSelect-testclass.php';

class JHtmlSelectGroupedListTest extends JHtmlSelectTest
{
	/**
	 * Get various common expectation strings
	 */
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
	 * Test passing list of group objects with attached selections as
	 * associative arrays.
	 */
	function testGroupedListObjectHash() {
		// New functionality for 1.6
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = array();
		$group = JHtmlSelect::option('unused', 'Group 1');
		$group->items = self::makeSampleOptions('keyed_array.g1');
		$data[] = $group;
		$group = JHtmlSelect::option('unused', 'Group 2');
		$group->items = self::makeSampleOptions('keyed_array.g2');
		$data[] = $group;
		$expect = self::expectingSelect('grouped');
		$options = array();
		$html = JHtmlSelect::groupedList($data, 'testlist', $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test passing list of group objects with attached selections as arrays.
	 */
	function testGroupedListObjectArray() {
		// New functionality for 1.6
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = array();
		$group = JHtmlSelect::option('unused', 'Group 1');
		$group->items = self::makeSampleOptions('array.g1');
		$data[] = $group;
		$group = JHtmlSelect::option('unused', 'Group 2');
		$group->items = self::makeSampleOptions('array.g2');
		$data[] = $group;
		$expect = self::expectingSelect('grouped');
		$options = array();
		$html = JHtmlSelect::groupedList($data, 'testlist', $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test passing list of group objects with attached selections as objects.
	 */
	function testGroupedListObjectObject() {
		// New functionality for 1.6
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = array();
		$group = JHtmlSelect::option('unused', 'Group 1');
		$group->items = self::makeSampleOptions('option.g1');
		$data[] = $group;
		$group = JHtmlSelect::option('unused', 'Group 2');
		$group->items = self::makeSampleOptions('option.g2');
		$data[] = $group;
		$expect = self::expectingSelect('grouped');
		$options = array();
		$html = JHtmlSelect::groupedList($data, 'testlist', $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test passing list of group arrays with attached selections as associative
	 * arrays.
	 */
	function testGroupedListArrayHash() {
		// New functionality for 1.6
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = array();
		$data[] = array(
			'text' => 'Group 1',
			'items' => self::makeSampleOptions('keyed_array.g1')
		);
		$data[] = array(
			'text' => 'Group 2',
			'items' => self::makeSampleOptions('keyed_array.g2')
		);
		$expect = self::expectingSelect('grouped');
		$options = array();
		$html = JHtmlSelect::groupedList($data, 'testlist', $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test passing a set of associative arrays.
	 */
	function testGroupedListHashHash() {
		// New functionality for 1.6
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = array();
		$data['Group 1'] = self::makeSampleOptions('keyed_array.g1');
		$data['Group 2'] = self::makeSampleOptions('keyed_array.g2');
		$expect = self::expectingSelect('grouped');
		$options = array('group.items' => null);
		$html = JHtmlSelect::groupedList($data, 'testlist', $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

}

// Call mainline if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JHtmlSelectGroupedListTest::main') {
	JHtmlSelectGroupedListTest::main();
}
