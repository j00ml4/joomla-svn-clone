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

jimport('joomla.utilities.date');

require_once 'JDate-helper-dataset.php';

class JDateTest_Construct extends PHPUnit_Framework_TestCase
{
	function setUp() {
	}

	function testConstruct() {
		if (!JUnit_Setup::isTestEnabled(JVERSION, array('jver_min' => '1.6.0'))) {
			$this->markTestSkipped('These tests are designed for J1.6+');
			return;
		}
		/*
		 * Allow one tick in difference just in case the second rolls over mid-
		 * test.
		 */
		$jd = new JDate();
		$now = gmdate('U');
		$delta = $now - $jd->toUnix();
		$this->assertTrue(abs($delta) < 1,
			'gmdate= ' . $now . ' toUnix=' . $jd->toUnix() . ' Delta is ' . $delta
		);
		$jd = new JDate('now', 1);
		$now = gmdate('U');
		$delta = $now - $jd->toUnix();
		$this->assertTrue(abs($delta) < 1,
			'gmdate= ' . $now . ' toUnix=' . $jd->toUnix() . ' Delta is ' . $delta
		);
	}

}
