<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * JEditor class to handle WYSIWYG editors
 *
 * @author		Louis Landry <louis.landry@joomla.org>
 * @package		Joomla.Framework
 * @subpackage	Presentation
 * @since		1.5
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
	function & getInstance()
	{
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
	function init()
	{
		global $mainframe;

		$return = '';
		if ($mainframe->get('loadEditor', false)) {

			$args['event'] = 'onInit';

			$results[] = $this->_editor->update($args);
			foreach ($results as $result) {
				if (trim($result)) {
					//$return .= $result;
					$return = $result;
				}
			}
		}
		return $return;
	}

	/**
	 * Present a text area
	 *
	 *
	 */
	function display($name, $html, $width, $height, $col, $row)
	{
		global $mainframe, $my;

		$this->_loadEditor();

		/*
		 * Initialize variables
		 */
		$return = null;

		$args['name'] 		 = $name;
		$args['content']	 = $html;
		$args['width'] 		 = $width;
		$args['height'] 	 = $height;
		$args['col'] 		 = $col;
		$args['row'] 		 = $row;
		$args['event'] 		 = 'onDisplay';

		$results[] = $this->_editor->update($args);

		foreach ($results as $result) {
			if (trim($result)) {
				$return .= $result;
			}
		}
		return $return;
	}

	/**
	 * Save the editor content
	 *
	 *
	 */
	function save( $editor )
	{
		global $mainframe;

		$this->_loadEditor();

		$args[] = $editor;
		$args['event'] = 'onSave';

		$return = '';
		$results[] = $this->_editor->update($args);
		foreach ($results as $result) {
			if (trim($result)) {
				$return .= $result;
			}
		}
		return $return;
	}

	/**
	 * Get the editor extended buttons
	 *
	 *
	 */
	function getButtons($editor)
	{
		global $mainframe;

		$this->_loadEditor();

		$args['name'] = $editor;
		$args['event'] = 'onGetInsertMethod';

		$return = '';
		$results[] = $this->_editor->update($args);
		foreach ($results as $result) {
			if (trim($result)) {
				$return .= $result;
			}
		}

		$dispatcher =& JEventDispatcher::getInstance();
		$results = $dispatcher->trigger( 'onCustomEditorButton', array('name' => $editor) );

		$html = null;
		foreach ($results as $result) {
			/*
			 * Results should be a three offset array consisting of:
			 * [0] - onclick event
			 * [1] - button text
			 * [2] - button icon
			 */
			if ( $result[0] ) {
				$html .= "<div class=\"button2-left\"><div class=\"".$result[2]."\"><a title=\"".$result[1]."\" onclick=\"javascript: ".$result[0].";\">".$result[1]."</a></div></div>\n";
			}
		}
		/*
		 * This will allow plugins to attach buttons or change the behavior on the fly using AJAX
		 */
		return "\n<div id=\"editor-xtd-buttons\">\n$html</div>";
	}

	/**
	 * Get the editor contents
	 *
	 *
	 */
	function getContent( $editor )
	{
		global $mainframe;

		$this->_loadEditor();

		$args['name'] = $editor;
		$args['event'] = 'onGetContent';

		$return = '';
		$results[] = $this->_editor->update($args);
		foreach ($results as $result) {
			if (trim($result)) {
				$return .= $result;
			}
		}
		return $return;
	}

	/**
	 * Set the editor contents
	 *
	 *
	 */
	function setContent( $editor, $html )
	{
		global $mainframe;

		$this->_loadEditor();

		$args[] = $editor;
		$args['event'] = 'onSetContent';

		$return = '';
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
	 * @since 1.5
	 */
	function _loadEditor()
	{
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
		$user	=& $mainframe->getUser();
		if (is_object($user))
		{
			$editor = $user->getParam('editor', $editor);
		}

		// Build the path to the needed editor plugin
		$path = JPATH_SITE.DS.'plugins'.DS.'editors'.DS.$editor.'.php';

		//TODO::Raise warning when the file can't be found

		// Require plugin file
		require_once ($path);

		// Build editor plugin classname
		$name = 'JEditor_'.$editor;
		$this->_editor = new $name ($this);

		JPluginHelper::importPlugin('editors-xtd');

		$mainframe->set('loadEditor', true);
	}
}
?>