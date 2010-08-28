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
?>
<div class="projects-content projects-frame projects-space">
    <h4><?php echo JText::_('COM_PROJECTS_TASKS_LIST_TASKS'); ?></h4>
	<?php
		$c = count($this->tasks); 
		if($c) : // list tasks ?>
		<ul class="ulList">
		<?php
			for($i = 0; $i < $c; $i++) {
				?>
				<li><a href="<?php echo ProjectsHelper::getLink('task.view.task',$this->tasks[$i]->id)?>"><?php echo $this->tasks[$i]->title?></a></li>
				<?php
			} ?>
		</ul> <?php
		else:
			echo JText::_('COM_PROJECTS_PROJECT_NO_TASK').'<br /><br />';
		endif
	?>
    <a href="<?php echo ProjectsHelper::getLink('tasks.task', $this->item->id); ?>" class="readmore">
        <?php echo JText::_('COM_PROJECTS_TASKS_LIST_TASKS_LINK'); ?></a>
</div>
