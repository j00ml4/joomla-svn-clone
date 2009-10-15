<?php
/**
 * @version		$Id: default_batch.php 13031 2009-10-02 21:54:22Z louis $
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$options = array(
	JHtml::_('select.option', 'c', JText::_('Menus_Batch_Copy')),
	JHtml::_('select.option', 'm', JText::_('Menus_Batch_Move'))
);
$published = $this->state->get('filter.published');
?>
	<fieldset class="batch">

			<legend><?php echo JText::_('Menus_Batch_Options');?></legend>

			<label for="batch_access">
				<?php echo JText::_('Menus_Batch_Access_Label'); ?>
			</label>
			<?php echo JHtml::_('access.assetgrouplist', 'batch[assetgroup_id]', '', 'class="inputbox"', array('title' => '', 'id' => 'batch_access'));?>

		<?php if (is_numeric($published)) : ?>

			<label for="batch_access">
				<?php echo JText::_('Menus_Batch_Menu_Label'); ?>
			</label>
			<select name="batch[menu_id]" class="inputbox" id="batch_menu_id">
				<option></option>
				<?php echo JHtml::_('select.options', JHtml::_('menu.menuitems', array('published' => $published)));?>
			</select>
			<?php echo JHTML::_( 'select.radiolist', $options, 'batch[move_copy]', '', 'value', 'text', 'm'); ?>

		<?php endif; ?>

		<button type="submit" onclick="submitbutton('item.batch');">
			<?php echo JText::_('Menus_Batch_Process'); ?></button>

	</fieldset>
