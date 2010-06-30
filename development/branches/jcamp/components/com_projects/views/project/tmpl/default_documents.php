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
	<h4><?php echo JText::_('COM_PROJECTS_DOCUMENT_LIST');?></h4>
	<ul class="ulList">
		<li>doc1</li>
		<li>doc2</li>
	</ul>
	<a href="<?php echo JRoute::_('index.php?option=com_projects&view=documents&id='.$this->item->id);?>"><?php echo JText::_('COM_PROJECTS_DOCUMENT_LIST_LINK');?></a>
</div>
