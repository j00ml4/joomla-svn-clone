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
	<h2><?php echo JText::_('COM_PROJECTS_TEAM_LIST');?></h2>
	<?php if ($this->canDo->get('project.view')): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_projects&view=members&type=list&id='.$this->item->id);?>"><?php echo JText::_('COM_PROJECTS_TEAM_USER_LIST');?></a><br />
	<?php endif; ?>
	<?php if ($this->canDo->get('project.edit')): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_projects&view=members&type=assign&id='.$this->item->id);?>"><?php echo JText::_('COM_PROJECTS_TEAM_USER_ASSIGN');?></a><br />
		<a href="<?php echo JRoute::_('index.php?option=com_projects&view=members&type=delete&id='.$this->item->id);?>"><?php echo JText::_('COM_PROJECTS_TEAM_USER_DELETE');?></a>
	<?php endif; ?>
</div>
