<?php
/**
 * @version		$Id: default.php 14857 2010-02-15 12:08:04Z infograf768 $
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
?>
<form action="<?php echo JRoute::_('index.php?option=com_menus&view=items');?>" method="post" name="adminForm">
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('Filters'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSearch_Filter_Label'); ?>:</label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::_('Menus_Items_search_filter'); ?>" />
			<button type="submit"><?php echo JText::_('JSearch_Filter_Submit'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSearch_Filter_Clear'); ?></button>
		</div>
		<div class="filter-select">
			<label class="selectlabel" for="filter_access">
				<?php echo JText::_('Filter_Access'); ?>
			</label>
			<select name="filter_access" id="filter_access" class="inputbox">
				<option value=""><?php echo JText::_('JOption_Select_Access');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'));?>
			</select>
			
			<label class="selectlabel" for="filter_published">
				<?php echo JText::_('Filter_State'); ?>
			</label> 
			<select name="filter_published" id="filter_published" class="inputbox">
				<option value=""><?php echo JText::_('JOption_Select_Published');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
			
			<label class="selectlabel" for="menutype">
				<?php echo JText::_('JMenu_Option_Select_Menu'); ?>
			</label> 
			<select name="menutype" id="menutype" class="inputbox">
				<?php echo JHtml::_('select.options', JHtml::_('menu.menus'), 'value', 'text', $this->state->get('filter.menutype'));?>
			</select>
			
			<label class="selectlabel" for="filter_level">
				<?php echo JText::_('JMenu_Option_Select_Level'); ?>
			</label>
			<select name="filter_level" id="filter_level" class="inputbox">
				<option value=""><?php echo JText::_('JMenu_Option_Select_Level');?></option>
				<?php echo JHtml::_('select.options', $this->f_levels, 'value', 'text', $this->state->get('filter.level'));?>
			</select>
			
			<label class="selectlabel" for="filter_menutype">
				<?php echo JText::_('JMenu_Option_Select_Menutype'); ?>
			</label>
			<select name="filter_menutype" id="filter_menutype" class="inputbox">
				<option value=""><?php echo JText::_('JMenu_Option_Select_Menutype');?></option>
				<?php echo JHtml::_('select.options', $this->f_levels, 'value', 'text', $this->state->get('filter.level'));?>
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
					<input type="checkbox" name="toggle" value="" title="<?php echo JText::_('TPL_HATHOR_CHECKMARK_ALL'); ?>" onclick="checkAll(this)" />
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'JGrid_Heading_Title', 'a.title', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap state-col">
					<?php echo JHtml::_('grid.sort', 'JGrid_Heading_Published', 'a.published', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap ordering-col">
					<?php echo JText::_('JGrid_Heading_Ordering'); ?>
					<?php echo JHtml::_('grid.order',  $this->items); ?>
				</th>
				<th class="title access-col">
					<?php echo JHtml::_('grid.sort',  'JGrid_Heading_Access', 'access_level', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('JGrid_Heading_Menu_Item_Type'); ?>
				</th>
				<th class="nowrap id-col">
					<?php echo JHtml::_('grid.sort',  'JGrid_Heading_ID', 'a.lft', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
		<?php
		foreach ($this->items as $i => $item) :
			$lang = &JFactory::getLanguage();
			$lang->load($item->componentname, JPATH_ADMINISTRATOR);
			$ordering = ($this->state->get('list.ordering') == 'a.lft');
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
						<span><?php echo JHTML::_('image', 'menu/icon-16-default.png', JText::_('Default'), array( 'title' => JText::_('Default')), true); ?></span>
					<?php endif; ?>

					<p class="smallsub" title="<?php echo $this->escape($item->path);?>">
								(<?php echo '<span>'.JText::_('JFIELD_ALIAS_LABEL') . ':</span> ' . $this->escape($item->alias) ;?>)</p>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'items.');?>
				</td>
				<td class="order">
					<span><?php echo $this->pagination->orderUpIcon($i, $item->order_up, 'items.orderup', 'JGrid_Move_Up', $ordering); ?></span>
					<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, $item->order_dn, 'items.orderdown', 'JGrid_Move_Down', $ordering); ?></span>
					<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
					<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" title="<?php echo $item->title; ?> order" />
				</td>
				<td class="center">
					<?php echo $this->escape($item->access_level); ?>
				</td>
				<td class="center">
						<?php if ($item->component_id=='0'){
							echo $this->escape($item->type);
							}
							else {
								echo $this->escape(JText::_($item->componentname).'» ');
							}
						;?>
				</td>
				<td class="center">
					<span title="<?php echo sprintf('%d-%d', $item->lft, $item->rgt);?>">
						<?php echo (int) $item->id; ?></span>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
	<?php echo $this->pagination->getListFooter(); ?>
	<div class="clr"> </div>

	<?php echo $this->loadTemplate('batch'); ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->state->get('list.ordering'); ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('list.direction'); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
