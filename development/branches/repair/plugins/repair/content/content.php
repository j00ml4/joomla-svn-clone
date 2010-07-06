<?php
/**
 * @version		$Id$
 * @package		Repair
 * @subpackage	com_repair
 * @copyright	Copyright 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Repair Components Plugin.
 *
 * @package		Repair
 * @subpackage	plg_repair_content
 * @since		1.0
 */
class plgRepairContent extends JPlugin
{
	/**
	 * Method to run tests on the data source(s).
	 *
	 * @return	boolean	True if successful, false if not and a plugin error is set.
	 * @since	1.0
	 */
	function onRepairRunTests()
	{
		// Adjust error condition as required.
		if (false) {
		 	$this->_subject->setError(JText::_('PLG_REPAIR_CONTENT_ERROR'));
	 		return false;
	 	}

		return true;
	}
}