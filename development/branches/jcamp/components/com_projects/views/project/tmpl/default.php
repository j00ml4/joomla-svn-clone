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
<div class="projects<?php echo $pageClass?>">
	<div class="two3">
		<div class="projects-content">
			<h1><?php echo $this->item->title; ?></h1>
	
			<?php echo $this->item->description; ?>
	
			<div class="projects-space">
				<?php echo JHTML::_('tool.progressBar', 50); ?>
			</div>
				
			<dl class="projects-info projects-space projects-frame">
				<dt><?php echo JText::_('COM_PROJECTS_FIELD_START_AT_LABEL')?></dt>
				<dd><?php echo JFactory::getDate($this->item->start_at)->toFormat('%d.%m.%Y');?></dd>
	
				<dt><?php echo JText::_('COM_PROJECTS_FIELD_FINISH_AT_LABEL')?></dt>
				<dd><?php echo JFactory::getDate($this->item->finish_at)->toFormat('%d.%m.%Y');?></dd>
			</dl>
			
			<?php echo $this->loadTemplate('buttons'); ?>
		</div>
			
	</div>

	<div class="one3">
		<div class=" projects-space">
			<?php echo $this->loadTemplate('members');?>
			<?php echo $this->loadTemplate('tasks');?>
			<?php echo $this->loadTemplate('documents');?>
		</div>
	</div>
</div>
</div>