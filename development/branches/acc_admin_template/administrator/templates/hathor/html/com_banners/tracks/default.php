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
JHtml::_('behavior.modal', 'a.modal');
$user	= JFactory::getUser();
$userId	= $user->get('id');
?>
<form action="<?php echo JRoute::_('index.php?option=com_banners&view=tracks'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></legend>
		<div class="filter-search">

			<label class="filter-hide-lbl" for="filter_begin"><?php echo JText::_('BANNERS_BEGIN_LABEL'); ?></label>
			<?php echo JHtml::_('calendar', $this->state->get('filter.begin'), 'filter_begin','filter_begin','%Y-%m-%d' , array('size'=>10,'onchange'=>'this.form.submit()'));?>

			<label class="filter-hide-lbl" for="filter_end"><?php echo JText::_('BANNERS_END_LABEL'); ?></label>
			<?php echo JHtml::_('calendar', $this->state->get('filter.end'), 'filter_end', 'filter_end','%Y-%m-%d' ,array('size'=>10,'onchange'=>'this.form.submit()'));?>

		</div>
		<div class="filter-select">
			<label class="selectlabel" for="filter_type">
				<?php echo JText::_('BANNERS_SELECT_TYPE'); ?>
			</label>
			<select name="filter_type" id="filter_type" class="inputbox">
				<?php echo JHtml::_('select.options', array(JHtml::_('select.option', '0', JText::_('BANNERS_SELECT_TYPE')), JHtml::_('select.option', 1, JText::_('Banners_Impression')), JHtml::_('select.option', 2, JText::_('Banners_Click'))), 'value', 'text', $this->state->get('filter.type'));?>
			</select>

			<label class="selectlabel" for="filter_category_id">
				<?php echo JText::_('JOPTION_SELECT_CATEGORY'); ?>
			</label>
			<?php $category = $this->state->get('filter.category_id');?>
			<select name="filter_category_id" id="filter_category_id" class="inputbox">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<option value="0"<?php if($category==='0') echo ' selected="selected"';?>><?php echo JText::_('JOPTION_NO_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_banners'), 'value', 'text', $category);?>
			</select>

			<label class="selectlabel" for="filter_client_id">
				<?php echo JText::_('BANNERS_SELECT_CLIENT'); ?>
			</label>
			<select name="filter_client_id" id="filter_client_id" class="inputbox">
				<option value=""><?php echo JText::_('BANNERS_SELECT_CLIENT');?></option>
				<?php echo JHtml::_('select.options', JFormFieldBannerClient::getOptions(), 'value', 'text', $this->state->get('filter.client_id'));?>
			</select>
			
			<button type="button" id="filter-go" onclick="this.form.submit();">
				<?php echo JText::_('GO'); ?></button>
			
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'BANNERS_HEADING_NAME', 'name', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap width-20">
					<?php echo JHtml::_('grid.sort', 'BANNERS_HEADING_CLIENT', 'client_name', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="width-20">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_CATEGORY', 'category_title', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap width-10">
					<?php echo JHtml::_('grid.sort', 'BANNERS_HEADING_TYPE', 'track_type', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap width-10">
					<?php echo JHtml::_('grid.sort', 'BANNERS_HEADING_COUNT', 'count', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap width-10">
					<?php echo JHtml::_('grid.sort', 'BANNERS_HEADING_DATE', 'track_date', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
		<?php foreach ($this->items as $i => $item) :?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<?php echo $item->name;?>
				</td>
				<td>
					<?php echo $item->client_name;?>
				</td>
				<td>
					<?php echo $item->category_title;?>
				</td>
				<td>
					<?php echo $item->track_type==1 ? JText::_('BANNERS_IMPRESSION'): JText::_('BANNER_CLICK');?>
				</td>
				<td>
					<?php echo $item->count;?>
				</td>
				<td>
					<?php echo $item->track_date;?>
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
