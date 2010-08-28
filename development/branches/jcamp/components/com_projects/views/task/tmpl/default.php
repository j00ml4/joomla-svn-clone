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
<div class="project-item <?php echo $pageClass?>">
<form action="<?php echo ProjectsHelper::getLink('form'); ?>" method="post" id="adminForm" name="adminForm">
	<dl class="">
	<?php if(!empty($this->item->estimate)): ?>
		<dt><?php echo JText::_('COM_PROJECTS_TASK_CELL_ESTIMATE'); ?></dt>
		<dd><?php echo $this->item->estimate; ?></dd>
	<?php endif; ?>
	
	<?php if(!empty($this->item->category_title)): ?>
		<dt><?php echo JText::_('JCATEGORY'); ?></dt>
		<dd><?php echo $this->item->category_title; ?></dd>
	<?php endif; ?>		
	</dl>
	
	<div class="item-page">
		<div class="item">
			<?php echo $this->item->description; ?>
		</div>
	</div>
	
	<?php if(count($this->items)): ?>
	<fieldset>
		<legend><?php echo JText::sprintf('COM_PROJECTS_TASKS_LIST_TASKS_TITLE', $this->item->title); ?></legend>
		<?php echo $this->loadTemplate('table'); ?>
	</fieldset>
	<?php endif; ?>
		
	
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
    <input type="hidden" name="boxchecked" value="1" />     
    <?php echo JHTML::_('form.token'); ?>
</form>		
</div>