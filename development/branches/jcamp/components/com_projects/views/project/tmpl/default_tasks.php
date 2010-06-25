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
$link = JRoute::_('index.php?option=com_projects&view=tasks&id='.$this->item->id);
?>
<div class="project-content frame space">
	<h4><a href="<?php echo $link; ?>"><?php echo JText::_('COM_PROJECTS_TASKS');?></a></h4>
	<ul class="ulList">
		<li>task</li>
		<li>task</li>
	</ul>
	<a href="<?php echo $link; ?>"><?php echo JText::_('COM_PROJECTS_TASK_LIST_LINK');?></a>
</div>
