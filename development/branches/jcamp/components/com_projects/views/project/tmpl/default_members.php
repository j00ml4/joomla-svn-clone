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
	<h4><?php echo JText::_('COM_PROJECTS_TEAM_LIST');?></h4>
	<?php if ($this->canDo->get('project.view')): ?>
		
		<form>
			<input type="button" value="<?php echo JText::_('COM_PROJECTS_TEAM_USER_LIST');?>" onClick="parent.location='<?php echo JRoute::_('index.php?option=com_projects&view=members&type=list&id='.$this->item->id);?>'">
		</form>
	<?php endif; ?>
	<?php if ($this->canDo->get('project.edit')): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_projects&view=members&type=assign&id='.$this->item->id);?>"><?php echo JText::_('COM_PROJECTS_TEAM_USER_ASSIGN');?></a><br />
		<a href="<?php echo JRoute::_('index.php?option=com_projects&view=members&type=delete&id='.$this->item->id);?>"><?php echo JText::_('COM_PROJECTS_TEAM_USER_DELETE');?></a>
	<?php endif; ?>
</div>
