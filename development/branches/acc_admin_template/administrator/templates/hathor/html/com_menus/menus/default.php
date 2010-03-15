<?php
/**
 * @version		$Id: default.php 14567 2010-02-04 07:02:10Z eddieajau $
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');

$uri	= &JFactory::getUri();
$return	= base64_encode($uri);
?>
<form action="<?php echo JRoute::_('index.php?option=com_menus&view=menus');?>" method="post" name="adminForm" id="adminForm">
	<table class="adminlist">
		<thead>
			<tr>
				<th class="checkmark-col" rowspan="2">
					<input type="checkbox" name="toggle" value="" title="<?php echo JText::_('TPL_HATHOR_CHECKMARK_ALL'); ?>" onclick="checkAll(this)" />
				</th>
				<th rowspan="2">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_TITLE', 'a.title', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th class="width-30" colspan="3">
					<?php echo JText::_('COM_MENUS_HEADING_NUMBER_MENU_ITEMSs'); ?>
				</th>
				<th class="width-20" rowspan="2">
					<?php echo JText::_('COM_MENUS_HEADING_LINKED_MODULES'); ?>
				</th>
				<th class="nowrap id-col" rowspan="2">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'a.id', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
			</tr>
			<tr>
				<th class="width-10">
					<?php echo JText::_('COM_MENUS_HEADING_PUBLISHED_ITEMS'); ?>
				</th>
				<th class="width-10">
					<?php echo JText::_('COM_MENUS_HEADING_UNPUBLISHED_ITEMS'); ?>
				</th>
				<th class="width-10">
					<?php echo JText::_('COM_MENUS_HEADING_TRASHED_ITEMS'); ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_menus&view=items&menutype='.$item->menutype) ?> ">
						<?php echo $this->escape($item->title); ?></a>
					<p class="smallsub">(<span><?php echo JText::_('COM_MENUS_MENU_MENUTYPE_LABEL') ?>:</span>
						<?php echo '<a href="'. JRoute::_('index.php?option=com_menus&task=menu.edit&cid[]='.$item->id).' title='.$this->escape($item->description).'">'.
						$this->escape($item->menutype).'</a>'; ?>)</p>
				</td>
				<td class="center btns">
					<a href="<?php echo JRoute::_('index.php?option=com_menus&view=items&menutype='.$item->menutype.'&filter_published=1');?>">
						<?php echo $item->count_published; ?></a>
				</td>
				<td class="center btns">
					<a href="<?php echo JRoute::_('index.php?option=com_menus&view=items&menutype='.$item->menutype.'&filter_published=0');?>">
						<?php echo $item->count_unpublished; ?></a>
				</td>
				<td class="center btns">
					<a href="<?php echo JRoute::_('index.php?option=com_menus&view=items&menutype='.$item->menutype.'&filter_published=2');?>">
						<?php echo $item->count_trashed; ?></a>
				</td>
				<td class="left">
					<?php
					if (isset($this->modules[$item->menutype])) :
						foreach ($this->modules[$item->menutype] as &$module) :
						?>
						<a href="<?php echo JRoute::_('index.php?option=com_modules&task=module.edit&module_id='.$module->id.'&return='.$return);?>">
							<?php echo $this->escape($module->title); ?></a>
						<p class="smallsub">(<?php echo $this->escape($module->position);?>)</p>
						<?php
						endforeach;
					endif;
					?>
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
