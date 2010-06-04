<?php
/**
 * @version		$Id: edit.php 17079 2010-05-15 17:17:59Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');

// Create shortcut to parameters.
$params = $this->state->get('params');
?>

<script language="javascript" type="text/javascript">
function submitbutton(task) {
	if (task == 'article.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
		<?php //echo $this->form->fields['introtext']->editor->save('jform[introtext]'); ?>
		submitform(task);
	}
}
</script>
<div class="edit item-page<?php echo $this->escape($params->get('pageclass_sfx')); ?>">
<?php if ($params->get('show_page_heading', 1)) : ?>
<h1>
	<?php echo $this->escape($params->get('page_heading')); ?>
</h1>
<?php endif; ?>

<form action="<?php echo JRoute::_('index.php?option=com_projects&view=project'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<fieldset>	
		<?php echo $this->loadTemplate('buttons'); ?>
	</fieldset>
	
	<fieldset>
		<legend><?php echo JText::_('JEDITOR'); ?></legend>

		<div class="formelm">
			<?php echo $this->form->getLabel('title'); ?>
			<?php echo $this->form->getInput('title'); ?>
		</div>

		<div class="formelm">
			<?php echo $this->form->getLabel('catid'); ?>
			<?php echo $this->form->getInput('catid'); ?>
		</div>

		<?php if ($params->get('edit_language', 0)): ?>
		<div class="formelm">
			<?php echo $this->form->getLabel('language'); ?>
			<?php echo $this->form->getInput('language'); ?>
		</div>
		<?php endif; ?>
	
		<?php if ($this->user->authorise('core.edit.state', 'com_projects.project.'.$this->item->id)): ?>
		<div class="formelm">
			<?php echo $this->form->getLabel('state'); ?>
			<?php echo $this->form->getInput('state'); ?>
		</div>
		<?php endif; ?>

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

		<?php echo $this->form->getInput('description'); ?>
	</fieldset>
	
	<fieldset>	
		<?php echo $this->loadTemplate('buttons'); ?>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>