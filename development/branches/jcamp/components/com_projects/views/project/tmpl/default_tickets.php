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
	<h4><?php echo JText::_('COM_PROJECTS_PROJECT_TICKETS');?></h4>
	<ul class="ulList">
		<li>ticket</li>
		<li>ticket</li>
	</ul>
	<a href="<?php echo ProjectsHelper::getLink('tasks.tickets', $this->item->id); ?>" class="readmore">
		<?php echo JText::_('COM_PROJECTS_PROJECT_TICKET_LIST_LINK'); ?></a>	
</div>
