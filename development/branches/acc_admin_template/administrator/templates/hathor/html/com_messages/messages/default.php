<?php
/**
 * @version		$Id: default.php 14276 2010-01-18 14:20:28Z louis $
 * @package		Joomla.Administrator
 * @subpackage	com_messages
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');

$user	= JFactory::getUser();
?>

<form action="<?php echo JRoute::_('index.php?option=com_messages&view=messages'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('Filters'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSearch_Filter_Label'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::_('Messages_Search_in_subject'); ?>" />
			<button type="submit"><?php echo JText::_('JSearch_Filter_Submit'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSearch_Filter_Clear'); ?></button>
		</div>
		<div class="filter-select">
			<label class="selectlabel" for="filter_state">
				<?php echo JText::_('Messages_Option_Select_State'); ?>
			</label>
			<select name="filter_state" id="filter_state" class="inputbox">
				<option value=""><?php echo JText::_('Messages_Option_Select_State');?></option>
				<?php echo JHtml::_('select.options', MessagesHelper::getStateOptions(), 'value', 'text', $this->state->get('filter.state'));?>
			</select>
			
			<button type="button" id="filter-go" onclick="this.form.submit();">
				<?php echo JText::_('Go'); ?></button>
			
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th class="checkmark-col">
					<input type="checkbox" name="toggle" value="" title="<?php echo JText::_('Checkmark_All'); ?>" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort',  'Messages_Heading_Subject', 'a.subject', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="width-5">
					<?php echo JHtml::_('grid.sort', 'Messages_Heading_Read', 'a.state', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="width-15">
					<?php echo JHtml::_('grid.sort', 'Messages_Heading_From', 'a.user_id_from', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap width-20">
					<?php echo JHtml::_('grid.sort', 'Messages_Heading_Date', 'a.date_time', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$canChange	= $user->authorise('core.edit.state', 'com_messages');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<?php echo JHtml::_('grid.id', $i, $item->message_id); ?>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_messages&view=message&message_id='.(int) $item->message_id); ?>">
						<?php echo $this->escape($item->subject); ?></a>
				</td>
				<td class="center">
					<?php echo JHtml::_('messages.state', $item->state, $i, $canChange); ?>
				</td>
				<td>
					<?php echo $item->user_from; ?>
				</td>
				<td>
					<?php echo JHtml::_('date', $item->date_time, JText::_('DATE_FORMAT_LC2')); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
	<?php echo $this->pagination->getListFooter(); ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->state->get('list.ordering'); ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('list.direction'); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>