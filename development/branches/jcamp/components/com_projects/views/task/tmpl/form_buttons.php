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
?>
<!-- Actions -->
<div class="formelm_buttons">
	<button type="button" onclick="submitbutton('task.save')">
		<?php echo JText::_('JSAVE'); ?>
	</button>
	<button type="button" onclick="submitbutton('task.cancel')">
		<?php echo JText::_('JCANCEL'); ?>
	</button>
</div>