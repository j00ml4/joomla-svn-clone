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
$link = JRoute::_('index.php?option=com_projects&view=project&id='.$item->id);
?>

<div class="items-row item">
	<h2>
		<a href="<?php echo $link; ?>"><?php echo $item->title;?></a>
	</h2>
	<div class="category-desc">
		<?php echo $item->description;?>
	</div>

	<a href="<?php echo $link; ?>">
		<?php echo JText::_('COM_PROJECTS_PROJECT_GALLERY_SEE_PROJECT');?>
	</a>
</div>
<div class="clr"></div>