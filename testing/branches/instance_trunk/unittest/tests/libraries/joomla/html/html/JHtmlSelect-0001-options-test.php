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
	define('JUNIT_MAIN_METHOD', 'JHtmlSelectOptionsTest::main');
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

class JHtmlSelectOptionsTest extends JHtmlSelectTest
{
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
	 * Tests the simplest case, passing an associative array
	 */
	function testOptionValues() {
		// New functionality for 1.6
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions('keyed_array');
		$expect = self::expectingOptions();
		$html = JHtmlSelect::options($data);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test defaults using an object array
	 */
	function testOptionObjects() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$version15up)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions('object');
		$expect = self::expectingOptions();
		$html = JHtmlSelect::options($data);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test using an array of objects with custom entries
	 */
	function testOptionObjectsCustom() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$version15up)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$options = array(
			'option.key' => 'custKey',
			'option.text' => 'custText'
		);
		$data = self::makeSampleOptions('option_custom');
		$expect = self::expectingOptions();
		$html = JHtmlSelect::options($data, $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test defaults using an array of arrays
	 */
	function testOptionArray() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$version15up)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions('array');
		$expect = self::expectingOptions();
		$html = JHtmlSelect::options($data);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test using an array of arrays with custom entries
	 */
	function testOptionArrayCustom() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$options = array(
			'option.key' => 'custKey',
			'option.text' => 'custText'
		);
		$data = self::makeSampleOptions('array_custom');
		$expect = self::expectingOptions();
		$html = JHtmlSelect::options($data, $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test using an array of arrays with formatting options
	 */
	function testOptionArrayFormat() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$version15up)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$options = array(
			'format.depth' => 2,
			'format.eol' => "\r\n",
			'format.indent' => '  '
		);
		$data = self::makeSampleOptions('array');
		$expect = self::expectingOptions('basic_format');
		$html = JHtmlSelect::options($data, $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test using an array of arrays with id information
	 */
	function testOptionArrayIds() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions('array_id');
		$expect = self::expectingOptions('basic_id');
		$options = array(
			'option.id' => 'id',
		);
		$html = JHtmlSelect::options($data, $options);
		JUnit_TestCaseHelper::stringDiff($html, $expect);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test using an array of arrays with custom entries and ids
	 */
	function testOptionArrayIdsCustom() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$options = array(
			'option.key' => 'custKey',
			'option.text' => 'custText',
			'option.id' => 'custId',
		);
		$data = self::makeSampleOptions('array_id_custom');
		$expect = self::expectingOptions('basic_id');
		$html = JHtmlSelect::options($data, $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test using an array of arrays with label information
	 */
	function testOptionArrayLabels() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions('array_label');
		$expect = self::expectingOptions('basic_label');
		$options = array(
			'option.label' => 'label',
		);
		$html = JHtmlSelect::options($data, $options);
		JUnit_TestCaseHelper::stringDiff($html, $expect);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test using an array of arrays with custom entries and labels
	 */
	function testOptionArrayLabelsCustom() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$options = array(
			'option.key' => 'custKey',
			'option.text' => 'custText',
			'option.label' => 'custLabel',
		);
		$data = self::makeSampleOptions('array_label_custom');
		$expect = self::expectingOptions('basic_label');
		$html = JHtmlSelect::options($data, $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test using an array of arrays with label information and preformatted
	 * HTML.
	 */
	function testOptionArrayLabelsRaw() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions('array_label_raw');
		$expect = self::expectingOptions('basic_label');
		$options = array(
			'option.label' => 'label',
			'option.label.toHtml' => false,
			'option.key.toHtml' => false,
			'option.text.toHtml' => false,
		);
		$html = JHtmlSelect::options($data, $options);
		JUnit_TestCaseHelper::stringDiff($html, $expect);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test using an array of arrays with labels and translation
	 */
	function testOptionArrayTranslate() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		JText::$lang = 'foo';
		$options = array(
			'list.translate' => true,
			'option.label' => 'label',
		);
		$data = self::makeSampleOptions('array_label');
		$expect = self::expectingOptions('basic_label_lang');
		$html = JHtmlSelect::options($data, $options);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test the disabled attribute
	 */
	function testOptionDisabled() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$version15up)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions('object');
		$data[3]->disable = true;
		$expect = self::expectingOptions('d3');
		$html = JHtmlSelect::options($data);
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test the selected attribute with a single value
	 */
	function testOptionSelectOne() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions();
		$expect = self::expectingOptions('s3');
		$html = JHtmlSelect::options($data, array('list.select' => 's<3'));
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test the selected attribute with a single value, J1.5 style
	 */
	function testOptionSelectOne15() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$version15up)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions();
		$expect = self::expectingOptions('s3');
		$html = JHtmlSelect::options($data, 'value', 'text', 's<3');
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test the selected attribute with multiple values
	 */
	function testOptionSelectMany() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions();
		$expect = self::expectingOptions('s0.s3');
		$html = JHtmlSelect::options($data, array('list.select' => array('s0', 's<3')));
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

	/**
	 * Test the selected attribute with a single value, J1.5 style
	 */
	function testOptionSelectMany15() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$version15up)) {
			$this -> markTestSkipped(self::VERSION_SKIP_MSG);
			return;
		}
		$data = self::makeSampleOptions();
		$expect = self::expectingOptions('s0.s3');
		$html = JHtmlSelect::options($data, 'value', 'text', array('s0', 's<3'));
		$this -> assertEquals($html, $expect, JUnit_TestCaseHelper::stringDiff($html, $expect));
	}

}

// Call mainline if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JHtmlSelectOptionsTest::main') {
	JHtmlSelectOptionsTest::main();
}
