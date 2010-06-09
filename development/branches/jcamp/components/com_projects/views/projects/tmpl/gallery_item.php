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
$params =& $this->item->params;
$canEdit = $this->user->authorise('core.edit', 'com_content.category.' . $this->item->id);
?>

<div class="portfolio-projects-gallery-item">
	<h2 class="portfolio-projects-gallery-item-header"><?php echo $this->item->title;?> - (<?php echo $this->item->alias;?>)</h2>
	<div class="portfolio-projects-gallery-item-desc">
		<?php echo $this->item->description;?>
	</div>
	<span class="portfolio-projects-gallery-item-created">
		<?php echo JText::_('COM_PROJECTS_PROJECT_CREATED_BY')?>:
			<?php echo $this->item->name.JText::_('COM_PROJECTS_PROJECT_CREATED_ON').' '.Date(JText::_('DATE_FORMAT_LC3'),strtotime($this->item->created));?>
	</span><br />
	<a href="<?php echo JRoute::_('index.php?option=com_projects&view=project&layout=detail&id='.$this->item->id);?>">
	<?php echo JText::_('COM_PROJECTS_PROJECT_GALLERY_SEE_PROJECT');?></a>
</div>
<div class="portfolio-projects-gallery-item-separator"></div>