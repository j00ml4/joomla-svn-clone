<?php
/**
 * JFilterOutput cleanText tests
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id$
 * @author Jui-Yu Tsai <raytsai@gmail.com>
 */

jimport( 'joomla.filter.filteroutput' );

class JFilterOutputTest_CleanText extends PHPUnit_Framework_TestCase {
	
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

