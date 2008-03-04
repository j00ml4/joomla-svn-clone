<?php
/**
 * Joomla! v1.5 Unit Test Facility
 *
 * Template for a basic unit test
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright Copyright (C) 2005 - 2008 Open Source Matters, Inc.
 * @version $Id: $
 *
 */

if (!defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'EmailcloakTest_Mode1::main');
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
// This simple mock for JPluginHelper just returns the canned responses we select.
JLoader::injectMock(
	JUNIT_ROOT . '/tests/libraries/joomla/plugin/JPluginHelper-mock-general.php',
	'joomla.plugin.helper'
);
// This mock for JApplication is specific to this test suite;
// it simply has an empty method for registerEvent.
JLoader::injectMock(
	dirname(__FILE__) . '/JApplication-mock-general.php',
	'joomla.application.application'
);
// Our JHTML mock provides a stub for the "email.cloak" function.
// This dramatically simplifies the test results.
JLoader::injectMock(
	dirname(__FILE__) . '/JHTML-mock-general.php',
	'joomla.html.html'
);

/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';
jimport('joomla.utilities.string');
jimport('joomla.html.html');
jimport('joomla.html.parameter');

// Set mainframe to our brain-dead mock.
$mainframe = & JFactory::getApplication('site');

// Load the subject code
require_once JPATH_BASE . '/plugins/content/emailcloak.php';

/**
 * A unit test class for the eMail cloaking plugin
 */
class EmailcloakTest_Mode1 extends PHPUnit_Framework_TestCase
{
	/**
	 * Generate a test data set.
	 *
	 * @return array The test name is the array index; each element in the array
	 * is an array of two elements. The first element is an object with the
	 * subject string in the text property. The second element is the expected
	 * string after the cloaker has run.
	 */
	static function dataSet() {
		/*
		 * Our mock for JHTML::_('email.cloak', ...) simply returns the
		 * parameters it was passed, enclosed in square brackets and with all
		 * cases of "@" converted to " at " (the replacement prevents
		 * unterminated loops).
		 */
		$cases = array(
			'no addr' => array(
				'no mail addy here.',
				'no mail addy here.',
			),
			'simple lead' => array(
				'joe@example.com text.',
				'[mail=joe at example.com mailto=1 text= email=1] text.',
			),
			'simple trail' => array(
				'mail is joe@example.com',
				'mail is [mail=joe at example.com mailto=1 text= email=1]',
			),
			'simple embed' => array(
				'mail is joe@example.com text.',
				'mail is [mail=joe at example.com mailto=1 text= email=1] text.',
			),
			// Note this case is wrong (trailing period is not part of addy)
			'simple trail dot' => array(
				'mail is joe@example.com.',
				'mail is [mail=joe at example.com. mailto=1 text= email=1]',
			),
			// Note this case is wrong (trailing period is not part of addy)
			'compound trail dot' => array(
				'mail is to-joe.example@sub.example.com.',
				'mail is [mail=to-joe.example at sub.example.com. mailto=1 text= email=1]',
			),
			// The leading mailto outside HTML is not recognized
			'mailto' => array(
				'mail is mailto:joe@example.com.',
				'mail is mailto:[mail=joe at example.com. mailto=1 text= email=1]',
			),
			'simple mailto same' => array(
				'mail is <a href="mailto:joe@example.com">joe@example.com</a>.',
				'mail is [mail=joe at example.com mailto=1 text=joe at example.com email=1].',
			),
			'simple mailto different' => array(
				'mail is <a href="mailto:joe@example.com">Joe Example</a>.',
				'mail is [mail=joe at example.com mailto=1 text=Joe Example email=0].',
			),
			'simple mailto w/subject,body' => array(
				'mail is <a href="mailto:joe@example.com?Subject=test&body=good &amp; special">joe@example.com</a>.',
				'mail is [mail=joe at example.com?Subject=test&body=good & special mailto=1 text=joe at example.com email=1].',
			),
			'compound mailto same' => array(
				'mail is <a href="mailto:joe@sub.example.com">joe@sub.example.com</a>.',
				'mail is [mail=joe at sub.example.com mailto=1 text=joe at sub.example.com email=1].',
			),
			'compound mailto different' => array(
				'mail is <a href="mailto:joe@sub.example.com">Joe Example</a>.',
				'mail is [mail=joe at sub.example.com mailto=1 text=Joe Example email=0].',
			),
			'disabled compound mailto same' => array(
				'mail {emailcloak=off}is <a href="mailto:joe@sub.example.com">joe@sub.example.com</a>.',
				'mail is <a href="mailto:joe@sub.example.com">joe@sub.example.com</a>.',
			),
			'midstream disable' => array(
				'mail valid@first1.net {emailcloak=off}and unchanged@2nd.one.org.',
				'mail valid@first1.net and unchanged@2nd.one.org.',
			),
		);
		$tests = array();
		foreach ($cases as $key => $testCase) {
			$test = new stdClass;
			$test -> text = $testCase[0];
			$testCase[0] = $test;
			$tests[$key] = $testCase;
		}
		return $tests;
	}

	/**
	 * Runs the test methods of this class.
	 */
	function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	/**
	 * Initialize the mock for JPluginHelper.
	 *
	 * This creates a mock replacement for the plugin with the desired mode set.
	 */
	function setUp() {
		/*
		 * Define a skeletal mock that will return the mode.
		 */
		JPluginHelper::mockReset();
		$plugin = new stdClass;
		$plugin -> params = 'mode=1' . chr(10) . chr(10);
		JPluginHelper::mockSetUp('content', 'emailcloak', $plugin, null);
	}

	/**
	 * Execute a cloaking test case.
	 *
	 * The test framework calls this function once for each element in the array
	 * returned by the named data provider.
	 *
	 * @dataProvider dataSet
	 * @param object A seleton of an article object with the text property set.
	 * @param string The expected result for this test.
	 */
	function testCloak($article, $expect)
	{
		$params = array();
		$result = plgContentEmailCloak($article, $params);
		// TODO: uncomment this line once function always returns true.
		//$this -> assertTrue($result);
		$this -> assertEquals($expect, $article -> text);
	}

}

// Call main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'EmailcloakTest_Mode1::main') {
	EmailcloakTest_Mode1::main();
}
