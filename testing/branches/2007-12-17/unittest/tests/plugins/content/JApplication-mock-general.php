<?php
/**
 * Joomla! v1.5 Unit Test Facility
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright Copyright (C) 2005 - 2008 Open Source Matters, Inc.
 * @version $Id: $
 *
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
?>
