<?php
/**
 * @version		$Id: default_navigation.php 13031 2009-10-02 21:54:22Z louis $
 * @package		Joomla.Administrator
 * @subpackage	com_config
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;
?>
<div id="submenu-box">
	<div class="t">
		<div class="t">
			<div class="t"></div>
 		</div>
	</div>
	<div class="m">
		<div class="submenu-box">
			<div class="submenu-pad">
				<ul id="submenu" class="configuration">
					<li><a href="#" onclick="return false;" id="site" class="active"><?php echo JText::_('Site'); ?></a></li>
					<li><a href="#" onclick="return false;" id="system"><?php echo JText::_('System'); ?></a></li>
					<li><a href="#" onclick="return false;" id="server"><?php echo JText::_('Server'); ?></a></li>
					<li><a href="#" onclick="return false;" id="permissions"><?php echo JText::_('Permissions'); ?></a></li>
				</ul>
				<div class="clr"></div>
			</div>
		</div>
		<div class="clr"></div>
	</div>
	<div class="b">
		<div class="b">
 			<div class="b"></div>
		</div>
	</div>
</div>