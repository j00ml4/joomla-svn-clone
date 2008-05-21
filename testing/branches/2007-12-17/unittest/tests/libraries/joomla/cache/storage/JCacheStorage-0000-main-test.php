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
	define('JUNIT_MAIN_METHOD', 'JCacheStorageTest_Main::main');
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

require_once(dirname(dirname(__FILE__)) . DS . 'handler' .  DS . 'JCacheRaw.php');

class JCacheStorageTest_Main extends PHPUnit_Framework_TestCase
{
	/**
	 * Runs the test methods of this class.
	 */
	static function main() {
		$suite  = new PHPUnit_Framework_TestSuite(__CLASS__);
		$result = PHPUnit_TextUI_TestRunner::run($suite);
	}

	public function provider() {
		static $ret = array();
		if(empty($ret)) {
			$names = JCache::getStores();
			foreach ($names AS $name) {
				$ret[] = array($name);
			}
		}
		return $ret;
	}

	/**
	 * @dataProvider provider
	 */
	function testCacheGet($store) {
		$id = 'randomTestID';
		$group = 'testing';
		$data = 'testData';
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$this->assertTrue($cache->store($data, $id, $group), 'Initial Store Failed');
		unset($cache);
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$new = $cache->get($id, $group);
		$this->assertTrue(($new === $data), 'Expected: '.$data.' Actual: '.$new);
		unset($cache);
	}

	/**
	 * @dataProvider provider
	 */
	function testCacheTimeout($store) {
		$id = 'randomTestID';
		$group = 'testing';
		$data = 'testData';
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$cache->setLifeTime(2);
		$this->assertTrue($cache->store($data, $id, $group), 'Initial Store Failed');
		unset($cache);
		sleep(3);
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$cache->setLifeTime(2);
		$new = $cache->get($id, $group);
		$this->assertTrue(($new === false), 'Expected: false Actual: '.((string) $new));
		unset($cache);
	}

	/**
	 * @dataProvider provider
	 */
	function testCacheRemove($store) {
		$id = 'randomTestID';
		$group = 'testing';
		$data = 'testData';
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$this->assertTrue($cache->store($data, $id, $group), 'Initial Store Failed');
		unset($cache);
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$this->assertTrue($cache->remove($id, $group), 'Removal Failed');
		unset($cache);
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$new = $cache->get($id, $group);
		$this->assertTrue(($new === false), 'Expected: false Actual: '.((string) $new));
		unset($cache);
	}

	/**
	 * @dataProvider provider
	 */
	function testCacheClearGroup($store) {
		$id = 'randomTestID';
		$group = 'testing';
		$data = 'testData';
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$this->assertTrue($cache->store($data, $id, $group), 'Initial Store Failed');
		unset($cache);
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$this->assertTrue($cache->clean($group), 'Clean Failed');
		unset($cache);
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$new = $cache->get($id, $group);
		$this->assertTrue(($new === false), 'Expected: false Actual: '.((string) $new));
		unset($cache);
	}

	/**
	 * @dataProvider provider
	 */
	function testCacheClearNotGroup($store) {
		$id = 'randomTestID';
		$group = 'testing';
		$data = 'testData';
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$this->assertTrue($cache->store($data, $id, $group), 'Initial Store Failed');
		unset($cache);
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$this->assertTrue($cache->clean($group, 'notgroup'), 'Clean Failed');
		unset($cache);
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$new = $cache->get($id, $group);
		$this->assertTrue(($new === $data), 'Expected: '.$data.' Actual: '.((string) $new));
		unset($cache);
	}

	/**
	 * @dataProvider provider
	 */
	function testCacheClearNoGroup($store) {
		$id = 'randomTestID';
		$group = 'testing';
		$data = 'testData';
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$this->assertTrue($cache->store($data, $id, $group), 'Initial Store Failed');
		unset($cache);
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$this->assertTrue($cache->clean(), 'Clean Failed');
		unset($cache);
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$new = $cache->get($id, $group);
		$this->assertTrue(($new === false), 'Expected: false Actual: '.((string) $new));
		unset($cache);
	}

}

// Call JCacheTest_Construct::main() if this source file is executed directly.
if (JUNIT_MAIN_METHOD == 'JCacheStorageTest_Main::main') {
	JCacheStorageTest_Main::main();
}

