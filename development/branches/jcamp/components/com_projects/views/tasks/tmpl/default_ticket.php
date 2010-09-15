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
				<?php echo JHtml::_('grid.sort',  'JGLOBAL_TITLE', 'a.`title`', $listDir, $listOrder); ?>
			</th>
			<th width="20%">
				<?php echo JHtml::_('grid.sort',  'JGLOBAL_FIELD_CREATED_BY_ALIAS_LABEL', '`created_by_alias`', $listDir, $listOrder); ?>
			</th>
			<th width="5%">
				<?php echo JHtml::_('grid.sort',  'JGLOBAL_STATE', 'a.`state`', $listDir, $listOrder); ?>
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
				<a class="state-<?php echo $item->state; ?>" href="<?php echo ProjectsHelper::getLink($this->type, $item->id); ?>">
					<?php echo $item->title; ?></a>	
				<?php if(!empty($item->category_title)): ?>
				<span class="category">
					(<?php echo $item->category_title; ?>)
				</span>
				<?php endif; ?>	
			</td>

			<td>
				<?php echo $item->author; ?>
			</td>
			
			<td>
				<?php echo JHtml::_('tool.published', $item->state, $i, $this->type); ?>
			</td>
						
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
