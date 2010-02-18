<?php
/**
 * @version		$Id: default_navigation.php 12447 2009-07-05 03:43:03Z eddieajau $
 * @package		Hathor Accessible Administrator Template
 * @since		1.6
 * @version  	1.04
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;
?>
<div id="submenu-box">
			<ul id="submenu" class="configuration">
									<li><a href="#" onclick="return false;" id="site" class="active"><?php echo JText::_('SITE'); ?></a></li>
					<li><a href="#" onclick="return false;" id="system"><?php echo JText::_('SYSTEM'); ?></a></li>
					<li><a href="#" onclick="return false;" id="server"><?php echo JText::_('SERVER'); ?></a></li>
					<li><a href="#" onclick="return false;" id="permissions"><?php echo JText::_('PERMISSIONS'); ?></a></li>
			</ul>
			<div class="clr"></div>
</div>