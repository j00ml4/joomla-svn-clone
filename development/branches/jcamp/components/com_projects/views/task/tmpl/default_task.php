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
<div class="divCreatedBy">
	<span><?php echo JText::sprintf('COM_PROJECTS_CREATED_ON_BY',
						JHTML::_('date', $this->item->created, JText::_('COM_PROJECTS_DATE_FORMAT2')),
						$this->item->created_by);?></span>
	<?php if($this->item->modified_by): ?>
	<span>
<?php echo JText::sprintf('COM_PROJECTS_MODIFIED_ON_BY',
						JHTML::_('date', $this->item->modified, JText::_('COM_PROJECTS_DATE_FORMAT2')),
						$this->item->modified_by);?>		
	</span>
<?php endif;?>
</div>
<div class="divBox1">
	<table>
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
		<tr>
			<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_FINISHED_WHEN');?>:</td>
			<td>
			<?php
				echo strtotime($this->item->finished) == 0 ?
					 JText::_('COM_PROJECTS_TASK_NOT_YET') :
				     JHTML::_('date', $this->item->finished, JText::_('DATE_FORMAT_LC3'));
			?>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_ESTIMATE');?>:</td>
			<td><?php echo $this->item->estimate;?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_ASSIGNED_TO');?>:</td>
			<td>
			<?php
//				echo $this->item-> == 0 ?
					echo JText::_('COM_PROJECTS_TASK_NOT_YET');
//				     JHTML::_('date', $this->item->finish_at, JText::_('DATE_FORMAT_LC3'));
			?>
			</td>
		</tr>
	</table>
	
	<table>
		<tr>
			<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_CATEGORY_TASK');?>:</td>
			<td><?php echo $this->item->category_title;?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_STATUS_TASK');?>:</td>
			<td><?php echo ProjectsHelper::getStateTask($this->item->state);?></td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_PROJECTS_TASK_CELL_IMPORTANCE');?>:</td>
			<td><?php echo ProjectsHelper::getImportanceTask($this->item->ordering);?></td>
		</tr>
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
	</table>
</div>

<div class="projects-module">
	<h4><?php echo JText::_('COM_PROJECTS_TASK_CELL_DESCRIPTION');?></h4>
	<div class="projects-content">
		<?php echo $this->item->description;?>
	</div>
</div>

<?php if(count($this->items)): ?>
<div class="projects-module">
	<h4><?php echo JText::sprintf('COM_PROJECTS_TASKS_LIST_TASKS_TITLE', $this->item->title); ?></h4>
	<div class="projects-content">
		<?php echo $this->loadTemplate('table'); ?>
	</div>
</div>
<?php endif; ?>