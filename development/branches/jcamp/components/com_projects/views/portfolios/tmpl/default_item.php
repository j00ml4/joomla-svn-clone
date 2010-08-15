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
	<h3><a href="<?php echo $this->getLink('portfolios',$this->item->id);?>">
		<?php echo $this->item->title;?>
	</a></h3>
	<div class="cetegory-desc">			
		<?php echo $this->item->description;?>
		<div class="clear"></div>
		<dl>
			<dt>projects<dt><dd>xx</dd>
		</dl>
	</div>
	<a class="readmore" href="<?php echo $this->getLink('portfolios',$this->item->id);?>">
		<?php echo JText::_('COM_PROJECTS_PORTFOLIO_LINK'); ?>
	</a>
</li>