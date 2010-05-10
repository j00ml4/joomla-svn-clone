<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_templates
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
<!--
	function submitbutton(task)
	{
		if (task == 'style.cancel' || document.formvalidator.isValid(document.id('style-form'))) {
			submitform(task);
		}
	}
// -->
</script>

<form action="<?php JRoute::_('index.php?option=com_templates'); ?>" method="post" name="adminForm" id="style-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('JDETAILS');?></legend>
			
			<?php echo $this->form->getLabel('title'); ?>
			<?php echo $this->form->getInput('title'); ?>
			
			<?php echo $this->form->getLabel('template'); ?>
			<?php echo $this->form->getInput('template'); ?>

			<?php echo $this->form->getLabel('client_id'); ?>
			<input type="text" size="35" value="<?php echo $this->item->client_id == 0 ? JText::_('JSITE') : JText::_('JADMINISTRATOR'); ?>	" class="readonly" readonly="readonly" />


			<?php echo $this->form->getLabel('home'); ?>
			<?php echo $this->form->getInput('home'); ?>
		<div class="clr"></div>
			<?php if ($this->item->id) : ?>
				<?php echo $this->form->getLabel('id'); ?>
				<input type="text" size="35" value="<?php  echo ($text = (string) JText::_($this->item->id)); ?>" class="readonly" readonly="readonly" />

			<?php endif; ?>
		</fieldset>
	</div>

	<div class="width-40 fltrt">
	<?php echo JHtml::_('sliders.start','template-sliders-'.$this->item->id); ?>

		<?php //get the menu parameters that are automatically set but may be modified.
			echo $this->loadTemplate('options'); ?>

		<div class="clr"></div>

	<?php echo JHtml::_('sliders.end'); ?>
	</div>

	<div class="clr"></div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
