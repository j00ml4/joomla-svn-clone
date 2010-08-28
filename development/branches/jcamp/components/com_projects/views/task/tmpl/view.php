<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;

// Vars
$params =  $this->params;
?>
<div class="project-item">
	<div class="projects-content">
		<?php if ($params->get('show_page_heading', 1)) : ?> 
		<h1 class="componentheading">
			<?php echo JText::sprintf('COM_PROJECTS_TASK_VIEW_'.$this->prefix.'_TITLE',$this->item->title); ?>
		</h1>
		<?php endif; ?>
		<table style="width : 100%;">
			<tr>
				<td><?php echo JText::_('COM_PROJECTS_TYPE_'.$this->prefix);?>:</td>
				<td>#<?php echo $this->item->id;?></td>
				<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_'.$this->prefix.'_TITLE');?>:</td>
				<td><?php echo $this->item->title;?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_CREATED_BY');?>:</td>
				<td><?php echo $this->item->created_by;?></td>
				<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_CREATED_WHEN');?>:</td>
				<td><?php echo JHTML::_('date', $this->item->created, JText::_('COM_PROJECTS_DATE_FORMAT')); ?></td>			
			</tr>
			<?php if($this->item->modified_by):?>
				<tr>
					<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_MODIFIED_BY');?>:</td>
					<td><?php echo $this->item->modified_by;?></td>
					<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_MODIFIED_WHEN');?>:</td>
					<td><?php echo JHTML::_('date', $this->item->modified, JText::_('COM_PROJECTS_DATE_FORMAT'));?></td>			
				</tr>
			<?php endif;?>
			<tr>
				<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_CATEGORY_'.$this->prefix);?>:</td>
				<td><?php echo $this->item->category_title;?></td>
				<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_ESTIMATE');?>:</td>
				<td><?php echo $this->item->estimate;?></td>			
			</tr>
			<tr>
				<td><?php echo JText::_('COM_PROJECTS_FIELD_START_AT_TASK_LABEL');?>:</td>
				<td><?php echo JHTML::_('date', $this->item->start_at, JText::_('DATE_FORMAT_LC3'));?></td>
			
				<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_FINISH_AT');?>:</td>
				<td><?php echo JHTML::_('date', $this->item->finish_at, JText::_('DATE_FORMAT_LC3'));?></td>
			</tr>
			<?php if($this->item->finished):?>
				<tr>
					<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_FINISHED_WHEN');?>:</td>
					<td><?php echo JHTML::_('date', $this->item->finished, JText::_('COM_PROJECTS_DATE_FORMAT'));?></td>
					<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_FINISHED_BY');?>:</td>
					<td><?php echo $this->item->finished_by;?></td>			
				</tr>
			<?php endif;?>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4"><?php echo JText::_('COM_PROJECTS_TASK_CELL_DESCRIPTION');?>:</td>
			</tr>
			<tr>
				<td colspan="4"><?php echo $this->item->description;?></td>
			</tr>
		</table>	
	</div>
</div>