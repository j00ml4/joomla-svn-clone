<?php
/**
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

/**
 * Minimal mock of JApplication for testing the mail cloaking plugin.
 */
class JApplication {
	function &getInstance() {
		$t = new JApplication();
		return $t;
	}

	function registerEvent() {
	}

}
