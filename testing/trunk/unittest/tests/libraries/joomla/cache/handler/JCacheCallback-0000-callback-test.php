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
//Include the mock storage engine for these tests...
require_once(dirname(dirname(__FILE__)).DS.'storage'.DS.'JCacheStorageMock.php');

require_once(dirname(__FILE__).DS.'JCacheCallback.helper.php');

class JCacheCallbackTest_Callback extends PHPUnit_Extensions_OutputTestCase
{
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
			$result = $cache->get(array($instance, 'instanceCallback'), array($arg1, $arg2));
			$this->assertTrue($arg2 === $result,
				'Expected: '.$arg2.' Actual: '.$result
			);
			unset($instance);
		}
	}


}
