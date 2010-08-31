<?php
/**
 * @version		$Id:
 * @package		Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Vars
$pageClass = $this->params->get('pageclass_sfx');
?>
<form action="<?php echo ProjectsHelper::getLink('form'); ?>" method="post" id="adminForm" name="adminForm">
	<div class="projects<?php echo $pageClass;?> projects-task<?php echo $pageClass;?>">
	<?php 
		echo $this->loadTemplate($this->type);
	?>
	</div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="boxchecked" value="1" />     
	<?php echo JHTML::_('form.token'); ?>
</form>		
