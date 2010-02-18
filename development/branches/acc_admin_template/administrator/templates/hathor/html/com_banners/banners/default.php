<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_banners
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
JHtml::_('behavior.tooltip');
JHtml::_('script', 'multiselect.js');
$user	= JFactory::getUser();
$userId	= $user->get('id');

// Get additional language strings prefixed with TPL_HATHOR
$lang =& JFactory::getLanguage();
$lang->load('tpl_hathor', JPATH_ADMINISTRATOR); 

?>
<form action="<?php echo JRoute::_('index.php?option=com_banners&view=banners'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>:</label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::_('Banners_Search_in_title'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select">
			
			<label class="selectlabel" for="filter_state">
				<?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?>
			</label>
			<select name="filter_state" id="filter_state" class="inputbox">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);?>
			</select>
			
			<label class="selectlabel" for="filter_category_id">
				<?php echo JText::_('JOPTION_SELECT_CATEGORY'); ?>
			</label>
			<select name="filter_category_id" id="filter_category_id" class="inputbox">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<option value="0"><?php echo JText::_('JOPTION_NO_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_banners'), 'value', 'text', $this->state->get('filter.category_id'));?>
			</select>
			
			<label class="selectlabel" for="filter_client_id">
				<?php echo JText::_('BANNERS_SELECT_CLIENT'); ?>
			</label>
			<select name="filter_client_id" id="filter_client_id" class="inputbox">
				<option value=""><?php echo JText::_('BANNERS_SELECT_CLIENT');?></option>
				<?php echo JHtml::_('select.options', JFormFieldBannerClient::getOptions(), 'value', 'text', $this->state->get('filter.client_id'));?>
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
					<?php echo JHtml::_('grid.sort',  'BANNERS_HEADING_NAME', 'name', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap width-10">
					<?php echo JHtml::_('grid.sort', 'BANNERS_HEADING_CLIENT', 'client_name', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap state-col">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_PUBLISHED', 'state', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap title category-col">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_CATEGORY', 'category_title', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap ordering-col">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'ordering', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
					<?php if ($this->state->get('list.ordering')=='ordering'): ?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'banners.saveorder'); ?>
					<?php endif;?>
				</th>
				<th class="nowrap width-5">
					<?php echo JHtml::_('grid.sort', 'BANNERS_HEADING_STICKY', 'sticky', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap width-5">
					<?php echo JHtml::_('grid.sort', 'BANNERS_HEADING_IMPRESSIONS', 'impmade', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap width-10">
					<?php echo JHtml::_('grid.sort', 'BANNERS_HEADING_CLICKS', 'clicks', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap width-5">
					<?php echo JText::_('BANNERS_HEADING_METAKEYWORDS'); ?>
				</th>
				<th class="width-10">
					<?php echo JText::_('BANNERS_HEADING_PURCHASETYPE'); ?>
				</th>
				<th class="nowrap id-col">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'id', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($this->state->get('list.ordering') == 'ordering');
			$item->cat_link = JRoute::_('index.php?option=com_categories&extension=com_banners&task=edit&type=other&cid[]='. $item->catid);
			$canCreate	= $user->authorise('core.create',		'com_banners.category.'.$item->catid);
			$canEdit	= $user->authorise('core.edit',			'com_banners.category.'.$item->catid);
			$canChange	= $user->authorise('core.edit.state',	'com_banners.category.'.$item->catid);
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<?php if ($item->checked_out) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $item->editor, $item->checked_out_time); ?>
					<?php endif; ?>
					<?php if ($canCreate || $canEdit) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_banners&task=banner.edit&id='.(int) $item->id); ?>">
							<?php echo $this->escape($item->name); ?></a>
					<?php else : ?>
							<?php echo $this->escape($item->name); ?>
					<?php endif; ?>
					<p class="smallsub">
						(<span><?php echo JText::_('JFIELD_ALIAS_LABEL'); ?>:</span> <?php echo $this->escape($item->alias);?>)</p>
				</td>
				<td class="center">
					<?php echo $item->client_name;?>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->state, $i, 'banners.', $canChange);?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->category_title); ?>
				</td>
				<td class="order">
					<?php if ($item->state >=0):?>
						<?php if ($canChange && $this->state->get('list.ordering')=='ordering') : ?>
							<span><?php echo $this->pagination->orderUpIcon($i, $this->state->get('list.direction')=='asc' ? $item->ordering!=1 : $item->ordering!=$this->categories[$item->catid]->max, $this->state->get('list.direction')=='asc' ? 'banners.orderup' : 'banners.orderdown', 'JGrid_Move_Up', $ordering); ?></span>
							<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, $this->state->get('list.direction')!='asc' ? $item->ordering!=1 : $item->ordering!=$this->categories[$item->catid]->max, $this->state->get('list.direction')=='asc' ? 'banners.orderdown' : 'banners.orderup', 'JGrid_Move_Down', $ordering); ?></span>
							<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
							<input type="text" name="order[]" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" title="<?php echo $item->name; ?> order" />
						<?php else : ?>
							<?php echo $item->ordering; ?>
						<?php endif; ?>
					<?php endif;?>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->sticky, $i, 'banners.sticky_', $canChange);?>
				</td>
				<td class="center">
					<?php echo JText::sprintf('BANNERS_IMPRESSIONS', $item->impmade, $item->imptotal ? $item->imptotal : JText::_('BANNERS_UNLIMITED'));?>
				</td>
				<td class="center">
					<?php echo $item->clicks;?> -
					<?php echo sprintf('%.2f%%', $item->impmade ? 100 * $item->clicks/$item->impmade : 0);?>
				</td>
				<td>
					<?php echo $item->metakey; ?>
				</td>
				<td class="center">
					<?php if ($item->purchase_type<0):?>
						<?php echo JText::sprintf('BANNERS_DEFAULT',($item->client_purchase_type>0) ? JText::_('BANNERS_'.$item->client_purchase_type) : JText::_('BANNERS_'.$this->params->get('purchase_type')));?>
					<?php else:?>
						<?php echo JText::_('BANNERS_'.$item->purchase_type);?>
					<?php endif;?>
				</td>
				<td class="center">
					<?php echo $item->id; ?>
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
