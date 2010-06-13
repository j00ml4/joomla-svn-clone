<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Create a shortcut for params.
$params	=& $item->params;
$item	=& $this->item;
$link = JRoute::_('index.php?option=com_projects&task=project.add');
?>

<div class="formelm_buttons">
	<a href="<?php echo $link; ?>"><?php echo JText::_('COM_PROJECTS_CREATE_PROJECT'); ?></a>
</div>