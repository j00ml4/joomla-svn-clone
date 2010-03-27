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
	function onDisplayUploaderForm($name, $id = null)
	{
		if (empty($id)) {
			$id = $name;
		}
		
		$config = JComponentHelper::getParams('com_media');
		$session = JFactory::getSession();
		
		
		$return = '<form action="'.JURI::base().'index.php?option=com_media&amp;task=file.upload&amp;tmpl=component&amp;'.$session->getName().'='.$session->getId().'&amp;'.JUtility::getToken().'=1" id="uploadForm" method="post" enctype="multipart/form-data">';
		$return .= '<fieldset id="uploadform">';
		$return .= '<legend>'.JText::_('UPLOAD_FILE').' ('.JText::_('MAXIMUM_SIZE').':&nbsp;'.($config->get('upload_maxsize') / 1000000).'MB)</legend>';
		$return .= '<fieldset id="upload-noflash" class="actions">';
		$return .= '<label for="upload-file" class="hidelabeltxt">'.JText::_('UPLOAD_FILE').'</label>';
		$return .= '<input type="file" id="upload-file" name="Filedata" />';
		$return .= '<label for="upload-submit" class="hidelabeltxt">'.JText::_('START_UPLOAD').'</label>';
		$return .= '<input type="submit" id="upload-submit" value="'.JText::_('START_UPLOAD').'"/>';
		$return .= '</fieldset>';
		$return .= '</fieldset>';
		$return .= '<input type="hidden" name="return-url" value="'.base64_encode('index.php?option=com_media').'" />';
		$return .= '</form>';

		return $return;
	}
}