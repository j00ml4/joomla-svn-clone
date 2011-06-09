<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * CodeMirror Editor Plugin.
 *
 * @package		Joomla.Plugin
 * @subpackage	Editors.codemirror
 * @since		1.6
 */
class plgEditorCodemirror extends JPlugin
{
	/**
	 * Base path for editor files
	 */
	protected $_basePath = 'media/editors/codemirror/';

	/**
	 * Initialises the Editor.
	 *
	 * @return	string	JavaScript Initialization string.
	 */
	public function onInit()
	{
		JHtml::_('core');
		$uncompressed	= JFactory::getApplication()->getCfg('debug') ? '-uncompressed' : '';
		JHtml::_('script', $this->_basePath . 'js/codemirror'.$uncompressed.'.js', false, false, false, false);
		JHtml::_('stylesheet', $this->_basePath . 'css/codemirror.css');

		return '';
	}

	/**
	 * Copy editor content to form field.
	 *
	 * @param	string	$id	The id of the editor field.
	 *
	 * @return string Javascript
	 */
	public function onSave($id)
	{
		return "Joomla.editors.instances['$id'].save();\n";
	}

	/**
	 * Get the editor content.
	 *
	 * @param	string	$id	The id of the editor field.
	 *
	 * @return string Javascript
	 */
	public function onGetContent($id)
	{
		return "Joomla.editors.instances['$id'].getValue();\n";
	}

	/**
	 * Set the editor content.
	 *
	 * @param	string	$id			The id of the editor field.
	 * @param	string	$content	The content to set.
	 *
	 * @return string Javascript
	 */
	public function onSetContent($id, $content)
	{
		return "Joomla.editors.instances['$id'].setCode($content);\n";
	}

	/**
	 * Adds the editor specific insert method.
	 *
	 * @return boolean
	 */
	public function onGetInsertMethod()
	{
		static $done = false;

		// Do this only once.
		if (!$done)
		{
			$done = true;
			$doc = JFactory::getDocument();
			$js = "\tfunction jInsertEditorText(text, editor) {
					Joomla.editors.instances[editor].replaceSelection(text);\n
			}";
			$doc->addScriptDeclaration($js);
		}

		return true;
	}

	/**
	 * Display the editor area.
	 *
	 * @param	string	$name		The control name.
	 * @param	string	$html		The contents of the text area.
	 * @param	string	$width		The width of the text area (px or %).
	 * @param	string	$height		The height of the text area (px or %).
	 * @param	int		$col		The number of columns for the textarea.
	 * @param	int		$row		The number of rows for the textarea.
	 * @param	boolean	$buttons	True and the editor buttons will be displayed.
	 * @param	string	$id			An optional ID for the textarea (note: since 1.6). If not supplied the name is used.
	 * @param	string	$asset
	 * @param	object	$author
	 * @param	array	$params		Associative array of editor parameters.
	 *
	 * @return string HTML
	 */
	public function onDisplay($name, $content, $width, $height, $col, $row, $buttons = true, $id = null, $asset = null, $author = null, $params = array())
	{
		$document = JFactory::getDocument();

		if (empty($id)) {
			$id = $name;
		}

		// Only add "px" to width and height if they are not given as a percentage
		if (is_numeric($width)) {
			$width .= 'px';
		}

		if (is_numeric($height)) {
			$height .= 'px';
		}

		$document->addStyleDeclaration('.CodeMirror {
			width: '.$width.';
			font-size: 13px;
		}
		
		.CodeMirror-scroll {
			height: '.$height.';
		}');

		// Must pass the field id to the buttons in this editor.
		$buttons = $this->_displayButtons($id, $buttons, $asset, $author);

		$compressed	= JFactory::getApplication()->getCfg('debug') ? '-uncompressed' : '';

		$theme = 'default';

		// Default syntax
		$parserName = 'xml';

		// Look if we need special syntax coloring.
		$syntax = JFactory::getApplication()->getUserState('editor.source.syntax');

		if ($syntax) {
			switch($syntax)
			{
				case 'css':
					$parserName = 'css';
					break;

				case 'js':
					// @todo Do we edit javascript ?
					$parserName = 'js';
					break;

				case 'php':
					$parserName = 'php';
					break;

				/* case 'html':
					$parserName = 'htmlmixed';
					break; */
				// TODO: Depends on CSS, XML and JS need to handle that

				case 'xml':
					$parserName = 'xml';
					break;

				default:
					;
					break;
			} //switch
		}

		$options	= new stdClass;

		$options->path			= JURI::root(true).'/'.$this->_basePath.'js/';
		$options->mode			= $parserName;
		//$options->height		= $height;
		//$options->width			= $width;
		$options->theme			= $theme;
		$options->continuousScanning = 500;
		
		

		if ($this->params->get('linenumbers', 0)) {
			$options->lineNumbers	= true;
			$options->textWrapping	= false;
		}

		if ($this->params->get('tabmode', '') == 'shift') {
			$options->tabMode = 'shift';
		}

		JHtml::_('script', $this->_basePath . 'js/modes/'.$parserName.'.js', false, false, false, false);
		JHtml::_('stylesheet', $this->_basePath . 'css/theme/'.$theme.'.css');

		$html = array();
		$html[]	= "<textarea name=\"$name\" id=\"$id\" cols=\"$col\" rows=\"$row\">$content</textarea>";
		$html[] = $buttons;
		$html[] = '<script type="text/javascript">';
		$html[] = '(function() {';
		$html[] = 'var editor = CodeMirror.fromTextArea(document.getElementById("'.$id.'"), '.json_encode($options).');';
		$html[] = 'Joomla.editors.instances[\''.$id.'\'] = editor;';
		$html[] = '})()';
		$html[] = '</script>';

		return implode("\n", $html);
	}

	/**
	 * Displays the editor buttons.
	 *
	 * @param string $name
	 * @param mixed $buttons [array with button objects | boolean true to display buttons]
	 *
	 * @return string HTML
	 */
	protected function _displayButtons($name, $buttons, $asset, $author)
	{
		// Load modal popup behavior
		JHtml::_('behavior.modal', 'a.modal-button');

		$args['name'] = $name;
		$args['event'] = 'onGetInsertMethod';

		$html = array();
		$results[] = $this->update($args);

		foreach ($results as $result)
		{
			if (is_string($result) && trim($result)) {
				$html[] = $result;
			}
		}

		if (is_array($buttons) || (is_bool($buttons) && $buttons)) {
			$results = $this->_subject->getButtons($name, $buttons, $asset, $author);

			// This will allow plugins to attach buttons or change the behavior on the fly using AJAX
			$html[] = '<div id="editor-xtd-buttons">';

			foreach ($results as $button)
			{
				// Results should be an object
				if ($button->get('name')) {
					$modal		= ($button->get('modal')) ? 'class="modal-button"' : null;
					$href		= ($button->get('link')) ? 'href="'.JURI::base().$button->get('link').'"' : null;
					$onclick	= ($button->get('onclick')) ? 'onclick="'.$button->get('onclick').'"' : null;
					$title      = ($button->get('title')) ? $button->get('title') : $button->get('text');
					$html[] = '<div class="button2-left"><div class="'.$button->get('name').'">';
					$html[] = '<a '.$modal.' title="'.$title.'" '.$href.' '.$onclick.' rel="'.$button->get('options').'">';
					$html[] = $button->get('text').'</a></div></div>';
				}
			}

			$html[] = '</div>';
		}

		return implode("\n", $html);
	}
}
