<?php
/**
 * @version		$Id: default.php 14276 2010-01-18 14:20:28Z louis $
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');

$user = JFactory::getUser();
?>
<form action="<?php echo JRoute::_('index.php?option=com_users&view=groups');?>" method="post" name="adminForm">
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('Filters'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::sprintf('JSearch_Label', 'Users'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::sprintf('Users_Search_in_title', 'Groups'); ?>" />
			<button type="submit"><?php echo JText::_('JSearch_Filter_Submit'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSearch_Filter_Clear'); ?></button>
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th class="checkmark-col">
					<input type="checkbox" name="toggle" value="" title="<?php echo JText::_('Checkmark_All'); ?>" onclick="checkAll(this)" />
				</th>
				<th class="title">
					<?php echo JText::_('Users_Heading_Group_Title'); ?>
				</th>
				<th class="width-10">
					<?php echo JText::_('Users_Heading_Users_in_group'); ?>
				</th>
				<th class="nowrap id-col">
					<?php echo JText::_('JGrid_Heading_ID'); ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$canCreate	= $user->authorise('core.create',		'com_users');
			$canEdit	= $user->authorise('core.edit',			'com_users');
			$canChange	= $user->authorise('core.edit.state',	'com_users');
		?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
<!-- TO DO: UI system for representing levels and parent-child relationships <span class="gi">|&mdash;</span> -->
				<td class="indent-<?php echo intval(($item->level)*15)+4; ?>">
					<?php if ($canCreate || $canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_users&task=group.edit&id='.$item->id);?>">
						<?php echo $this->escape($item->title); ?></a>
					<?php else : ?>
						<?php echo $this->escape($item->title); ?>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo $item->user_count ? $item->user_count : ''; ?>
				</td>
				<td class="center">
					<?php echo (int) $item->id; ?>
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
