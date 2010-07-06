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

/**
 * Repair display helper.
 *
 * @package		Repair
 * @subpackage	com_repair
 * @since		1.0
 */
class RepairHelper
{
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$actions = array(
			'core.admin', 'core.manage'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, 'com_repair'));
		}

		return $result;
	}
}