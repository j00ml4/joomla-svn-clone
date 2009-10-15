<?php
/**
 * @version		$Id: system.php 12433 2009-07-04 06:51:26Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Utility class working with system
 *
 * @package		Joomla.Administrator
 * @subpackage	Admin
 * @since		1.6
 */
abstract class JHtmlSystem
{
	/**
	 * method to generate a string message for a value
	 *
	 * @param string $val a php ini value
	 *
	 * @return string html code
	 */
	public static function server($val)
	{
		if (empty($val)) {
			return JText::_('Admin_na');
		}
		else {
			return $val;
		}
	}
}

