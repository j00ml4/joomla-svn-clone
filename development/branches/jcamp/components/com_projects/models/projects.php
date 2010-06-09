<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_project
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Portifolio gallery view to display all projects within a portifolio
 * @author eden & elf
 *
 */
class ProjectsModelProjects extends JModelList
{

	/**
	 * @param	boolean	True to join selected foreign information
	 *
	 * @return	string
	 * @since	1.6
	 */
	function getListQuery()
	{
		$task = JRequest::getCmd('layout','default');
		// Create a new query object.
		$db = $this->getDbo();
		$q = $db->getQuery(true);
		
		switch($task)
		{
			case 'default' :
				{
					// Select the required fields from the table.
					$q->select('c.`id`, c.`title`');
					$q->from('`#__categories` c');
					$q->where('c.`extension`=\'com_projects\'');
					$q->order('c.`title`');
					break;
				}
			case 'gallery' :
				{
					// Is more meanfull to have ther real nama of the field
					$id = JRequest::getInt('catid',0);
		
					// Select the required fields from the table.
					$q->select('p.`id`, p.`title`, p.`alias`, p.`description`, u.`name`, p.`created`');
					$q->from('`#__projects` AS p');
					$q->leftJoin('`#__users` AS u ON u.`id` = p.`created_by`');
					$q->where('p.`catid`='.$id);
					$q->order('p.`ordering`');
					break;
				}
		}
		
		return $q;
	}

}
?>