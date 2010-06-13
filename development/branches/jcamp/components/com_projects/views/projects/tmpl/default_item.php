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
<li class="portfolio-list-item">
	<a href="<?php echo JRoute::_('index.php?option=com_projects&view=projects&layout=project&id='.$this->item->id);?>">
		<?php echo $this->item->title;?>
	</a>
</li>