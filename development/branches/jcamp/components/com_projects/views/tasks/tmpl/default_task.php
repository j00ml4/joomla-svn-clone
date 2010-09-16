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
			<?php if($canChange): ?>
			<th width="1%">
				<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
			</th>
			<?php endif; ?>
			
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
			<?php if($canChange): ?>
			<td> 
				<?php echo JHtml::_('grid.id', $i, $item->id); ?>
			</td>
			<?php endif; ?>
			
			<td>
				<div style="padding-left:<?php echo (($item->level - 1) * 3); ?>em;">
					<a class="state-<?php echo $item->state; ?>" href="<?php echo ProjectsHelper::getLink($this->type, $item->id); ?>">
						<?php echo $item->title; ?></a>	
					<?php if(!empty($item->category_title)): ?>
					<span class="category">
						(<?php echo $item->category_title; ?>)
					</span>
					<?php endif; ?>
					<p class="smallsub" title="<?php echo $this->escape($item->title);?>">
					<?php 
						switch ($item->state){
							case 2:
								echo JText::sprintf('COM_PROJECTS_FINISHED_ON_BY', 
									JHTML::_('date', $item->finished, JText::_('DATE_FORMAT_LC1')),
									$item->editor);
									break;

							default:
								echo JText::sprintf('COM_PROJECTS_CREATED_ON_BY', 
									JHTML::_('date', $item->created, JText::_('DATE_FORMAT_LC1')),
									$item->author);
									break;
						}		
					?>			
					</p>
				</div>	
			</td>
			
			<td>
				<?php echo JHtml::_('tool.published', $item->state, $i, $this->type, $canChange); ?>
			</td>
				
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
