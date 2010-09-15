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

// Vars
$params =  $this->params;
$pageClass = $this->params->get('pageclass_sfx');
?>
<div class="projects-both-sides">
	<span><?php echo JText::sprintf('COM_PROJECTS_CREATED_ON_BY',
						JHTML::_('date', $this->item->created, JText::_('DATE_FORMAT_LC1')),
						$this->item->author);?></span>
	<?php if(!empty($this->item->modified_by)): ?>
	<span>
		<?php echo JText::sprintf('COM_PROJECTS_MODIFIED_ON_BY',
						JHTML::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC1')),
						$this->item->modified_last);?>		
	</span>
	<?php endif;?>
	<?php if($this->item->category_title): ?>
	<span>
		<?php echo JText::_('JCATEGORY') .': '. $this->item->category_title; ?>
	</span>
	<?php endif; ?>
</div>
<div class="divBox1">
	<table>
		<?php  /* ?>
		<tr>
			<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_START_AT');?>:</td>
			<td>
			<?php
				echo strtotime($this->item->start_at) == 0 ?
					 JText::_('COM_PROJECTS_TASK_NOT_YET') :
				     JHTML::_('date', $this->item->start_at, JText::_('DATE_FORMAT_LC3'));
			?>
			</td>
		</tr>
		<?php */ ?>
		<tr>
			<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_FINISH_AT');?>:</td>
			<td>
			<?php
				echo strtotime($this->item->finish_at) == 0 ?
					 JText::_('COM_PROJECTS_TASK_NOT_YET') :
				     JHTML::_('date', $this->item->finish_at, JText::_('DATE_FORMAT_LC3'));
			?>
			</td>
		</tr>
		<?php /* ?>
		<tr>
			<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_ESTIMATE');?>:</td>
			<td><?php echo $this->item->estimate;?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_ASSIGNED_TO');?>:</td>
			<td>
			<?php
				echo $this->item->editor;
			?>
			</td>
		</tr>
		<?php */ ?>
	</table>
	
	<table>
		
		<tr>
			<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_STATUS_TASK');?>:</td>
			<td><?php echo JHtml::_('tool.published', $this->item->state,  $this->item->id, $this->type, false); ?></td>
		</tr>
	</table>
</div>

<?php if($this->item->description): ?>
<div class="projects-module">
	<h4><?php echo JText::_('COM_PROJECTS_TASK_CELL_DESCRIPTION');?></h4>
	<div class="projects-content">
		<?php echo $this->item->description;?>
	</div>
</div>
<?php endif; ?>

<?php if(count($this->items)): ?>
<div class="projects-module">
	<h4><?php echo JText::sprintf('COM_PROJECTS_TASKS_LIST_TASKS_TITLE', $this->item->title); ?></h4>
	<div class="projects-content">
		<?php echo $this->loadTemplate('table'); ?>
	</div>
</div>
<?php endif; ?>