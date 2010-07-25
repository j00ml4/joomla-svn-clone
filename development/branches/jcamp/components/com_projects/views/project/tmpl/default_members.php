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
			echo JHTML::_('action.link',JText::_('COM_PROJECTS_PROJECT_TEAM_USER_LIST'),$this->item->id,'index.php?option=com_projects&view=members&type=list');
		endif;
	
	if ($this->canDo->get('project.edit')): ?>
		<br/>
		<a href="<?php echo JRoute::_('index.php?option=com_projects&view=members&type=assign&id='.$this->item->id);?>"><?php echo JText::_('COM_PROJECTS_PROJECT_TEAM_USER_ASSIGN');?></a><br />
		<a href="<?php echo JRoute::_('index.php?option=com_projects&view=members&type=delete&id='.$this->item->id);?>"><?php echo JText::_('COM_PROJECTS_PROJECT_TEAM_USER_DELETE');?></a>
	<?php endif; ?>
</div>
