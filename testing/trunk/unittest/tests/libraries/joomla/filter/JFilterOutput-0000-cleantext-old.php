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

/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

jimport( 'joomla.filter.filteroutput' );

class JFilterOutputTest_CleanText extends PHPUnit_Framework_TestCase {

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