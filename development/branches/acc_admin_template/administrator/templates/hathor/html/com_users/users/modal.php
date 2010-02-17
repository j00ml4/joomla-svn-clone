<?php
/**
 * @version		$Id: modal.php 14276 2010-01-18 14:20:28Z louis $
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
JHtml::_('behavior.tooltip');
$function = 'jSelectUser_'.JRequest::getVar('field');
?>
<form action="<?php echo JRoute::_('index.php?option=com_users&view=users');?>" method="post" name="adminForm">
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('Filters'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSearch_Filter'); ?>:</label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::_('Users_Search_in_name'); ?>" />
			<button type="submit"><?php echo JText::_('JSearch_Filter_Submit'); ?></button>
			<button type="button" onclick="document.id('search').value='';this.form.submit();"><?php echo JText::_('JSearch_Filter_Clear'); ?></button>
		</div>
		<div class="filter-select">
			<label for="filter_group_id">
				<?php echo JText::_('Users_Filter_User_Group'); ?>
			</label>
			<?php echo JHtml::_('access.usergroup', 'filter_group_id', $this->state->get('filter.group_id')); ?>
			
			<button type="button" id="filter-go" onclick="this.form.submit();">
				<?php echo JText::_('Go'); ?></button>
			
		</div>
	</fieldset>

	<table class="adminlist">
		<thead>
			<tr>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'Users_Heading_Name', 'a.name', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap width=25">
					<?php echo JHtml::_('grid.sort', 'Users_Heading_UserName', 'a.username', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap width=25">
					<?php echo JHtml::_('grid.sort', 'Users_Heading_Groups', 'a.group_names', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
		<?php
			$i = 0;
			foreach ($this->items as $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<a class="pointer" onclick="if (window.parent) window.parent.<?php echo $function;?>('<?php echo $item->id; ?>', '<?php echo $this->escape($item->name); ?>');">
						<?php echo $item->name; ?></a>
				</td>
				<td class="center">
					<?php echo $item->username; ?>
				</td>
				<td class="title">
					<?php echo nl2br($item->group_names); ?>
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
