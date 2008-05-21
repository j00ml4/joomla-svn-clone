<?php
/**
 * JDate constructor tests
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id: $
 * @author Anthony Ferrara 
 */

// Call JDateTest::main() if this source file is executed directly.
if (! defined('JUNIT_MAIN_METHOD')) {
	define('JUNIT_MAIN_METHOD', 'JCacheTest_Construct::main');
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

jimport('joomla.cache.cache');


class JCacheTest_Construct extends PHPUnit_Framework_TestCase
{
	/**
	 * Runs the test methods of this class.
	 */
	static function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	public static function provider() {
		return array(
				array('callback'), 
				array('output'), 
				array('page'), 
				array('view')
				);
	}

	/**
	 * @dataProvider provider
	 */
	function testConstruct($type) {
		$class = 'JCache'.ucfirst($type);
		$cache =& JCache::getInstance($type);
		$this -> assertTrue(($cache instanceof $class), 
			'Expecting= '.$class.' Returned= '.get_class($cache)
		); 
		$cache2 =& JCache::getInstance($type);
		$this -> assertTrue(($cache !== $cache2),
			'Type: '.$type.' Recieved the same instance twice'
		);
	}

}

// Call JCacheTest_Construct::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JCacheTest_Construct::main') {
	JCacheTest_Construct::main();
}

