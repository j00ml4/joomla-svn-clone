<?php
/**
 * @version		$Id:
 * @package		Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="projects-content projects-frame projects-space">
	<h4><?php echo JText::_('COM_PROJECTS_PROJECT_TEAM_LIST');?></h4>
	<?php if ($this->canDo->get('project.view')):
			echo JHTML::_('action.link',JText::_('COM_PROJECTS_PROJECT_TEAM_USER_LIST'),null,$this->item->id,'index.php?option=com_projects&view=members&type=list');
		endif;
	
	if ($this->canDo->get('project.edit')): ?>
		<br/>
		<?php 
		 echo JHTML::_('action.link',JText::_('COM_PROJECTS_PROJECT_TEAM_USER_ASSIGN'),null,$this->item->id,'index.php?option=com_projects&view=members&type=assign');
		 echo JHTML::_('action.link',JText::_('COM_PROJECTS_PROJECT_TEAM_USER_DELETE'),null,$this->item->id,'index.php?option=com_projects&view=members&type=delete');
		endif; ?>
</div>
