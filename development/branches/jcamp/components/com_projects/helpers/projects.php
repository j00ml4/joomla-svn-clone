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
class ProjectsHelper
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
		empty($user) &&	$user = JFactory::getUser();
		$params	= JComponentHelper::getParams('com_projects');
		
		// Can do action?
		$canDo = false;
		
		// What action?
		switch($action){
			// Can view
			case 'project.view':
				$canDo 	= $params->get('view_projects', 1) ||
					$user->authorise('project.manage', $context) ||
					$user->authorise('project.work', $context) ||
					$user->authorise('project.text', $context);
				break;
			// Can create project
			case 'project.create':
				$canDo = $user->authorise('project.manage', $context);
				break;
				
			// Can delete project
			case 'project.delete':
				$canDo = $user->authorise('project.manage', $context);
				break;	 
								
			// Can edit project
			case 'project.edit':
				$canDo = $user->authorise('project.manage', $context);
				break;

			// Can can cchange the state of project
			case 'project.edit.state':
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
			case 'project.view.tasks':
				$canDo 	= $params->get('view_tasks', 1) ||
					$user->authorise('project.manage', $context) ||
					$user->authorise('project.work', $context);
				break;
				
			/* Tickets */
			// Can view project tickets
			case 'project.view.tickets':
				$canDo 	= $params->get('view_tickets', 1) ||
					$user->authorise('project.manage', $context) ||
					$user->authorise('project.work', $context);
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
	public static function getActions($record=null, $catid=null)
	{
		$user	= JFactory::getUser();
		$canDo	= new JObject;

		$context = 'com_projects';
		if (!empty($record->id)) {
			$context = 'com_projects.'.(int) $record->id;
		}
		elseif (!empty($catid)) {
			$context = 'com_projects.category.'.(int) $catid;
		}
		
		// Action
		$actions = array(
			// Project
			'project.view',
			'project.create',
			'project.delete',
			'project.edit', 
			'project.edit.state',
			'project.edit.portfolio', 
			'project.edit.lang',
			'project.edit.order',
		
			// Tasks
			'project.view.tasks',
		
			// Documents
			'project.view.documents',
		
			// Tickets
			'project.view.tickets',
		
			// Activities
			'project.view.activities',
		);
		
		// Check Can do list
		foreach ($actions as $action) {
			$canDo->set($action, self::can($action, $context, $record, $user));
		}

		return $canDo;
	}
	
	/**
	 * Include CSS styles to page
	 *
	 * @since	1.6
	 */
	public static function includeCSS($style='default')
	{
		$document = &JFactory::getDocument();
		$document->addStyleSheet('components/com_projects/assets/css/'.$style.'.css');
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