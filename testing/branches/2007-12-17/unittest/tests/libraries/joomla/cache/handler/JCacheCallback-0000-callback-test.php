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
//Include the mock storage engine for these tests...
require_once(dirname(dirname(__FILE__)).DS.'storage'.DS.'JCacheStorageMock.php');


class JCacheCallbackTest_Callback extends PHPUnit_Framework_TestCase
{
	/**
	 * Runs the test methods of this class.
	 */
	static function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	public function testCallbackFunction() {
		$cache =& JCache::getInstance('callback', array('storage'=>'mock'));
		$arg1 = 'e1';
		$arg2 = 'e2';
		$callback = 'testCallbackHandlerFunc';
		$this->expectOutputString('e1e1e1e1e1');
		for($i = 0; $i < 5; $i++) {
			$result = $cache->get($callback, array($arg1, $arg2));
			$this->assertTrue($arg2 === $result, 
				'Expected: '.$arg2.' Actual: '.$result
			);
		}
	}

	public function testCallbackStatic() {
		$cache =& JCache::getInstance('callback', array('storage'=>'mock'));
		$arg1 = 'e1';
		$arg2 = 'e2';
		$callback = array('testCallbackHandler', 'staticCallback');
		$this->expectOutputString('e1e1e1e1e1');
		for($i = 0; $i < 5; $i++) {
			$result = $cache->get($callback, array($arg1, $arg2));
			$this->assertTrue($arg2 === $result, 
				'Expected: '.$arg2.' Actual: '.$result
			);
		}
	}

	public function testCallbackInstance() {
		$cache =& JCache::getInstance('callback', array('storage'=>'mock'));
		$arg1 = 'e1';
		$arg2 = 'e2';
		$this->expectOutputString('e1e1e1e1e1');
		for($i = 0; $i < 5; $i++) {
			$instance = new testCallbackHandler();
			$callback = array(&$instance, 'instanceCallback');

			$result = $cache->get($callback, array($arg1, $arg2));
			$this->assertTrue($arg2 === $result, 
				'Expected: '.$arg2.' Actual: '.$result
			);
			unset($instance);
		}
	}


}

// Call JCacheTest_Construct::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JCacheTest_Construct::main') {
	JCacheTest_Construct::main();
}

