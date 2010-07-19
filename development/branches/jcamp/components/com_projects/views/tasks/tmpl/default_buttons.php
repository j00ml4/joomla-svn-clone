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
		<li  class="">
			<?php echo JHTML::_('action.link', JText::_('COM_PROJECTS_NEW_TASK_LINK'), 'task.add'); ?>
		</li>
		<?php endif; ?>
		<li>
			<?php echo JHTML::_('action.link', JText::_('COM_PROJECTS_BACK_TO_PROJECT_TASKS_LINK'), 'tasks.back'); ?>
		</li>
	</ul>
</div>