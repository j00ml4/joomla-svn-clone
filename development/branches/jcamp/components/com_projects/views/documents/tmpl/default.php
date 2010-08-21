<?php
/**
 * @version		$Id: default.php 17187 2010-05-19 11:18:22Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;
?>

<div class="project-items <?php echo $this->params->get('pageclass_sfx'); ?>">
<form action="<?php echo ProjectsHelper::getLink('form'); ?>" method="post" id="adminForm" name="adminForm">
	<div class="category-list">	
	    <div class="cat-items">
	        <?php echo $this->loadTemplate('articles'); ?>
	    </div>
    </div>
    
    <input type="hidden" id="task" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<input type="hidden" name="limitstart" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>    
</div>