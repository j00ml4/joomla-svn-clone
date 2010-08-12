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
	if (task == 'project.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
		submitform(task);
	}
}
</script>
<div class="projects">
<div class="edit item-page<?php echo $params->get('pageclass_sfx'); ?>">
	<?php if ($params->get('show_page_heading', 1)) : ?>
	<?php /* this allows the user to set the title he wants */ ?> 
	<h1 class="componentheading"><?php echo $this->escape($params->get('page_heading', 
			JText::_('COM_PROJECTS_PROJECT_FORM_TITLE'))); ?></h1>
	<?php endif; ?>
	
	<?php /** This way we have more controll over the design */ ?>
	<form action="<?php echo JRoute::_('index.php?option=com_projects'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">	
		<!-- This keeps the code more DRY -->	
		<?php echo $this->loadTemplate('buttons'); ?>
		
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
		
		<!-- SubTemplates are DRY -->	
		<?php echo $this->loadTemplate('buttons'); ?>
		
		<input type="hidden" name="task" value="" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>
</div>