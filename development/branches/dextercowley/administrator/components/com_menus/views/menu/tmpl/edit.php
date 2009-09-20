<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<script type="text/javascript">
<!--
	function submitbutton(task)
	{
		if (task == 'menu.cancel' || document.formvalidator.isValid($('item-form'))) {
			submitform(task);
		}
	}
// -->
</script>

<form action="<?php JRoute::_('index.php?option=com_menus'); ?>" method="post" name="adminForm" id="item-form">
	<fieldset style="width:45%;float:left">
		<legend><?php echo JText::_('Menus_Menu_Details');?></legend>

			<div>
				<?php echo $this->form->getLabel('menutype'); ?><br />
				<?php echo $this->form->getInput('menutype'); ?>
			</div>
			<div>
				<?php echo $this->form->getLabel('title'); ?><br />
				<?php echo $this->form->getInput('title'); ?>
			</div>
			<div>
				<?php echo $this->form->getLabel('description'); ?><br />
				<?php echo $this->form->getInput('description'); ?>
			</div>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<div class="clr"></div>
