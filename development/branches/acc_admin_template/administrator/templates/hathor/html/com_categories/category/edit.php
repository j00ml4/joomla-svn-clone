<?php
/**
 * @version	 $Id: edit.php 13742 2009-12-14 22:10:18Z dextercowley $
 * @package		Joomla.Administrator
 * @subpackage	com_categories
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
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

<div class="category-edit">

<form action="<?php JRoute::_('index.php?option=com_menus'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
	<div class="col main-section">
		<fieldset class="adminform">
			<legend><?php echo JText::_('CATEGORIES_FIELDSET_DETAILS');?></legend>
				<div>
					<?php echo $this->form->getLabel('title'); ?>
					<?php echo $this->form->getInput('title'); ?>
				</div>
				<div>
					<?php echo $this->form->getLabel('alias'); ?>
					<?php echo $this->form->getInput('alias'); ?>
				</div>
				<div>
					<?php echo $this->form->getLabel('extension'); ?>
					<?php echo $this->form->getInput('extension'); ?>
				</div>
				<div>
					<?php echo $this->form->getLabel('parent_id'); ?>
					<?php echo $this->form->getInput('parent_id'); ?>
				</div>
				<div>
					<?php echo $this->form->getLabel('published'); ?>
					<?php echo $this->form->getInput('published'); ?>
				</div>
				<div>
					<?php echo $this->form->getLabel('access'); ?>
					<?php echo $this->form->getInput('access'); ?>
				</div>
				<div>
					<?php echo $this->form->getLabel('language'); ?>
					<?php echo $this->form->getInput('language'); ?>
				</div>	

					<div class="clr"></div>
					<?php echo $this->form->getLabel('description'); ?>
					<div class="clr"></div>
					<?php echo $this->form->getInput('description'); ?>
					<div class="clr"></div>
		</fieldset>
	</div>

	<div class="col options-section">

		<?php echo JHTML::_('sliders.start'); ?>
		
	<?php if(in_array('params', $this->form->getGroups())) : ?>
		
			<?php $groups = $this->form->getGroups('params');
			$fieldsets = $this->form->getFieldsets();
			array_unshift($groups, 'params'); ?>
			
			<?php foreach($groups as $group) : ?>
			
				<?php echo JHTML::_('sliders.panel', JText::_($fieldsets[$group]['label']), $group); ?>
				
				<fieldset class="panelform">
				
				<?php foreach($this->form->getFields($group) as $field) : 
					if ($field->hidden) : 
						echo $field->input; 
					else : 
						echo $field->label; 
						echo $field->input; 
					endif; 
				endforeach; ?>
				
				</fieldset>
				
			<?php endforeach; ?>
			
		<?php endif; ?>
		
		<?php echo JHtml::_('sliders.panel',JText::_('CATEGORIES_FIELDSET_METADATA'), 'meta-options'); ?>
		<fieldset class="panelform">
			<legend class="element-invisible"><?php echo JText::_('CATEGORIES_FIELDSET_METADATA'); ?></legend>
			<?php echo $this->loadTemplate('metadata'); ?>
		</fieldset>
		
		<?php echo JHtml::_('sliders.panel',JText::_('CATEGORIES_FIELDSET_RULES'), 'access-rules'); ?>
		<fieldset class="panelform">
			<legend class="element-invisible"><?php echo JText::_('CATEGORIES_FIELDSET_RULES'); ?></legend>
			<?php // echo $this->form->getLabel('rules'); ?>
			<?php echo $this->form->getInput('rules'); ?>
		</fieldset>
		
		<?php echo JHtml::_('sliders.end'); ?>	
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<div class="clr"></div>
</div>
