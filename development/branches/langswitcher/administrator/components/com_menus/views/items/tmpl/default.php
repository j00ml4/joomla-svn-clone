<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
JHtml::_('behavior.tooltip');

$user	= &JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>
<?php //Set up the filter bar. ?>
<form action="<?php echo JRoute::_('index.php?option=com_menus&view=items');?>" method="post" name="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<?php foreach($this->form->getFieldSet('search') as $field): ?>
				<?php if (!$field->hidden): ?>
					<?php echo $field->label; ?>
				<?php endif; ?>
				<?php echo $field->input; ?>
			<?php endforeach; ?>
		</div>
		<div class="filter-select fltrt">
			<?php foreach($this->form->getFieldSet('select') as $field): ?>
				<?php if (!$field->hidden): ?>
					<?php echo $field->label; ?>
				<?php endif; ?>
				<?php echo $field->input; ?>
			<?php endforeach; ?>
		</div>
	</fieldset>
	<div class="clr"> </div>
<?php //Set up the grid heading. ?>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(this)" />
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.published', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGrid_Heading_Ordering', 'a.lft', $listDirn, $listOrder); ?>
					<?php echo JHtml::_('grid.order',  $this->items); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ACCESS', 'access_level', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('JGRID_HEADING_MENU_ITEM_TYPE'); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
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
		<?php // Grid layout ?>
		<tbody>
		<?php
		foreach ($this->items as $i => $item) :
			$ordering = ($listOrder == 'a.lft');
			$orderkey = array_search($item->id, $this->ordering[$item->parent_id]);
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td class="indent-<?php echo intval(($item->level-1)*15)+4; ?>">

					<?php if ($item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $item->editor, $item->checked_out_time); ?>
					<?php endif; ?>
					<a href="<?php echo JRoute::_('index.php?option=com_menus&task=item.edit&cid[]='.$item->id);?>">
						<?php echo $this->escape($item->title); ?></a>

					<?php if ($item->home == 1) : ?>
						<span><?php echo JHTML::_('image','menu/icon-16-default.png', JText::_('JDEFAULT'), array( 'class'=>'hasTip', 'title' => JText::_('JDEFAULT').'::'.($item->language_title ? $this->escape($item->language_title):JText::_('JOPTION_ALL_LANGUAGES'))), true); ?></span>
					<?php endif; ?>

					<p class="smallsub" title="<?php echo $this->escape($item->path);?>">
								(<?php echo '<span>'.JText::_('JFIELD_ALIAS_LABEL') . ':</span> ' . $this->escape($item->alias) ;?>)</p>

				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'items.');?>
				</td>
				<td class="order">
					<span><?php echo $this->pagination->orderUpIcon($i, isset($this->ordering[$item->parent_id][$orderkey - 1]), 'items.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
					<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, isset($this->ordering[$item->parent_id][$orderkey + 1]), 'items.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
					<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
					<input type="text" name="order[]" size="5" value="<?php echo $orderkey + 1;?>" <?php echo $disabled ?> class="text-area-order" />
				</td>
				<td class="center">
					<?php echo $this->escape($item->access_level); ?>
				</td>
				<td class="center nowrap">
					<span title="<?php echo isset($item->item_type_desc) ? htmlspecialchars($this->escape($item->item_type_desc), ENT_COMPAT, 'UTF-8') : ''; ?>">
						<?php echo $this->escape($item->item_type); ?></span>
				</td>
				<td class="center">
					<?php if ($item->language==''):?>
						<?php echo JText::_('JOPTION_DEFAULT_LANGUAGE'); ?>
					<?php elseif ($item->language=='*'):?>
						<?php echo JText::_('JOPTION_ALL_LANGUAGES'); ?>
					<?php else:?>
						<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
					<?php endif;?>
				</td>
				<td class="center">
					<span title="<?php echo sprintf('%d-%d', $item->lft, $item->rgt);?>">
						<?php echo (int) $item->id; ?></span>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php //Load the batch processing form. ?>
<?php $options = array(
	JHtml::_('select.option', 'c', JText::_('COM_MENUS_BATCH_COPY')),
	JHtml::_('select.option', 'm', JText::_('COM_MENUS_BATCH_MOVE'))
);
$published = (int) $this->state->get('filter.published');
?>
	<fieldset class="batch">
		<legend><?php echo JText::_('COM_MENUS_BATCH_OPTIONS');?></legend>
		<label id="batch-access-lbl" for="batch-access">
			<?php echo JText::_('COM_MENUS_BATCH_ACCESS_LABEL'); ?>
		</label>
		<?php echo JHtml::_('access.assetgrouplist', 'batch[assetgroup_id]', '', 'class="inputbox"', array('title' => '', 'id' => 'batch-access'));?>

		<?php if ($published >= 0) : ?>
			<label id="batch-choose-action-lbl" for="batch-choose-action">
				<?php echo JText::_('COM_MENUS_BATCH_MENU_LABEL'); ?>
			</label>
			<fieldset id="batch-choose-action" class="combo">
				<select name="batch[menu_id]" class="inputbox" id="batch-menu-id">
					<option></option>
					<?php echo JHtml::_('select.options', JHtml::_('menu.menuitems', array('published' => $published)));?>
				</select>
				<?php echo JHTML::_( 'select.radiolist', $options, 'batch[move_copy]', '', 'value', 'text', 'm'); ?>
			</fieldset>
		<?php endif; ?>
		<button type="submit" onclick="submitbutton('item.batch');">
			<?php echo JText::_('COM_MENUS_BATCH_PROCESS'); ?>
		</button>
	</fieldset>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
