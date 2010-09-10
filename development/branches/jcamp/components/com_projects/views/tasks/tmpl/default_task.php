<?php
/**
 * @version		$Id:
 * @package		Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHTML::_('script','system/multiselect.js',false,true);

$ordering	= true;
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $this->canDo->get('core.edit.state');
$listOrder	= $this->state->get('list.ordering');
$listDir	= $this->state->get('list.direction');
$canChange  = $this->canDo->get($this->type.'.edit');
?>
<table class="todo-table category">
	<thead>
		<tr>
			<th width="1%">
				<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
			</th>
			<th>
				<?php echo JText::_('COM_PROJECTS_TASK'); ?>
			</th>

			<th width="5%">
				<?php echo JText::_('JGLOBAL_STATE'); ?>
				<?php //echo JHtml::_('grid.sort',  'JGLOBAL_STATE', 'a.`state`', $listDir, $listOrder); ?>
			</th>	
		</tr>
	</thead>
	
	<tbody>
		<?php foreach ($this->items as $i => $item): ?>
		<tr class="cat-list-row<?php echo $i % 2; ?>">
			<td> 
				<?php echo JHtml::_('grid.id', $i, $item->id); ?>
			</td>
			<td>
				<div style="padding-left:<?php echo (($item->level - 1) * 3); ?>em;">
					<a class="state-<?php echo $item->state; ?>" href="<?php echo ProjectsHelper::getLink($this->type, $item->id); ?>">
						<?php echo $item->title; ?></a>	
					<?php if(!empty($item->category_title)): ?>
					<span class="category">
						(<?php echo $item->category_title; ?>)
					</span>
					<?php endif; ?>
					<!-- p class="smallsub" title="<?php echo $this->escape($item->title);?>">
						<?php echo str_repeat('<span class="gtr">|&mdash;</span>', $item->level-1) ?>
						<dl></dl>
						
					</p-->
				</div>	
			</td>

				<?php /*if ($item->checked_out) :
					echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'categories.', $canChange);
				else:
					echo $item->author_name;
				endif; */ ?>
			
			<td>
				<?php echo JHtml::_('tool.published', $item->state, $i, $this->type); ?>
			</td>
				
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
