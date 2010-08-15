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
	<h4><?php echo JText::_('COM_PROJECTS_MEMBERS_LIST');?></h4>
	<ul class="items-list members-list">
		<li>elf</li>
		<li>eden</li>
		<li>har</li>
	</ul>
	<a href="<?php echo ProjectsHelper::getLink('members', $this->item->id); ?>" class="readmore">
		<?php echo JText::_('COM_PROJECTS_MEMBERS_LIST_LINK'); ?></a>
</div>
