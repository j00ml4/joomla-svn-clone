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
    <h4><?php echo JText::_('COM_PROJECTS_TASKS_LIST_TASKS'); ?></h4>
    <ul class="items-list tasks-list">
        <li>task</li>
        <li>task</li>
    </ul>
    <a href="<?php echo ProjectsHelper::getLink('tasks.tasks', $this->item->id); ?>" class="readmore">
        <?php echo JText::_('COM_PROJECTS_TASKS_LIST_TASKS_LINK'); ?></a>
</div>
