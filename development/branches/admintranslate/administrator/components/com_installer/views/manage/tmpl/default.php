<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<form action="<?php echo JRoute::_('index.php?option=com_installer&view=manage');?>" method="post" name="adminForm">
	<?php if ($this->showMessage) : ?>
		<?php echo $this->loadTemplate('message'); ?>
	<?php endif; ?>

	<?php if ($this->ftp) : ?>
		<?php echo $this->loadTemplate('ftp'); ?>
	<?php endif; ?>

	<?php echo $this->loadTemplate('filter'); ?>

	<?php if (count($this->items)) : ?>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="10"><?php echo JText::_('INSTALLER_HEADING_MANAGE_NUM'); ?></th>
				<th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(this)" /></th>
				<th class="nowrap"><?php echo JHTML::_('grid.sort', 'INSTALLER_HEADING_MANAGE_NAME', 'name', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?></th>
				<th ><?php echo JHTML::_('grid.sort', 'INSTALLER_HEADING_MANAGE_TYPE', 'type', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?></th>
				<th width="10%" class="center"><?php echo JHTML::_('grid.sort', 'INSTALLER_HEADING_MANAGE_ENABLED', 'enabled', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?></th>
				<th width="10%" class="center"><?php echo JText::_('INSTALLER_HEADING_MANAGE_VERSION'); ?></th>
				<th width="10%"><?php echo JText::_('INSTALLER_HEADING_MANAGE_DATE'); ?></th>
				<th><?php echo JHTML::_('grid.sort', 'INSTALLER_HEADING_MANAGE_FOLDER', 'folder', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?></th>
				<th><?php echo JHTML::_('grid.sort', 'INSTALLER_HEADING_MANAGE_CLIENT', 'client_id', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?></th>
				<th width="15%"><?php echo JText::_('INSTALLER_HEADING_MANAGE_AUTHOR'); ?></th>
				<th width="10"><?php echo JHTML::_('grid.sort', 'INSTALLER_HEADING_MANAGE_ID', 'extension_id', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?></th>
			</tr>
		</thead>
		<tfoot><tr><td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item): ?>
			<tr class="row<?php echo $i%2;?>" style="<?php if ($item->protected) echo 'color:#999999;';?>">
				<td><?php echo $this->pagination->getRowOffset($i); ?></td>
				<td><?php echo JHtml::_('grid.id', $i, $item->extension_id); ?></td>
				<td><span class="bold"><?php echo $item->name; ?></span></td>
				<td><?php echo JText::_('INSTALLER_' . $item->type); ?></td>
				<td class="center">
					<?php if (!$item->element) : ?>
					<strong>X</strong>
					<?php else : ?>
						<?php echo JHtml::_('jgrid.published', $item->enabled, $i, 'manage.', !$item->protected);?>
					<?php endif; ?>
				</td>
				<td class="center"><?php echo @$item->version != '' ? $item->version : '&nbsp;'; ?></td>
				<td><?php echo @$item->creationDate != '' ? $item->creationDate : '&nbsp;'; ?></td>
				<td class="center"><?php echo @$item->folder != '' ? $item->folder : JText::_('INSTALLER_NONAPPLICABLE'); ?></td>
				<td class="center"><?php echo $item->client; ?></td>
				<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_('AUTHOR_INFORMATION');?>::<?php echo $item->author_info; ?>">
						<?php echo @$item->author != '' ? $item->author : '&nbsp;'; ?>
					</span>
				</td>
				<td><?php echo $item->extension_id ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php else : ?>
	<p class="nowarning"><?php echo JText::_('INSTALLER_MSG_MANAGE_NOEXTENSION'); ?></p>
	<?php endif; ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->state->get('list.ordering'); ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('list.direction'); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
