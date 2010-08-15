<?php
/**
 * @version		$Id: media.php 15757 2010-04-01 11:06:27Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * @package		Joomla.Site
 * @subpackage	com_projects
 */
abstract class TasksHelper
{
	/*
	 * 	Method to set a certain type to a task
	 *
	 *  @param $pk ID of the task
	 *  @param $type Type of the task
	 *
	 *  @return True or false wether the operation was successful or not
	 */
	public function setTypeTask($pk, $type = 2)
	{
		$db = JFactory::getDBO();
		$q = 'UPDATE `#__project_tasks` SET `type` = '.(int)$type.' WHERE `id` IN ('.$pk.')';
		$db->setQuery($q);
		$db->query();
			
		if($db->getErrorNum()) // if any error occured during executing the quere, return false
		return false;
		else // otherwise return true
		return true;
	}

	/*
	 * Method to get a correct prefix for translation strings
	 *
	 *  @param $type Type of task
	 *
	 *  @return Correct translation prefix
	 */
	public function getPrefix($type)
	{
		switch($type)
		{
			case 1:
				return 'MILESTONE';

			case 2:
				return 'TASK';
					
			case 3:
			default:
				return 'TICKET';
		}
	}
}
?>