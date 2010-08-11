<?php
/**
 * @version		$Id: media.php 15757 2010-04-01 11:06:27Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	Media
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * @package		Joomla.Site
 * @subpackage	com_projects
 */
abstract class ProjectsHelper
{
	
	/**
	 * Method to determine if a user is member of a project
	 * 
	 * @param $project_id ID of a project
	 * @param $user_id ID of a user
	 *  
	 * @return False in case the user is not a member of the project or ID of user group
	 * @since	1.6
	 */
	public function isMember($project_id=0, $user_id=0)
	{	
		if(empty($project_id)){
			return true;
		}
		
		if(empty($user_id)){
			return false;
		}
		
		// Check if is member
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('count(project_id)');
		$query->from('#__project_members AS a');
		$query->where('a.project_id = '.(int) $project_id .'a.user_id='.(int)$user_id);
		$db->setQuery($query);
		
		return $db->loadResult();
	}	
	
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param	int		The category ID.
	 * @return	JObject
	 * @since	1.6
	 */
	public function getActions($portfolio_id=0, $project_id=0)
	{	
		
		$user		= JFactory::getUser();
		$list		= new JObject;
		$is_member 	= self::isMember($project_id, $user->id);
		if (empty($portfolio_id)) {
			$assetName = 'com_projects';
		} else {
			$assetName = 'com_projects.category.'.(int)$portfolio_id;
		}
		
		// Action
		$actions 	= array(
			// Project
			'core.create',
			'core.delete',
			'core.edit', 
			'core.edit.state',
			
			// Tasks
			'task.create',
			'task.edit', 
			'task.delete',
		
			// Tasks
			'ticket.create',
			'ticket.edit', 
			'ticket.delete',

			// Tasks
			'document.create',
			'document.edit', 
			'document.delete',
		);
		
		foreach ($actions as $action){
			$list->set($action, 
				($is_member && $user->authorise($action, $assetName))
			);
		}

		return $list;
	}
	
	
	public function canDo($action, $portfolio_id=0, $project_id=0){
		static $assets;
		static $portfolioId; 
		static $projectId;
		
		if(
			empty($assets) ||
			($portfolio_id && $portfolioId != $portfolio_id) ||
			($project_id && $projectId != $project_id)
		){
			$assets = self::getActions($portfolio_id, $project_id);
			$portfolioId = $portfolio_id;
			$projectId = $project_id;
		}
		
		return $assets->get($action, false);
	}
	

	/** i don t know if we need this function..
	 * Resets breadcrumb and adds "Projects" link as first
	 * 
	 * @return Reference to breadcrumb object
	 * @since	1.6
	 */
	
	public function &resetPathway()
	{
		$app = &JFactory::getApplication();
  		$bc = &$app->getPathway();
 		return $bc;
	}

	/**
	 * Method to get ID of actual menu item
	 * 
	 * @return ID of actual menu item
	 * @since	1.6
	 */
	public function getMenuItemId()
	{
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		
		$id = $menu->getActive()->id;
		if(!((int)$id))
		  $id = JRequest::getInt('Itemid',0);
		return $id;
	}
	
}
?>