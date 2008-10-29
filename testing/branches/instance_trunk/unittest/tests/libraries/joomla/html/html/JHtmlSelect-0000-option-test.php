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
	define('JUNIT_MAIN_METHOD', 'JHtmlSelectOptionTest::main');
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
JLoader::injectMock(
	dirname(__FILE__) . DS . 'JText-mock-general.php',
	'joomla.text'
);

/**
 * Dummy JHtml class inline
 */
class JHtml {
	function _() {
		return '';
	}
}

/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

require_once JPATH_LIBRARIES . '/joomla/html/html/select.php';

require_once dirname(__FILE__) . DS . 'JHtmlSelect-testclass.php';

class JHtmlSelectOptionTest extends JHtmlSelectTest
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

	function testOptionDefault() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(VERSION_SKIP_MSG);
			return;
		}
		$option = JHtmlSelect::option('keyval', 'some text', array('disable' => null));
		$this -> assertTrue(is_object($option));
		$this -> assertObjectHasAttribute('value', $option);
		$this -> assertEquals('keyval', $option -> value);
		$this -> assertObjectHasAttribute('text', $option);
		$this -> assertEquals('some text', $option -> text);
		$this -> assertObjectNotHasAttribute('disable', $option);
	}

	function testOptionDefault15() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$version15up)) {
			$this -> markTestSkipped(VERSION_SKIP_MSG);
			return;
		}
		$option = JHtmlSelect::option('keyval', 'some text');
		$this -> assertTrue(is_object($option));
		$this -> assertObjectHasAttribute('value', $option);
		$this -> assertEquals('keyval', $option -> value);
		$this -> assertObjectHasAttribute('text', $option);
		$this -> assertEquals('some text', $option -> text);
		$this -> assertObjectHasAttribute('disable', $option);
		$this -> assertEquals(false, $option -> disable);
	}

	function testOptionCustom() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(VERSION_SKIP_MSG);
			return;
		}
		$option = JHtmlSelect::option(
			'keyval',
			'some text',
			array('disable' => null, 'option.key' => 'custKey', 'option.text' => 'custText')
		);
		$this -> assertTrue(is_object($option));
		$this -> assertObjectHasAttribute('custKey', $option);
		$this -> assertEquals('keyval', $option -> custKey);
		$this -> assertObjectHasAttribute('custText', $option);
		$this -> assertEquals('some text', $option -> custText);
		$this -> assertObjectNotHasAttribute('disable', $option);
	}

	function testOptionCustom15() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$version15up)) {
			$this -> markTestSkipped(VERSION_SKIP_MSG);
			return;
		}
		$option = JHtmlSelect::option('keyval', 'some text', 'custKey', 'custText');
		$this -> assertTrue(is_object($option));
		$this -> assertObjectHasAttribute('custKey', $option);
		$this -> assertEquals('keyval', $option -> custKey);
		$this -> assertObjectHasAttribute('custText', $option);
		$this -> assertEquals('some text', $option -> custText);
		$this -> assertObjectHasAttribute('disable', $option);
		$this -> assertEquals(false, $option -> disable);
	}

	function testOptionAttribute() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(VERSION_SKIP_MSG);
			return;
		}
		$option = JHtmlSelect::option(
			'keyval',
			'some text',
			array(
				'attr' => 'class="foo"',
				'option.attr' => 'custAttr',
			)
		);
		$this -> assertTrue(is_object($option));
		$this -> assertObjectHasAttribute('value', $option);
		$this -> assertEquals('keyval', $option -> value);
		$this -> assertObjectHasAttribute('text', $option);
		$this -> assertEquals('some text', $option -> text);
		$this -> assertObjectHasAttribute('custAttr', $option);
		$this -> assertEquals('class="foo"', $option -> custAttr);
	}

	function testOptionLabel() {
		if (! JUnit_Setup::isTestEnabled(JVERSION, self::$versionMask)) {
			$this -> markTestSkipped(VERSION_SKIP_MSG);
			return;
		}
		$option = JHtmlSelect::option(
			'keyval',
			'some text',
			array(
				'disable' => null,
				'label' => 'this is a label',
				'option.disable' => 'custDisable',
				'option.label' => 'custLbl',
				'option.key' => 'custKey',
				'option.text' => 'custText'
			)
		);
		$this -> assertTrue(is_object($option));
		$this -> assertObjectHasAttribute('custKey', $option);
		$this -> assertEquals('keyval', $option -> custKey);
		$this -> assertObjectHasAttribute('custText', $option);
		$this -> assertEquals('some text', $option -> custText);
		$this -> assertObjectHasAttribute('custLbl', $option);
		$this -> assertEquals('this is a label', $option -> custLbl);
		// Even though we provided a property name, there was no value
		$this -> assertObjectNotHasAttribute('custDisable', $option);
	}

}

// Call mainline if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JHtmlSelectOptionTest::main') {
	JHtmlSelectOptionTest::main();
}
