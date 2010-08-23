<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;

// HTML Helpers
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');

// Vars
$params =  $this->params;
?>
<div class="project-item">
    <div class="edit item-page<?php echo $params->get('pageclass_sfx'); ?>">
		<form action="<?php echo ProjectsHelper::getLink('project', $this->item->get('id')); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">	
            <fieldset>
                <legend><?php echo JText::_('JGLOBAL_DESCRIPTION'); ?></legend>

                <div class="formelm">
                        <?php echo $this->form->getLabel('catid'); ?>
                        <?php echo $this->form->getInput('catid', null, $params->get('catid')); ?>
                </div>

                <div class="formelm">
                       	<?php echo $this->form->getLabel('title'); ?>
                        <?php echo $this->form->getInput('title'); ?>
                </div>

                <div class="formelm">
                <?php echo $this->form->getInput('description'); ?>
                </div>
            </fieldset>


            <?php if ($this->canDo->get('core.edit.state')): ?>
            <fieldset>
                <legend><?php echo JText::_( 'JDETAILS' ); ?></legend>
                <div class="formelm">
                        <?php echo $this->form->getLabel('state'); ?>
                        <?php echo $this->form->getInput('state'); ?>
                </div>

                <div class="formelm">
                        <?php echo $this->form->getLabel('start_at'); ?>
                        <?php echo $this->form->getInput('start_at'); ?>
                </div>

                <div class="formelm">
                        <?php echo $this->form->getLabel('finish_at'); ?>
                        <?php echo $this->form->getInput('finish_at'); ?>
                </div>

                <div class="formelm">
                        <?php echo $this->form->getLabel('estimation_type', 'params'); ?>
                        <?php echo $this->form->getInput('estimation_type', 'params'); ?>
                </div>

                <div class="formelm">
                        <?php echo $this->form->getLabel('ordering'); ?>
                        <?php echo $this->form->getInput('ordering'); ?>
                </div>

                <div class="formelm">
                        <?php echo $this->form->getLabel('language'); ?>
                        <?php echo $this->form->getInput('language'); ?>
                </div>
            </fieldset>
            <?php endif; ?>

            <input type="hidden" name="task" value="" />
            <?php echo JHTML::_( 'form.token' ); ?>
		</form>
    </div>
</div>