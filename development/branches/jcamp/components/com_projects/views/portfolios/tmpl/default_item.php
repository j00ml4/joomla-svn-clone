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
<div class="items-row item">
	<h2><a href="<?php echo JRoute::_('index.php?option=com_projects&view=portfolios&id='.$this->item->id);?>">
			<?php echo $this->item->title;?>
		</a></h2>
		<div class="cetegory-desc">			
			<?php echo $this->item->description;?>
			<div class="clr">&nbsp;</div>
		</div>
</div	>