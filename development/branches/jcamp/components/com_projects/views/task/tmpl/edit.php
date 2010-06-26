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
<script language="javascript" type="text/javascript">
function submitbutton(task) {
	if (task == 'task.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
		submitform(task);
	}
}
</script>
<div class="projects">
<div class="edit item-page<?php echo $this->escape($params->get('pageclass_sfx')); ?>">
	<?php if ($params->get('show_page_heading', 1)) : ?> 
	<h1 class="componentheading">
		<?php echo $this->escape($params->get('page_heading', 
			empty($this->item->id)? 
				JText::_('COM_PROJECTS_TASK_NEW'): 
				JText::_('COM_PROJECTS_TASK_EDIT')) ); ?>
	</h1>
	<?php endif; ?>
		
	<form action="<?php echo 'index.php?option=com_projects'; ?>" method="post" name="adminForm" id="adminForm" class="form-validate">	
		<!-- This keeps the code more DRY -->	
		<?php echo $this->loadTemplate('buttons'); ?>
		
		<?php if (empty($this->item->id)): ?>
		<fieldset>
			<legend><?php echo JText::_('COM_PROJECTS_FIELD_TYPE_LABEL'); ?></legend>
			<div class="formelm">
				<?php echo $this->form->getInput('type', null,  $this->params->get('type', 3)); ?>
			</div>
		</fieldset>
		<?php endif; ?>
		
		<fieldset>
			<legend><?php echo JText::_('JGLOBAL_DESCRIPTION'); ?></legend>
			
			
			<div class="formelm">
				<?php echo $this->form->getLabel('parent_id'); ?>
				<?php echo $this->form->getInput('parent_id', null,  $this->params->get('parent_id')); ?>
			</div>
			
			<div class="formelm">
				<?php echo $this->form->getLabel('title'); ?>
				<?php echo $this->form->getInput('title'); ?>
				
				<?php echo $this->form->getInput('catid', null, $this->params->get('catid')); ?>
			</div>	
					
			<div class="formelm">
			<?php echo $this->form->getInput('description'); ?>
			</div>
		</fieldset>


		<?php if ($this->canDo->get('project.edit.state')): ?>
		<fieldset>
			<legend><?php echo JText::_('JDETAILS'); ?></legend>

			<div class="formelm">
				<?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?>
			</div>
			
			<div class="formelm">
				<?php echo $this->form->getLabel('estimate'); ?>
				<?php echo $this->form->getInput('estimate'); ?>
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
				<?php echo $this->form->getLabel('ordering'); ?>
				<?php echo $this->form->getInput('ordering'); ?>
			</div>
			
			<div class="formelm">
				<?php echo $this->form->getLabel('language'); ?>
				<?php echo $this->form->getInput('language'); ?>
			</div>
			
			<div class="formelm">
				<?php echo $this->form->getLabel('access'); ?>
				<?php echo $this->form->getInput('access'); ?>
			</div>	
		</fieldset>
		<?php endif; ?>
		
		<!-- SubTemplates are DRY -->	
		<?php echo $this->loadTemplate('buttons'); ?>
		
		<input type="hidden" name="task" value="task.save" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>
</div>