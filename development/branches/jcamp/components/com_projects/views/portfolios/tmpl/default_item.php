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
		<dl>
			<dd><a class="readmore" href="<?php echo ProjectsHelper::getLink('portfolios',$this->item->id);?>">
				<?php echo JText::sprintf('COM_PROJECTS_PORTFOLIOS_LINK', 'y'); ?>
			</a></dd>
			<dd><a class="readmore" href="<?php echo ProjectsHelper::getLink('portfolios',$this->item->id);?>">
				<?php echo JText::sprintf('COM_PROJECTS_PROJECTS_LINK', 'y'); ?>
			</a></dd>
		</dl>
	</div>
	
</li>