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
	
	<tbody>
		<?php foreach ($this->items as $i => $item): ?>
		<tr class="cat-list-row<?php echo $i % 2; ?>">
			<td> 
				<?php echo JHtml::_('grid.id', $i, $item->id); ?>
			</td>
			<td>
				<?php if($this->type == 'task'): ?>
				<span class="padding"><?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ', $item->level-1); ?></span>
				<?php endif; ?>
				<a class="state-<?php echo $item->state; ?>" href="<?php echo ProjectsHelper::getLink($this->type, $item->id); ?>">
					<?php echo $item->title; ?></a>	
				<?php if(!empty($item->category_title)): ?>
				<span class="category">
					(<?php echo $item->category_title; ?>)
				</span>
				<?php endif; ?>	
			</td>

			<td>
				<?php if ($item->checked_out) :
					echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'categories.', $canChange);
				else:
					echo $item->author_name;
				endif; ?>
			</td>
			
			<td>
				<?php echo JHtml::_('tool.published', $item->state, $i, $this->type); ?>
			</td>
						
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
