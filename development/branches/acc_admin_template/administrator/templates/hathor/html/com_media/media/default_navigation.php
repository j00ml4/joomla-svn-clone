<?php
/**
 * @version		$Id: default_navigation.php 13137 2009-10-10 21:16:32Z pentacle $
 * @package		Joomla.Administrator
 * @subpackage	com_media
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;
?>
<div id="submenu-box">
	<div class="submenu-box">
		<div class="submenu-pad">
			<ul id="submenu" class="media">
				<li><a href="#" id="thumbs" onclick="MediaManager.setViewType('thumbs')"><?php echo JText::_('COM_MEDIA_THUMBNAIL_VIEW'); ?></a></li>
				<li><a href="#" id="details" onclick="MediaManager.setViewType('details')"><?php echo JText::_('COM_MEDIA_DETAIL_VIEW'); ?></a></li>
			</ul>
			<div class="clr"></div>
		</div>
	</div>
	<div class="clr"></div>
</div>