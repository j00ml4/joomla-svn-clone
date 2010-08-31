<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Create a shortcut for params.
$params	=& $item->params;
$model	=$this->getModel();
?>
<div class="formelm_buttons">
<?php switch($this->type){
	case 'task':	
		echo '<a class="tab readmore" title="'. JText::_('COM_PROJECTS_MEMBERS_ASSIGN_DESC') .'" href="'.ProjectsHelper::getLink('tasks.ticket', $this->project->id).'">'. 
			JText::_('COM_PROJECTS_TASKS_LIST_TICKETS') .'</a>';
		break;		
		
	case 'ticket':
		echo '<a class="tab readmore" title="'. JText::_('COM_PROJECTS_MEMBERS_DELETE_DESC') .'" href="'.ProjectsHelper::getLink('tasks.task', $this->project->id).'">'. 
			JText::_('COM_PROJECTS_TASKS_LIST_TASKS') .'</a>';
		break;		
} ?>
</div>
