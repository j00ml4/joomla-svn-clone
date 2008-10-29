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
	define('JUNIT_MAIN_METHOD', 'JHtmlSelectGenericListTest::main');
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

class JHtmlSelectGenericListTest extends JHtmlSelectTest
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
	 * Tests the simplest case, passing an associative array with no options.
	 */
	function testGenericListDefault() {
		// Should work in 1.5 and up
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$version15up)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions('keyed_array');
		$items = self::expectingOptions('array');
		$expect = '<select id="testlist" name="testlist">' . chr(10);
		foreach ($items as $item) {
			$expect .= chr(9) . $item . chr(10);
		}
		$expect .= '</select>' . chr(10);
		$html = JHtmlSelect::genericList($data, 'testlist');
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test extra list attributes as array
	 */
	function testGenericListAttrArray() {
		// New functionality for 1.6
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions('keyed_array');
		$items = self::expectingOptions('array');
		$expect = '<select id="testlist" name="testlist" class="someclass" style="width:100%">' . chr(10);
		foreach ($items as $item) {
			$expect .= chr(9) . $item . chr(10);
		}
		$expect .= '</select>' . chr(10);
		$options = array('list.attr' => array('class' => 'someclass', 'style' => 'width:100%'));
		$html = JHtmlSelect::genericList($data, 'testlist', $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test extra list attributes as string
	 */
	function testGenericListAttrString() {
		// New functionality for 1.6
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions('keyed_array');
		$items = self::expectingOptions('array');
		$expect = '<select id="testlist" name="testlist" class="someclass" style="width:100%">' . chr(10);
		foreach ($items as $item) {
			$expect .= chr(9) . $item . chr(10);
		}
		$expect .= '</select>' . chr(10);
		$options = array('list.attr' => 'class="someclass" style="width:100%"');
		$html = JHtmlSelect::genericList($data, 'testlist', $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test custom select id attribute
	 */
	function testGenericListSetId() {
		// New functionality for 1.6
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions('keyed_array');
		$items = self::expectingOptions('array');
		$expect = '<select id="someid" name="testlist">' . chr(10);
		foreach ($items as $item) {
			$expect .= chr(9) . $item . chr(10);
		}
		$expect .= '</select>' . chr(10);
		$options = array('id' => 'someid');
		$html = JHtmlSelect::genericList($data, 'testlist', $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test supression of the select id attribute
	 */
	function testGenericListNoId() {
		// New functionality for 1.6
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions('keyed_array');
		$items = self::expectingOptions('array');
		$expect = '<select name="testlist">' . chr(10);
		foreach ($items as $item) {
			$expect .= chr(9) . $item . chr(10);
		}
		$expect .= '</select>' . chr(10);
		$options = array('id' => '');
		$html = JHtmlSelect::genericList($data, 'testlist', $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Tests formatting with a preset depth
	 */
	function testGenericListFormatDepth() {
		// Should work in 1.5 and up
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$version15up)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions('keyed_array');
		$items = self::expectingOptions('array');
		$expect = chr(9) . '<select id="testlist" name="testlist">' . chr(10);
		foreach ($items as $item) {
			$expect .= chr(9) . chr(9) . $item . chr(10);
		}
		$expect .= chr(9) . '</select>' . chr(10);
		$options = array('format.depth' => 1);
		$html = JHtmlSelect::genericList($data, 'testlist', $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Tests formatting with no indent or linebreak
	 */
	function testGenericListFormatCompact() {
		// Should work in 1.5 and up
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$version15up)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions('keyed_array');
		$items = self::expectingOptions('array');
		$expect = '<select id="testlist" name="testlist">';
		foreach ($items as $item) {
			$expect .= $item;
		}
		$expect .= '</select>';
		$options = array('format.eol' => '', 'format.indent' => '');
		$html = JHtmlSelect::genericList($data, 'testlist', $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test legacy option group code
	 */
	function testGenericListGroup() {
		// New functionality for 1.6
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = array();
		$data[] = JHtmlSelect::option('<OPTGROUP>', 'Group 1');
		$data = array_merge($data, self::makeSampleOptions('option.g1'));
		$data[] = JHtmlSelect::option('</OPTGROUP>', '');
		$data[] = JHtmlSelect::option('<OPTGROUP>', 'Group 2');
		$data = array_merge($data, self::makeSampleOptions('option.g2'));
		$data[] = JHtmlSelect::option('</OPTGROUP>', '');
		$items = self::expectingOptions('array.g1');
		$expect = '<select id="testlist" name="testlist">' . chr(10)
			. chr(9) . '<optgroup label="Group 1">' . chr(10)
		;
		foreach ($items as $item) {
			$expect .= chr(9) . chr(9) . $item . chr(10);
		}
		$expect .= chr(9) . '</optgroup>' . chr(10);
		$items = self::expectingOptions('array.g2');
		$expect .= chr(9) . '<optgroup label="Group 2">' . chr(10)
		;
		foreach ($items as $item) {
			$expect .= chr(9) . chr(9) . $item . chr(10);
		}
		$expect .= chr(9) . '</optgroup>' . chr(10);
		$expect .= '</select>' . chr(10);
		$options = array();
		$html = JHtmlSelect::genericList($data, 'testlist', $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

}

// Call mainline if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JHtmlSelectGenericListTest::main') {
	JHtmlSelectGenericListTest::main();
}
