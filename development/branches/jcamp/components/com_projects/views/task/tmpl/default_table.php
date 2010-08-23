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
				<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDir, $listOrder); ?>
			</th>
			<th width="5%">
				<?php echo JHtml::_('grid.sort',  'JGLOBAL_STATE', 'a.`state`', $listDir, $listOrder); ?>
			</th>
			<th width="20%">
				<?php echo JHtml::_('grid.sort',  'JGLOBAL_FIELD_CREATED_BY_ALIAS_LABEL', '`created_by_alias`', $listDir, $listOrder); ?>
			</th>
		</tr>
	</thead>
	
	<tbody>
		<?php foreach ($this->items as $i => $item): ?>
		<tr class="cat-list-row<?php echo $i % 2; ?> state-<?php echo $item->state; ?>">
			<td> 
				<?php echo JHtml::_('grid.id', $i, $item->id); ?>
			</td>
			<td class="state-<?php echo $item->state; ?>">
				<span class="padding"><?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $item->level-1); ?></span>
				<a href="<?php echo ProjectsHelper::getLink('task.'.$this->type, $item->id); ?>">
					<?php echo $item->title; ?></a>	
			</td>

			<td class="center">
				<?php echo JHtml::_('tool.taskstate', $item->state, $i, 'tasks.') ?>
			</td>
			
			<td class="center">
				<?php if ($item->checked_out) :
					echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'categories.', $canCheckin);
				else:
					echo $item->author_name;
				endif; ?>
			</td>
			
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
