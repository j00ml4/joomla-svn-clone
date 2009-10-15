<?php
/**
 * @version		$Id: categories.php 12277 2009-06-22 02:06:50Z pasamio $
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Weblinks helper.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_weblinks
 * @since		1.6
 */
class CategoriesHelper
{
	/**
	 * Configure the Submenu links.
	 *
	 * @param	string	The extension being used for the categories.
	 */
	public function addSubmenu($extension)
	{
		// Avoid nonsense situation.
		if ($extension == 'com_categories') {
			return;
		}

		// Try to find the component helper.
		$eName	= str_replace('com_', '', $extension);
		$file	= JPath::clean(JPATH_ADMINISTRATOR.'/components/'.$extension.'/helpers/'.$eName.'.php');

		if (file_exists($file))
		{
			require_once $file;
			$prefix	= ucfirst(str_replace('com_', '', $extension));
			$cName	= $prefix.'Helper';
			if (class_exists($cName))
			{
				if (is_callable(array($cName, 'addSubmenu')))
				{
					$lang = &JFactory::getLanguage();
					$lang->load($extension);
					call_user_func(array($cName, 'addSubmenu'), 'categories');
				}
			}
		}
	}
}