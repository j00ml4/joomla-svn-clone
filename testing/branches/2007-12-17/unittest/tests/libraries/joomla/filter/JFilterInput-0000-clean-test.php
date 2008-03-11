<?php
/**
 * JFilterInput clean tests
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id: $
 * @author Jui-Yu Tsai <raytsai@gmail.com>
 */

// Call JFilterInputTest_Clean::main() if this source file is executed directly.
if (! defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JFilterInputTest_Clean::main');
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
 
/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

jimport( 'joomla.filter.filterinput' );

class JFilterInputTest_Clean extends PHPUnit_Framework_TestCase {
	/**
	 * Runs the test methods of this class.
	 */
	static function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	function setUp() {
		
	}
	
	static function dataSet() {
	    $input = '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~€‚ƒ„…†‡ˆ‰Š‹ŒŽ‘’“”•–—˜™š›œžŸ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ';
	
	    $cases = array(
			'int_01' => array(
				'int',
				$input,
				123456789
			),
			'integer' => array(
				'int',
				$input,
				123456789
			),
			'int_02' => array(
				'int',
				'abc123456789abc123456789',
				123456789
			),
			'int_03' => array(
				'int',
				'123456789abc123456789abc',
				123456789
			),
			'int_04' => array(
				'int',
				'empty',
				0
			),
			'int_05' => array(
				'int',
				'ab-123ab',
				-123
			),
			'int_06' => array(
				'int',
				'-ab123ab',
				123
			),
			'int_07' => array(
				'int',
				'-ab123.456ab',
				123
			),
			'int_08' => array(
				'int',
				'456',
				456
			),
			'int_09' => array(
				'int',
				'-789',
				-789
			),
			'int_10' => array(
				'int',
				-789,
				-789
			),
			'float_01' => array(
				'float',
				$input,
				123456789
			),
			'double' => array(
				'double',
				$input,
				123456789
			),
			'float_02' => array(
				'float',
				20.20,
				20.2
			),
			'float_03' => array(
				'float',
				'-38.123',
				-38.123
			),
			'float_04' => array(
				'float',
				'abc-12.456',
				-12.456
			),
			'float_05' => array(
				'float',
				'-abc12.456',
				12.456
			),
			'float_06' => array(
				'float',
				'abc-12.456abc',
				-12.456
			),
			'float_07' => array(
				'float',
				'abc-12 . 456',
				-12
			),
			'float_08' => array(
				'float',
				'abc-12. 456',
				-12
			),
			'bool_0' => array(
				'bool',
				$input,
				true
			),
			'boolean' => array(
				'boolean',
				$input,
				true
			),
			'bool_1' => array(
				'bool',
				true,
				true
			),
			'bool_2' => array(
				'bool',
				false,
				false
			),
			'bool_3' => array(
				'bool',
				'',
				false
			),
			'bool_4' => array(
				'bool',
				0,
				false
			),
			'bool_5' => array(
				'bool',
				1,
				true
			),
			'bool_6' => array(
				'bool',
				NULL,
				false
			),
			'bool_7' => array(
				'bool',
				'false',
				true
			),
			'word_01' => array(
				'word',
				$input,
				'ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz'
			),
			'word_02' => array(
				'word',
				NULL,
				''
			),
			'word_03' => array(
				'word',
				123456789,
				''
			),
			'word_04' => array(
				'word',
				'word123456789',
				'word'
			),
			'word_05' => array(
				'word',
				'123456789word',
				'word'
			),
			'word_06' => array(
				'word',
				'w123o4567r89d',
				'word'
			),
			'alnum_01' => array(
				'alnum',
				$input,
				'0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'
			),
			'alnum_02' => array(
				'alnum',
				NULL,
				''
			),
			'alnum_03' => array(
				'alnum',
				'~!@#$%^&*()_+abc',
				'abc'
			),
			'cmd' => array(
				'cmd',
				$input,
				'-.0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz'
			),
			'base64' => array(
				'base64',
				$input,
				'+/0123456789=ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'
			),
		);
		$tests = $cases;
		
		return $tests;
	}

    /**
	 * Execute a clean test case.
	 *
	 * The test framework calls this function once for each element in the array
	 * returned by the named data provider.
	 *
	 * @dataProvider dataSet
	 * @param string The type of input
	 * @param string The input 
	 * @param string The expected result for this test.
	 */
	function testClean($type, $data, $expect) {
		$this->assertEquals($expect, JFilterInput::clean($data, $type));
	}

}

// Call JFilterInputTest::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JFilterInputTest_Clean::main') {
	JFilterInputTest_Clean::main();
}