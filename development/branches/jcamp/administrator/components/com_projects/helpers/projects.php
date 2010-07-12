<?php
/**
 * @version		$Id: weblinks.php 17267 2010-05-25 19:16:30Z chdemko $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Weblinks helper.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_projects
 * @since		1.6
 */
class ProjectsHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 * @since	1.6
	 */
	public static function addSubmenu()
	{
		$extension = JRequest::getCmd('extension');
		$vName = JRequest::getCmd('view');
		
		JSubMenuHelper::addEntry(
			JText::_('COM_PROJECTS_CONFIG'),
			'index.php?option=com_projects&view=config',
			$vName == 'config'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('COM_PROJECTS_PORTFOLIOS'),
			'index.php?option=com_categories&extension=com_projects',
			$extension == 'com_projects'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('COM_PROJECTS_TASK_CATEGORIES'),
			'index.php?option=com_categories&extension=com_projects.task',
			$extension == 'com_projects.task'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('COM_PROJECTS_DOCUMENT_CATEGORIES'),
			'index.php?option=com_categories&extension=com_projects.document',
			$extension == 'com_projects.document'
		);
		
		// Each Views
		switch($extension){
			case 'com_projects':
				JToolBarHelper::title( JText::_('COM_PROJECTS_PORTFOLIOS'), 'categories' );
				break;
				
			case 'com_projects.task':
				JToolBarHelper::title( JText::_('COM_PROJECTS_TASK_CATEGORIES'), 'categories' );
				break;
				
			default:
				JToolBarHelper::title( JText::_('COM_PROJECTS'), 'article' );
				JToolBarHelper::preferences('com_projects');	
		}
		
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param	int		The category ID.
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions($categoryId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($categoryId)) {
			$assetName = 'com_projects';
		} else {
			$assetName = 'com_projects.category.'.(int) $categoryId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
}
