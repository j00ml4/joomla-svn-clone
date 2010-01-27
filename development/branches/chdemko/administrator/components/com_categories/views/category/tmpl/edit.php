<?php
/**
 * @version	 $Id$
 * @package		Joomla.Administrator
 * @subpackage	com_categories
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<script type="text/javascript">
<!--
	function submitbutton(task)
	{
		if (task == 'category.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			submitform(task);
		}
	}
// -->
</script>

<form action="<?php JRoute::_('index.php?option=com_menus'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('Categories_Fieldset_Details');?></legend>

			<?php echo $this->form->getLabel('title'); ?>
			<?php echo $this->form->getInput('title'); ?>

			<?php echo $this->form->getLabel('alias'); ?>
			<?php echo $this->form->getInput('alias'); ?>

			<?php echo $this->form->getLabel('extension'); ?>
			<?php echo $this->form->getInput('extension'); ?>

			<?php echo $this->form->getLabel('parent_id'); ?>
			<?php echo $this->form->getInput('parent_id'); ?>

			<?php echo $this->form->getLabel('published'); ?>
			<?php echo $this->form->getInput('published'); ?>

			<?php echo $this->form->getLabel('access'); ?>
			<?php echo $this->form->getInput('access'); ?>

			<?php echo $this->loadTemplate('options'); ?>
			
			<?php echo $this->form->getLabel('language'); ?>
			<?php echo $this->form->getInput('language'); ?>

			<div class="clr"></div>
			<?php echo $this->form->getLabel('description'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('description'); ?>
		</fieldset>
		<?php foreach ($this->form->getFieldsets() as $name => $fieldSet) :?>
			<?php if (!in_array($name, array('params','metadata','_default')) && $name[0]=='_' && (!isset($fieldSet['hidden']) || !$fieldSet['hidden'])) :?>
		<fieldset class="adminform">
			<legend><?php echo JText::_(isset($fieldSet['label']) ? $fieldSet['label'] : 'Config_'.$name);?></legend>
				<?php foreach ($this->form->getFields($name) as $field) :?>
					<?php if (!$field->hidden):?>
						<?php echo $field->label; ?>
					<?php endif;?>
					<?php echo $field->input; ?>
				<?php endforeach;?>
			<?php endif;?>
		<?php endforeach;?>
		</fieldset>
	</div>

	<div class="width-40 fltrt">
		<?php echo JHtml::_('sliders.start','category-sliders-'.$this->item->id, array('useCookie'=>1)); ?>

		<?php echo JHtml::_('sliders.panel',JText::_('Categories_Fieldset_Rules'), 'category-rules'); ?>

		<fieldset class="panelform">
			<?php echo $this->form->getLabel('rules'); ?>
			<?php echo $this->form->getInput('rules'); ?>
		</fieldset>

		<?php echo JHtml::_('sliders.panel',JText::_('Categories_Fieldset_Metadata'), 'category-metadata'); ?>

		<fieldset class="panelform">
			<?php echo $this->loadTemplate('metadata'); ?>
		</fieldset>

		<?php foreach ($this->form->getFieldsets() as $name => $fieldSet) :?>
			<?php if (!in_array($name, array('params','metadata','_default')) && $name[0]!='_' && (!isset($fieldSet['hidden']) || !$fieldSet['hidden'])) :?>
				<?php echo JHtml::_('sliders.panel',JText::_(isset($fieldSet['label']) ? $fieldSet['label'] : 'Config_'.$name), $name.'-options');?>
				<?php if (isset($fieldSet['description'])) :?>
					<p class="tip"><?php echo JText::_($fieldSet['description']);?></p>
					<div class="clr"></div>
				<?php endif;?>
				<fieldset class="panelform">
					<?php foreach ($this->form->getFields($name) as $field) :?>
						<?php if (!$field->hidden):?>
							<?php echo $field->label; ?>
						<?php endif;?>
						<?php echo $field->input; ?>
					<?php endforeach;?>
				</fieldset>
			<?php endif;?>
		<?php endforeach;?>

		<?php echo JHtml::_('sliders.end'); ?>
		
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<div class="clr"></div>

