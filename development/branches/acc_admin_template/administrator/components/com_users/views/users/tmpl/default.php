<?php
/**
 * @version		$Id$
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

$canDo = UsersHelper::getActions();
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>

<form action="<?php echo JRoute::_('index.php?option=com_users&view=users');?>" method="post" name="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::sprintf('JSearch_Filter_Label', 'Users'); ?>:</label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::sprintf('JSearch_Title', 'Users'); ?>" />
			<button type="submit"><?php echo JText::_('JSearch_Submit'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSearch_Reset'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<label for="filter_state">
				<?php echo JText::sprintf('Users_Filter_Label', 'Users'); ?>
			</label>

			<select name="filter_state" class="inputbox" onchange="this.form.submit()">
				<option value="*"><?php echo JText::_('Users_Filter_State');?></option>
				<?php echo JHtml::_('select.options', UsersHelper::getStateOptions(), 'value', 'text', $this->state->get('filter.state'));?>
			</select>

			<select name="filter_active" class="inputbox" onchange="this.form.submit()">
				<option value="*"><?php echo JText::_('Users_Filter_Active');?></option>
				<?php echo JHtml::_('select.options', UsersHelper::getActiveOptions(), 'value', 'text', $this->state->get('filter.active'));?>
			</select>

			<select name="filter_group_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('Users_Filter_Usergroup');?></option>
				<?php echo JHtml::_('select.options', UsersHelper::getGroups(), 'value', 'text', $this->state->get('filter.group_id'));?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items);?>)" />
				</th>
				<th class="left">
					<?php echo JHtml::_('grid.sort', 'Users_Heading_Name', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'Users_Heading_Username', 'a.username', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="5%">
					<?php echo JHtml::_('grid.sort', 'JENABLED', 'a.block', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="5%">
					<?php echo JHtml::_('grid.sort', 'Users_Heading_Activated', 'a.activation', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JText::_('Users_Heading_Groups'); ?>
				</th>
				<th class="nowrap" width="15%">
					<?php echo JHtml::_('grid.sort', 'Users_Heading_Email', 'a.email', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="15%">
					<?php echo JHtml::_('grid.sort', 'Users_Heading_Last_Visit_Date', 'a.lastvisitDate', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="15%">
					<?php echo JHtml::_('grid.sort', 'Users_Heading_Registration_Date', 'a.registerDate', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="3%">
					<?php echo JHtml::_('grid.sort', 'JGrid_Heading_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<?php if ($canDo->get('core.edit')) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.$item->id); ?>" title="<?php echo JText::sprintf('Users_Edit_User', $item->name); ?>">
						<?php echo $this->escape($item->name); ?></a>
					<?php else : ?>
						<?php echo $this->escape($item->name); ?>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->username); ?>
				</td>
				<td class="center">
					<?php echo JHtml::_('grid.boolean', $i, !$item->block, 'users.unblock', 'users.block'); ?>
				</td>
				<td class="center">
					<?php echo JHtml::_('grid.boolean', $i, !$item->activation, 'users.activate', null); ?>
				</td>
				<td class="center">
					<?php echo nl2br($item->group_names); ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->email); ?>
				</td>
				<td class="center">
					<?php echo JHTML::_('date',$item->lastvisitDate); ?>
				</td>
				<td class="center">
					<?php echo JHTML::_('date',$item->registerDate); ?>
				</td>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>