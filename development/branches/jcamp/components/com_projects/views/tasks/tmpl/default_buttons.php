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
<div class="formelm_buttons projects-content toolbar-list">
	<ul class="actions">
		<?php if ($this->canDo->get('task.create')): ?>
		<li>
			<?php echo JHTML::_('action.link', JText::_('COM_PROJECTS_TASKS_NEW_'.$this->prefix.'_LINK'), 'task.add'); ?>
		</li>
		<?php endif; ?>
		<?php if ($this->canDo->get('task.delete')): ?>
		<li>
			<?php echo JHTML::_('action.question', JText::_('COM_PROJECTS_TASKS_DELETE_'.$this->prefix.'_LINK'), JText::_('COM_PROJECTS_TASKS_DELETE_'.$this->prefix.'_DELETE_MSG'),'tasks.delete');?>
		</li>
		<?php endif; ?>
		<li>
			<?php echo JHTML::_('action.link', JText::_('COM_PROJECTS_TASKS_BACK_TO_PROJECT'), 'tasks.back'); ?>
		</li>
	</ul>
</div>