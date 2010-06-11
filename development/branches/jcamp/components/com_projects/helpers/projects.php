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
 * @subpackage	Media
 */
class ProjectsHelper
{
	
	public function getRoles(){
		
	}
	
	/**
	 * Can do some action
	 * 
	 * @param $action
	 * @param $context
	 * @param $user
	 * @param $record
	 */
	public function can($action, $context=null, $user=null, $record=null)
	{	
		// User
		empty($user) &&	$user = JFactory::getUser();
		
		// Can do action?
		$canDo = false;
		
		// What action?
		switch($action){
			// Can create project
			case 'project.create':
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
				
				
			// Can delete project
			case 'project.delete':
				$canDo = $user->authorise('project.manage', $context);
				break;	 
		}
		
		// Return permition
		return $canDo;
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