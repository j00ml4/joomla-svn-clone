<?php
/**
 * @version		$Id: edit.php 17079 2010-05-15 17:17:59Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>

<!-- Actions -->
<div class="formelm_buttons">
	<button type="button" onclick="submitbutton('project.save')">
		<?php echo JText::_('JSAVE') ?>
	</button>
	<button type="button" onclick="submitbutton('project.cancel')">
		<?php echo JText::_('JCANCEL') ?>
	</button>
</div>