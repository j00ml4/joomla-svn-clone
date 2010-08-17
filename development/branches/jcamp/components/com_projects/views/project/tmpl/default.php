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

// Vars
$params = $this->params;
$pageClass = $this->params->get('pageclass_sfx');
?>
<div class="projects<?php echo $pageClass ?>">
    <div class="two3">
        <form action="<?php echo ProjectsHelper::getLink('project', $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
            <div class="projects-content">
                <?php echo $this->item->description; ?>
                <div class="projects-space">
                    <?php echo JHTML::_('tool.progressBar', 10); ?>
                </div>

                <dl class="projects-info projects-space projects-frame">
                    <dt><?php echo JText::_('COM_PROJECTS_FIELD_START_AT_LABEL') ?>:</dt>
                    <dd><?php echo JFactory::getDate($this->item->start_at)->toFormat('%d.%m.%Y'); ?></dd>
                    <dt><?php echo JText::_('COM_PROJECTS_FIELD_FINISH_AT_LABEL') ?>:</dt>
                    <dd><?php echo JFactory::getDate($this->item->finish_at)->toFormat('%d.%m.%Y'); ?></dd>
                </dl>
            </div>

        <?php if ($params->get('show_activities') || $this->canDo->get('activity.view')): ?>
            <div class="project-activities projects-space">
                <?php echo $this->loadTemplate('activities'); ?>
            </div>
        <?php endif; ?>

            <input type="hidden" name="task" value="" />
            <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
            <input type="hidden" name="boxchecked" value="1" />
            <?php echo JHTML::_('form.token'); ?>
        </form>
    </div>

    <div class="one3">
        <div class="projects-space">
        <?php
            // Tasks and Milestones
            if ($params->get('show_tasks') || $this->canDo->get('task.view')) {
                echo $this->loadTemplate('tasks');
            }

            // Tickets
            if ($params->get('show_tickets') || $this->canDo->get('ticket.view')) {
                echo $this->loadTemplate('tickets');
            }

            // Documents
            if ($params->get('show_documents') || $this->canDo->get('document.view')) {
                echo $this->loadTemplate('documents');
            }

            // Members
            if ($params->get('show_members') || $this->canDo->get('is.member')) {
                echo $this->loadTemplate('members');
            }
        ?>
        </div>
    </div>
</div>
