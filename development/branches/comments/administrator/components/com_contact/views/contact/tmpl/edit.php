<?php
/**
 * @version		$Id: edit.php 12295 2009-06-22 11:10:18Z eddieajau $
 * @package		Joomla.Administrator
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();
?>
<script type="text/javascript">
<!--
	function submitbutton(task)
	{
		if (task == 'contact.cancel' || document.formvalidator.isValid(document.id('contact-form'))) {
		}
		// @todo Deal with the editor methods
		submitform(task);
	}
// -->
</script>

<form action="<?php JRoute::_('index.php?option=com_contact'); ?>" method="post" name="adminForm" id="contact-form" class="form-validate">
	<div class="width-50 fltlft">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_CONTACT_NEW_CONTACT') : JText::sprintf('COM_CONTACT_EDIT_CONTACT', $this->item->id); ?></legend>

				<?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?>

				<?php echo $this->form->getLabel('alias'); ?>
				<?php echo $this->form->getInput('alias'); ?>

				<?php echo $this->form->getLabel('user_id'); ?>
				<?php echo $this->form->getInput('user_id'); ?>

				<?php echo $this->form->getLabel('access'); ?>
				<?php echo $this->form->getInput('access'); ?>

				<?php echo $this->form->getLabel('published'); ?>
				<?php echo $this->form->getInput('published'); ?>

				<?php echo $this->form->getLabel('catid'); ?>
				<?php echo $this->form->getInput('catid'); ?>

				<?php echo $this->form->getLabel('ordering'); ?>
				<?php echo $this->form->getInput('ordering'); ?>

				<?php echo $this->form->getLabel('sortname1'); ?>
				<?php echo $this->form->getInput('sortname1'); ?>

				<?php echo $this->form->getLabel('sortname2'); ?>
				<?php echo $this->form->getInput('sortname2'); ?>

				<?php echo $this->form->getLabel('sortname3'); ?>
				<?php echo $this->form->getInput('sortname3'); ?>

				<?php echo $this->form->getLabel('language'); ?>
				<?php echo $this->form->getInput('language'); ?>

				<div class="clr"> </div>
				<?php echo $this->form->getLabel('misc'); ?>
				<div class="clr"> </div>
				<?php echo $this->form->getInput('misc'); ?>
		</fieldset>
	</div>

	<div class="width-50 fltrt">
		<?php echo  JHtml::_('sliders.start', 'contact-slider'); ?>
		<?php foreach ($fieldsets as $fieldset) :
			if ($fieldset->name == '') :
				continue;
			endif;
			echo JHTML::_('sliders.panel', JText::_($fieldset->label), $fieldset->name);
		?>
		<fieldset class="panelform">
		<?php foreach($this->form->getFieldset($fieldset->name) as $field): ?>
			<?php if ($field->hidden): ?>
				<?php echo $field->input; ?>
			<?php else: ?>
				<?php echo $field->label; ?>
				<?php echo $field->input; ?>
			<?php endif; ?>
		<?php endforeach; ?>
		</fieldset>
		<?php endforeach; ?>
	<?php echo JHtml::_('sliders.end'); ?>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>