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
	 * Can do some action
	 * 
	 * @param $action
	 * @param $context
	 * @param $user
	 * @param $record
	 */
	public function can($action, $context=null, $record=null, $user=null )
	{	
		// Set vars
		if (empty($user)) {
			$user = JFactory::getUser();
		}
		// set params
		$params	= JComponentHelper::getParams('com_projects');
		
		// Can do action?
		$canDo = false;
		switch($action){
			// Can view
			case 'project.view':
				$canDo 	= (
					// Owner 
					(
						!empty($record->created_by) && 
						$record->created_by == $user->get('id')
					) ||
					
					// Param all can view
					( 
						($params->get('view_projects') == 2) ||
						(
							($params->get('view_projects') == 1) &&
							!empty($record->featured) && 
							$record->featured > 0
						)
					) ||
						
					// Role permission 
					(
						$user->authorise('project.manage', $context) ||
						$user->authorise('project.work', $context) ||
						$user->authorise('project.text', $context)
					)	
				);	
				break;
				
			// Can create project
			case 'project.create':
				$canDo 	= (
					empty($record->id) &&					
					// Permissions
				 	(
						$user->authorise('project.manage', $context)
					)
				);
				break;
				
			// Can delete project
			case 'project.delete':
				$canDo = $user->authorise('project.manage', $context);
				break;	 
								
			// Can edit project
			case 'project.edit':
				$canDo 	= (
					!empty($record->id) &&
					// Owner 
					(
						!empty($record->created_by) && 
						$record->created_by == $user->get('id')
					) ||
					
					// Permissions
				 	(
						$user->authorise('project.manage', $context)
					)
				);
				break;

			// Can can change the state of project
			case 'project.edit.state':
				$canDo = $user->authorise('project.manage', $context);
				break;	

			// Can can change the ACL rules of project
			case 'project.edit.rules':
				$canDo = $user->authorise('project.manage', $context);
				break;	
				
			// Can edit project portifolio
			case 'project.edit.portfolio':
				$canDo = $user->authorise('project.manage', $context);
				break;

			// Can edit project language
			case 'project.edit.lang':
				$canDo = $user->authorise('project.manage', $context);
				break;
			
			// Can edit project ordering
			case 'project.edit.order':
				$canDo = $user->authorise('project.manage', $context);
				break;
				
			/* Tasks */
			// Can view project tasks
			case 'task.view':
				$canDo 	= $params->get('view_tasks', 1) ||
					$user->authorise('project.manage', $context) ||
					$user->authorise('project.work', $context);
				break;

			// Can view project tasks
			case 'task.view.milestones':
				$canDo 	= $params->get('view_milestones', 1) ||
					$user->authorise('project.manage', $context) ||
					$user->authorise('project.work', $context);
				break;
			
				
			// Can view project tickets
			case 'task.view.tickets':
				$canDo 	= (
				 	$params->get('view_tickets', 1) ||
					$user->authorise('project.manage', $context) ||
					$user->authorise('project.test', $context) ||
					$user->authorise('project.work', $context)
				);
				break;	
								
			// Can create project
			case 'task.create':
				$canDo 	= (
					empty($record->id) &&					
					// Permissions
				 	(
						$user->authorise('project.work', $context)
					)
				);
				break;
				
			// Can create project
			case 'task.create.milestone':
				$canDo 	= (
					empty($record->id) &&					
					// Permissions
				 	(
						$user->authorise('project.manage', $context)
					)
				);
				break;	

			// Can create project
			case 'task.create.ticket':
				$canDo 	= (
					empty($record->id) &&					
					// Permissions
				 	(
						$user->authorise('project.test', $context)
					)
				);
				break;
				
			// Can delete project
			case 'task.delete':
				$canDo = $user->authorise('project.work', $context);
				break;

			// Can edit project
			case 'task.edit':
				$canDo 	= (
					!empty($record->id) &&
					// Owner 
					(
						!empty($record->created_by) && 
						$record->created_by == $user->get('id')
					) ||
					
					// Permissions
				 	(
						$user->authorise('project.work', $context)
					)
				);
				break;

			// Can can change the state of project
			case 'task.edit.state':
				$canDo = (
					!empty($record->id) &&
					// Owner 
					(
						!empty($record->created_by) && 
						$record->created_by == $user->get('id')
					) ||
					
					// Permissions
				 	(
						$user->authorise('project.work', $context) ||
						$user->authorise('project.manage', $context)
					)
				);
				break;	
				
			/* Documents */
			// Can view project documents
			case 'project.view.documents':
				$canDo 	= $params->get('view_documents', 1) ||
					$user->authorise('project.manage', $context) ||
					$user->authorise('project.work', $context);
				break;

			/* Activities */
			// Can view project activities
			case 'project.view.activities':
				$canDo 	= $params->get('view_documents', 1) ||
					$user->authorise('project.manage', $context) ||
					$user->authorise('project.work', $context);
				break;					
		}
		
		// Return permition
		return $canDo;
	}
	
	
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param	int		The category ID.
	 * @return	JObject
	 * @since	1.6
	 */
	public function getActions($context=null, $record=null)
	{	
		// Action
		$actions 	= array(
			// Project
			'project.view',
			'project.view.members',
			'project.create',
			'project.delete',
			'project.edit', 
			'project.edit.state',
			'project.work',
		
			// Tasks
			'task.view',
			'task.view.milestones',
			'task.view.tickets',
			'task.create',
			'task.create.milestones',
			'task.create.tickets',
			'task.edit', 
			'task.edit.state',
			'task.delete',
		
			// Documents
			'documents.view',
		
			// Activities
			'project.view.activities',
		);
		
		// Check Can do list
		$canDo	= new JObject;
		foreach ($actions as $action) {
			$canDo->set($action, self::can($action, $context, $record));
		}
		return $canDo;
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

/**
 * Simple debug function
 * @param $data
 */
function dump($data){
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}
?>