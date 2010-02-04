<?php
/**
 * @version		$Id: none.php 14563 2010-02-04 06:58:22Z eddieajau $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Plain Textarea Editor Plugin
 *
 * @package		Joomla
 * @subpackage	Editors
 * @since		1.5
 */
class plgUploaderNone extends JPlugin
{

	/**
	 * Display the editor area.
	 *
	 * @param	string	The name of the editor area.
	 * @param	string	The content of the field.
	 * @param	string	The width of the editor area.
	 * @param	string	The height of the editor area.
	 * @param	int		The number of columns for the editor area.
	 * @param	int		The number of rows for the editor area.
	 * @param	boolean	True and the editor buttons will be displayed.
	 * @param	string	An optional ID for the textarea (note: since 1.6). If not supplied the name is used.
	 */
	function onDisplay($name, $id = null)
	{
		if (empty($id)) {
			$id = $name;
		}

		$editor  = "<textarea name=\"$name\" id=\"$id\" cols=\"$col\" rows=\"$row\" style=\"width: $width; height: $height;\">$content</textarea>" . $buttons;

		return $editor;
	}
}