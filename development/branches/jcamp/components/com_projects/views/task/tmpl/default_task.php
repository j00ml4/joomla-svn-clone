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

echo $this->loadTemplate('header');
?>
<div class="divBox1">
	<table>

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
		<?php echo JHTML::_('tool.progressBar', $this->item->progress); ?>
		<?php echo $this->loadTemplate('table'); ?>
	</div>
</div>
<?php endif; ?>