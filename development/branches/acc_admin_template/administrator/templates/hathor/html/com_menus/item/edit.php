<?php
/**
 * @version		$Id: edit.php 14276 2010-01-18 14:20:28Z louis $
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHTML::_('behavior.modal');
?>

<script type="text/javascript">
<!--
	function submitbutton(task)
	{
		if (task == 'item.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			submitform(task);
		}
	}
// -->
</script>

<div class="menuitem-edit">

<form action="<?php JRoute::_('index.php?option=com_menus'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

<div class="col main-section">
	<fieldset class="adminform">
		<legend><?php echo JText::_('Menus_Item_Details');?></legend>

		<div>
			<?php echo $this->form->getLabel('title'); ?>
			<?php echo $this->form->getInput('title'); ?>
		</div>
		<div>
			<?php echo $this->form->getLabel('alias'); ?>
			<?php echo $this->form->getInput('alias'); ?>
		</div>
		<div>
			<?php echo $this->form->getLabel('type'); ?>
			<?php echo $this->form->getInput('type'); ?>
		</div>

		<?php if ($this->item->type =='url') : ?>
			<div>
				<?php echo $this->form->getLabel('link'); ?>
				<?php echo $this->form->getInput('link'); ?>
			</div>
		<?php endif; ?>

		<?php if ($this->item->type !=='url') : ?>
			<div>
				<?php echo $this->form->getLabel('link'); ?>
				<?php echo $this->form->getInput('link'); ?>
			</div>
		<?php endif; ?>

		<div>
			<?php echo $this->form->getLabel('published'); ?>
			<?php echo $this->form->getInput('published'); ?>
		</div>
		<div>
			<?php echo $this->form->getLabel('access'); ?>
			<?php echo $this->form->getInput('access'); ?>
		</div>
		<div>
			<?php echo $this->form->getLabel('menutype'); ?>
			<?php echo $this->form->getInput('menutype'); ?>
		</div>
		<div>
			<?php echo $this->form->getLabel('parent_id'); ?>
			<?php echo $this->form->getInput('parent_id'); ?>
		</div>
		<div>
			<?php echo $this->form->getLabel('browserNav'); ?>
			<?php echo $this->form->getInput('browserNav'); ?>
		</div>
		<div>
			<?php echo $this->form->getLabel('home'); ?>
			<?php echo $this->form->getInput('home'); ?>
		</div>
		<div>
			<?php echo $this->form->getLabel('template_style_id'); ?>
			<?php echo $this->form->getInput('template_style_id'); ?>
		</div>

	</fieldset>
</div>

<div class="col options-section">
	<?php echo JHtml::_('sliders.start','menu-sliders-'.$this->item->id); ?>
	<?php //Load  parameters.
			echo $this->loadTemplate('options'); ?>

		<div class="clr"></div>

		<?php if (!empty($this->modules)) : ?>
			<?php echo JHtml::_('sliders.panel',JText::_('Menu_Item_Module_Assignment'), 'module-options'); ?>
			<fieldset>
				<?php echo $this->loadTemplate('modules'); ?>
			</fieldset>
		<?php endif; ?>

	<?php echo JHtml::_('sliders.end'); ?>
</div>
	<input type="hidden" name="task" value="" />
	<?php echo $this->form->getInput('component_id'); ?>
	<?php echo JHtml::_('form.token'); ?>
</form>

<div class="clr"></div>
</div>

