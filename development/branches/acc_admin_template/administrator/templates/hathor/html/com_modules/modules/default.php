<?php
/**
 * @version		$Id: default.php 14690 2010-02-06 05:19:14Z infograf768 $
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
JHtml::_('script','multiselect.js');
$client = $this->state->get('filter.client_id') ? 'administrator' : 'site';
$user = JFactory::getUser();
?>
<form action="<?php echo JRoute::_('index.php?option=com_modules'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::_('COM_MODULES_MODULES_FILTER_SEARCH_DESC'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select">
			<label class="selectlabel" for="filter_client_id">
				<?php echo JText::_('Filter_Client'); ?>
			</label>
			<select name="filter_client_id" id="filter_client_id" class="inputbox">
				<?php echo JHtml::_('select.options', ModulesHelper::getClientOptions(), 'value', 'text', $this->state->get('filter.client_id'));?>
			</select>
			
			<label class="selectlabel" for="filter_state">
				<?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?>
			</label>
			<select name="filter_state" id="filter_state" class="inputbox">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', ModulesHelper::getStateOptions(), 'value', 'text', $this->state->get('filter.state'));?>
			</select>
			
			<label class="selectlabel" for="filter_access">
				<?php echo JText::_('JOPTION_SELECT_ACCESS'); ?>
			</label>
			<select name="filter_access" id="filter_access" class="inputbox">
				<option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'));?>
			</select>
			
			<label class="selectlabel" for="filter_position">
				<?php echo JText::_('COM_MODULES_OPTION_SELECT_POSITION'); ?>
			</label>
			<select name="filter_position" id="filter_position" class="inputbox">
				<option value=""><?php echo JText::_('COM_MODULES_OPTION_SELECT_POSITION');?></option>
				<?php echo JHtml::_('select.options', ModulesHelper::getPositions($this->state->get('filter.client_id')), 'value', 'text', $this->state->get('filter.position'));?>
			</select>
			
			<label class="selectlabel" for="filter_module">
				<?php echo JText::_('COM_MODULES_OPTION_SELECT_MODULE'); ?>
			</label>
			<select name="filter_module" id="filter_module" class="inputbox">
				<option value=""><?php echo JText::_('COM_MODULES_OPTION_SELECT_MODULE');?></option>
				<?php echo JHtml::_('select.options', ModulesHelper::getModules($this->state->get('filter.client_id')), 'value', 'text', $this->state->get('filter.module'));?>
			</select>
			
			<button type="button" id="filter-go" onclick="this.form.submit();">
				<?php echo JText::_('Go'); ?></button>
			
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist" id="modules-mgr">
		<thead>
			<tr>
				<th class="checkmark-col">
					<input type="checkbox" name="toggle" value="" title="<?php echo JText::_('TPL_HATHOR_CHECKMARK_ALL'); ?>" onclick="checkAll(this)" />
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_TITLE', 'title', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="width-20">
					<?php echo JHtml::_('grid.sort',  'COM_MODULES_HEADING_POSITION', 'position', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="width-10">
					<?php echo JHtml::_('grid.sort',  'COM_MODULES_HEADING_PAGES', 'pages', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="width-10">
					<?php echo JHtml::_('grid.sort', 'COM_MODULES_HEADING_MODULE', 'name', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="width-5">
					<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'published', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap ordering-col">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'ordering', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
					<?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'modules.saveorder'); ?>
				</th>
				<th class="title access-col">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'access', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="width-5">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="nowrap id-col">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'id', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($this->state->get('list.ordering') == 'a.ordering');
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
						<span><?php echo $this->pagination->orderUpIcon($i, true, 'modules.orderup', 'JGRID_MOVE_UP', $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'modules.orderdown', 'JGRID_MOVE_DOWN', $ordering); ?></span>
						<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" title="<?php echo $item->title; ?> order" />
					<?php else : ?>
						<?php echo $item->ordering; ?>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->access_level); ?>
				</td>
				<td class="center">
					<?php echo $item->language ? $this->escape($item->language) : JText::_('JDEFAULT'); ?>
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