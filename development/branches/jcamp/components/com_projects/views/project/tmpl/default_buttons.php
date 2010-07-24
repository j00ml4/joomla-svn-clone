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
$uri = &JFactory::getURI();
$id = $this->item->id;
?>
<div class="formelm_buttons projects-content toolbar-list">
	<ul class="actions">
		<?php if ($this->canDo->get('project.edit')): ?>
		<li  class="edit-icon">
			<?php echo JHTML::_('action.link', JText::_('JGLOBAL_EDIT'), 'project.edit', $id); ?>
		</li>
		<?php endif; ?>
	
		<!-- edit state --> 
		<?php if ($this->canDo->get('project.edit.state')): ?>
		<li>
			<?php if ($this->item->state == 0): ?>
				<?php echo JHTML::_('action.task', JText::_('JGLOBAL_PUBLISH'), 'project.publish', $id); ?>
			<?php else: ?>
				<?php echo JHTML::_('action.task', JText::_('JGLOBAL_UNPUBLISH'), 'project.unpublish', $id); ?>
			<?php endif; ?>
		</li>	
		<?php endif; ?>
		
		<!--  Delete -->
		<?php if ($this->canDo->get('project.delete')): ?>
		<li>
			<?php echo JHTML::_('action.delete', JText::_('JGLOBAL_DELETE'), 'project', $id); ?>
		</li>
		<?php endif; ?>			
		<li>
			<?php echo JHTML::_('action.task', JText::_('COM_PROJECT_BACK_TO_PROJECTS_PROJECT_LINK'), 'project.back'); ?>
		</li>
	</ul>
</div>