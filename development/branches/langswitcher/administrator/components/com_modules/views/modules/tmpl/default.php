<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_modules
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHTML::_('script','multiselect.js');
$client = $this->state->get('filter.client_id') ? 'administrator' : 'site';
$user = JFactory::getUser();
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>
<form action="<?php echo JRoute::_('index.php?option=com_modules'); ?>" method="post" name="adminForm" id="adminForm">
	<?php echo $this->loadTemplate('filters');?>
	<table class="adminlist" id="modules-mgr">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(this)" />
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_TITLE', 'title', $listDirn, $listOrder); ?>
				</th>
				<th width="20%">
					<?php echo JHtml::_('grid.sort',  'COM_MODULES_HEADING_POSITION', 'position', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort',  'COM_MODULES_HEADING_PAGES', 'pages', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_MODULES_HEADING_MODULE', 'name', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'published', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" nowrap="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'ordering', $listDirn, $listOrder); ?>
					<?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'modules.saveorder'); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'access', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" nowrap="nowrap">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'ordering');
			$canCreate	= $user->authorise('core.create',		'com_modules');
			$canEdit	= $user->authorise('core.edit',			'com_modules');
			$canChange	= $user->authorise('core.edit.state',	'com_modules');
		?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<?php if ($item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $item->editor, $item->checked_out_time); ?>
					<?php endif; ?>
					<?php if ($canCreate || $canEdit) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_modules&task=module.edit&id='.(int) $item->id); ?>">
							<?php echo $this->escape($item->title); ?></a>
					<?php else : ?>
							<?php echo $this->escape($item->title); ?>
					<?php endif; ?>
					<p class="smallsub">
					<?php if (!empty($item->note)) : ?>
						(<span><?php echo JText::_('JFIELD_NOTE_LABEL'); ?>:</span> <?php echo $this->escape($item->note); ?>)</p>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo $item->position; ?>
				</td>
				<td class="center">
					<?php
						if (is_null($item->pages)) {
							echo JText::_('COM_MODULES_ASSIGNED_NONE');
						} else if ($item->pages < 0) {
							echo JText::_('COM_MODULES_ASSIGNED_VARIES_EXCEPT');
						} else if ($item->pages > 0) {
							echo JText::_('COM_MODULES_ASSIGNED_VARIES_ONLY');
						} else {
							echo JText::_('COM_MODULES_ASSIGNED_ALL');
						}
					?>
				</td>
				<td class="left">
					<?php echo $item->name;?>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'modules.', $canChange);?>
				</td>
				<td class="order">
					<?php if ($canChange) : ?>
						<span><?php echo $this->pagination->orderUpIcon($i, (@$this->items[$i-1]->position == $item->position), 'modules.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (@$this->items[$i+1]->position == $item->position), 'modules.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
						<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
					<?php else : ?>
						<?php echo $item->ordering; ?>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->access_level); ?>
				</td>
				<td class="center">
					<?php if ($item->language==''):?>
						<?php echo JText::_('JDEFAULT'); ?>
					<?php elseif ($item->language=='*'):?>
						<?php echo JText::_('JALL'); ?>
					<?php else:?>
						<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
					<?php endif;?>
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
