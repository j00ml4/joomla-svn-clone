<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;
?>
<li class="items-row item">
	<h3><a href="<?php echo ProjectsHelper::getLink('portfolios',$this->item->id); ?>">
		<?php echo $this->item->title;?>
	</a></h3>
	<div class="cetegory-desc">			
		<?php echo $this->item->description;?>
		<div class="clear"></div>
		<?php if ($this->item->numcategories) :?>
		<a title="<?php echo JText::_('COM_PROJECTS_PORTFOLIOS_LINK_DESC'); ?>" class="readmore" href="<?php echo ProjectsHelper::getLink('portfolios',$this->item->id);?>">
			<?php echo JText::sprintf('COM_PROJECTS_PORTFOLIOS_LINK', $this->item->numcategories); ?>
		</a>
		<?php endif; ?>
		<a title="<?php echo JText::_('COM_PROJECTS_PROJECTS_LINK_DESC'); ?>" class="readmore" href="<?php echo ProjectsHelper::getLink('projects',$this->item->id);?>">
			<?php echo JText::sprintf('COM_PROJECTS_PROJECTS_LINK', $this->item->numitems); ?>
		</a>
	
	</div>
	
</li>