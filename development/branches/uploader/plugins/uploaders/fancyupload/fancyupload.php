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
class plgEditorFancyUpload extends JPlugin
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
	function onDisplayUploaderForm($name, $content, $width, $height, $col, $row, $buttons = true, $id = null)
	{
		if (empty($id)) {
			$id = $name;
		}

			if ($config->get('enable_flash', 1)) {
			$fileTypes = $config->get('image_extensions', 'bmp,gif,jpg,png,jpeg');
			$types = explode(',', $fileTypes);
			$displayTypes = '';		// this is what the user sees
			$filterTypes = '';		// this is what controls the logic
			$firstType = true;
			foreach($types AS $type) {
				if(!$firstType) {
					$displayTypes .= ', ';
					$filterTypes .= '; ';
				} else {
					$firstType = false;
				}
				$displayTypes .= '*.'.$type;
				$filterTypes .= '*.'.$type;
			}
			$typeString = '{ \'Images ('.$displayTypes.')\': \''.$filterTypes.'\' }';

			JHtml::_('behavior.uploader', 'upload-flash',
				array(
					'onComplete' => 'function(){ MediaManager.refreshFrame(); }',
					'targetURL' => '\\$(\'uploadForm\').action',
					'typeFilter' => $typeString
				)
			);
		}
		$buttons = $this->_displayButtons($id, $buttons);
		$editor  = "<textarea name=\"$name\" id=\"$id\" cols=\"$col\" rows=\"$row\" style=\"width: $width; height: $height;\">$content</textarea>" . $buttons;

		<form action="<?php echo JURI::base(); ?>index.php?option=com_media&amp;task=file.upload&amp;tmpl=component&amp;<?php echo $this->session->getName().'='.$this->session->getId(); ?>&amp;<?php echo JUtility::getToken();?>=1" id="uploadForm" method="post" enctype="multipart/form-data">
				<fieldset id="uploadform">
					<legend><?php echo JText::_('UPLOAD_FILE'); ?> (<?php echo JText::_('MAXIMUM_SIZE'); ?>:&nbsp;<?php echo ($this->config->get('upload_maxsize') / 1000000); ?>MB)</legend>
					<fieldset id="upload-noflash" class="actions">
						<label for="upload-file" class="hidelabeltxt"><?php echo JText::_('UPLOAD_FILE'); ?></label>
						<input type="file" id="upload-file" name="Filedata" />
						<label for="upload-submit" class="hidelabeltxt"><?php echo JText::_('START_UPLOAD'); ?></label>
						<input type="submit" id="upload-submit" value="<?php echo JText::_('START_UPLOAD'); ?>"/>
					</fieldset>
					<div id="upload-flash" class="hide">
						<ul>
							<li><a href="#" id="upload-browse"><?php echo JText::_('BROWSE_FILES'); ?></a></li>
							<li><a href="#" id="upload-clear">Clear List</a></li>
							<li><a href="#" id="upload-start">Start Upload</a></li>
						</ul>
						<div class="clr"> </div>
						<p class="overall-title"></p>
						<?php echo JHTML::_('image', 'media/bar.gif', JText::_('OVERALL_PROGRESS'), array('class' => 'progress overall-progress'), true); ?>
						<div class="clr"> </div>
						<p class="current-title"></p>
						<?php echo JHTML::_('image', 'media/bar.gif', JText::_('CURRENT_PROGRESS'), array('class' => 'progress current-progress'), true); ?>
						<p class="current-text"></p>
					</div>
					<ul class="upload-queue" id="upload-queue">
						<li style="display:none;" />
					</ul>
				</fieldset>
				<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_media'); ?>" />
			</form>
		
		return $editor;
	}
}