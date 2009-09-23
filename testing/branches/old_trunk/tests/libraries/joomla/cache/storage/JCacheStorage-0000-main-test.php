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
// Include mocks here
/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';

jimport('joomla.cache.cache');

require_once(dirname(dirname(__FILE__)) . DS . 'handler' .  DS . 'JCacheRaw.php');

class JCacheStorageTest_Main extends PHPUnit_Framework_TestCase
{
	public static function provider() {
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
	function testCacheHit($store) {
		$id = 'randomTestID';
		$group = 'testing';
		$data = 'testData';
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$this->assertTrue($cache->store($data, $id, $group), 'Initial Store Failed');
		unset($cache);
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$new = $cache->get($id, $group);
		$this->assertSame($new, $data, 'Expected: '.$data.' Actual: '.$new);
		unset($cache);
	}

	/**
	 * @dataProvider provider
	 */
	function testCacheMiss($store) {
		$id = 'randomTestID2423423';
		$group = 'testing';
		$data = 'testData';
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$new = $cache->get($id, $group);
		$this->assertFalse($new, 'Expected: false Actual: '.$new);
		unset($cache);
	}

	/**
	 * @dataProvider provider
	 */
	function testCacheTimeout($store) {
		if($store == 'xcache') {
			$this->markTestSkipped('Due to an xcache "bug/feature", this test will not function as expected, skipped');
		}
		$id = 'randomTestID';
		$group = 'testing';
		$data = 'testData';
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$cache->setLifeTime(2);
		$this->assertTrue($cache->store($data, $id, $group), 'Initial Store Failed');
		unset($cache);
		sleep(5);
		$cache =& JCache::getInstance('raw', array('storage'=>$store));
		$cache->setLifeTime(2);
		$new = $cache->get($id, $group);
		$this->assertFalse($new, 'Expected: false Actual: '.((string) $new));
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
		$this->assertFalse($new, 'Expected: false Actual: '.((string) $new));
		unset($cache);
	}

	/**
	 * @dataProvider provider
	 */
	function testCacheClearGroup($store) {
		if($store == 'xcache' || $store == 'eaccelerator' || $store == 'apc' || $store == 'memcache') {
			$this->markTestSkipped('XCache does not support clearing of the cache');
		}
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
		$this->assertFalse($new, 'Expected: false Actual: '.((string) $new));
		unset($cache);
	}

	/**
	 * @dataProvider provider
	 */
	function testCacheClearNotGroup($store) {
		if($store == 'xcache') {
			$this->markTestSkipped('XCache does not support clearing of the cache');
		}

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
		$this->assertSame($new, $data, 'Expected: '.$data.' Actual: '.((string) $new));
		unset($cache);
	}

}
