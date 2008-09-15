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


class JCacheTest_Construct extends PHPUnit_Framework_TestCase
{
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
		$this->assertTrue(($cache instanceof $class),
			'Expecting= '.$class.' Returned= '.get_class($cache)
		);
		$cache2 =& JCache::getInstance($type);
		$this->assertTrue(($cache !== $cache2),
			'Type: '.$type.' Recieved the same instance twice'
		);
	}

}