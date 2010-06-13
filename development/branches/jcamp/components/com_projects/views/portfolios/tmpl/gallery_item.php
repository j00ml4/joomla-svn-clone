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
<div class="portfolio-list-item">
	<div class="divProjects-content">
		<h3><a class="aNameItem" href="<?php echo JRoute::_('index.php?option=com_projects&view=projects&layout=gallery&id='.$this->item->id);?>">
			<?php echo $this->item->title;?>
		</a></h3>
		<?php echo $this->item->description;?>
	</div>
</div	>