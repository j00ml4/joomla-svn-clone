<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

if (!defined('_MOS_EDITOR_INCLUDED')) {

	function initEditor() {
		global $mainframe;

		if ($mainframe->get('loadEditor')) {
			$results = $mainframe->triggerEvent('onInitEditor');
			foreach ($results as $result) {
				if (trim($result)) {
					echo $result;
				}
			}
		}
	}

	function _loadEditor() {
			global $mainframe, $mosConfig_editor, $my;

		if ($mainframe->get('loadEditor')) {
			return;
		}

		if ($mosConfig_editor == '') {
			$mosConfig_editor = 'none';
		}

		// Per User Editor selection
		$editor = $mosConfig_editor;

		if (isset ($my)) {
			$params = new mosParameters($my->params);
			$editor = $params->get('editor', $mosConfig_editor);
		}

		JPluginHelper :: import('editors', $editor, 1);

		$mainframe->set('loadEditor', true);
	}

	function getEditorContents($editorArea, $hiddenField) {
		global $mainframe;

		_loadEditor();

		$results = $mainframe->triggerEvent('onGetEditorContents', array ($editorArea, $hiddenField));
		foreach ($results as $result) {
			if (trim($result)) {
				echo $result;
			}
		}
	}
	// just present a textarea
	function editorArea($name, $content, $hiddenField, $width, $height, $col, $row) {
		global $mainframe, $my;

		_loadEditor();

		$results = $mainframe->triggerEvent('onEditorArea', array ($name, $content, $hiddenField, $width, $height, $col, $row));
		foreach ($results as $result) {
			if (trim($result)) {
				echo $result;
			}
		}
	}

	define('_MOS_EDITOR_INCLUDED', 1);
}

/**
 * JEditor class to handle WYSIWYG editors
 * 
 * @author Louis Landry <louis@webimagery.net>
 * @package Joomla
 * @subpackage Editors
 * @since 1.1
 */
class JEditor extends JObservable {

	/**
	 * Editor Plugin object
	 */
	var $_editor = null;

	function __construct() {
	}

	/**
	 * Returns a reference to a global Editor object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		<pre>  $editor = &JEditor::getInstance([$editor);</pre>
	 *
	 * @access public
	 * @param string $editor  The editor to use.
	 * @return JEditor  The Editor object.
	 */
	function & getInstance() {
		static $instances;

		if (!isset ($instances)) {
			$instances = array ();
		}

		if (empty ($instances[0])) {
			$instances[0] = new JEditor();
		}

		return $instances[0];
	}

	/**
	 * Initialize the editor
	 * 
	 */
	function initEditor() {
		global $mainframe;

		if ($mainframe->get('loadEditor')) {
			
			$args['event'] = 'onInitEditor';
			
			$results[] = $this->_editor->update($args);
			foreach ($results as $result) {
				if (trim($result)) {
					$return .= $result;
				}
			}
		}
		return $return;
	}

	/**
	 * Get the editor contents
	 * 
	 * 
	 */
	function getEditorContents($editorArea, $hiddenField) {
		global $mainframe;

		$this->_loadEditor();
		
		$args[] = $editorArea;
		$args[] = $hiddenField;
		$args['event'] = 'onGetEditorContents';

		$results[] = $this->_editor->update($args);
		foreach ($results as $result) {
			if (trim($result)) {
				$return .= $result;
			}
		}
		return $return;
	}

	/**
	 * Present a text area
	 * 
	 * 
	 */
	function getEditor($name, $content, $hiddenField, $width, $height, $col, $row) {
		global $mainframe, $my;

		$this->_loadEditor();

		$args['name'] = $name;
		$args['content'] = $content;
		$args['hiddenField'] = $hiddenField;
		$args['width'] = $width;
		$args['height'] = $height;
		$args['col'] = $col;
		$args['row'] = $row;
		$args['event'] = 'onEditorArea';
		$results[] = $this->_editor->update($args);
		foreach ($results as $result) {
			if (trim($result)) {
				$return .= $result;
			}
		}
		return $return;
	}

	/**
	 * Load the editor
	 * 
	 * @access private
	 * @since 1.1 
	 */
	function _loadEditor() {
		global $mainframe;

		if ($mainframe->get('loadEditor')) {
			return;
		}

		if ($mainframe->getCfg('editor') == '') {
			$editor = 'none';
		} else {
			$editor = $mainframe->getCfg('editor');
		}

		/*
		 * Handle per-user editor options
		 */
		$my =& $mainframe->getUser();
		if (isset ($my)) {
			$params = new mosParameters($my->params);
			$editor = $params->get('editor', $editor);
		}

		// Build the path to the needed editor plugin
		$path = JPATH_SITE.DS.'plugins'.DS.'editors'.DS.$editor.'.php';

		// Require plugin file
		require_once ($path);

		// Build editor plugin classname
		$name = 'JEditor_'.$editor;
		$this->_editor = new $name ($this);
		
		JPluginHelper :: importGroup('editors-xtd');

		$mainframe->set('loadEditor', true);
	}
}
?>