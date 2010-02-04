<?php
/**
 * @version		$Id: uploader.php 14577 2010-02-04 07:12:36Z eddieajau $
 * @package		Joomla.Framework
 * @subpackage	HTML
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

jimport('joomla.event.dispatcher');

/**
 * JUploader class to handle different uploader scripts
 *
 * @package		Joomla.Framework
 * @subpackage	HTML
 * @since		1.6
 */
class JUploader extends JObservable
{
	/**
	 * Uploader Plugin object
	 *
	 * @var	object
	 */
	protected $_uploader = null;

	/**
	 * Uploader Plugin name
	 *
	 * @var string
	 */
	protected $_name = null;

	/**
	 * Uploader Options
	 * 
	 * @var array
	 */
	protected $_options = array();
	
	/**
	 * constructor
	 *
	 * @param	string	The editor name
	 */
	public function __construct($uploader = 'none')
	{
		$this->_name = $uploader;
	}

	/**
	 * Returns the global Uploader object, only creating it
	 * if it doesn't already exist.
	 *
	 * @param	string	$uploader  The uploader to use.
	 * @return	JUploader	The Uploader object.
	 */
	public static function getInstance($uploader = 'none')
	{
		static $instances;

		if (!isset ($instances)) {
			$instances = array ();
		}

		$signature = serialize($uploader);

		if (empty ($instances[$signature])) {
			$instances[$signature] = new JUploader($uploader);
		}

		return $instances[$signature];
	}

	/**
	 * Set options for uploader
	 * 
	 * @param array $options 
	 */
	public function setOptions($options = array())
	{
		$this->_options = $options;
	}
	
	/**
	 * Display the uploader area.
	 *
	 * @param	string	The control name.
	 * @param	string	An optional ID for the textarea (note: since 1.6). If not supplied the name is used.
	 * @param	array	Associative array of editor parameters.
	 */
	public function display($name, $id = null, $params = array())
	{
		$this->_loadUploader($params);

		//check if uploader is already loaded
		if (is_null(($this->_uploader))) {
			return;
		}

		// Initialise variables.
		$return = null;

		$args['name']		= $name;
		$args['id']			= $id ? $id : $name;
		$args['event']		= 'onDisplayUploaderForm';

		$results[] = $this->_uploader->update($args);

		foreach ($results as $result) {
			if (trim($result)) {
				$return .= $result;
			}
		}
		return $return;
	}

	/**
	 * Load the Uploader
	 *
	 * @param	array	Associative array of uploader config paramaters
	 * @since	1.6
	 */
	protected function _loadUploader($config = array())
	{
		//check if editor is already loaded
		if (!is_null(($this->_uploader))) {
			return;
		}

		jimport('joomla.filesystem.file');

		// Build the path to the needed editor plugin
		$name = JFilterInput::getInstance()->clean($this->_name, 'cmd');
		$path = JPATH_SITE.DS.'plugins/uploaders/'.$name.'.php';

		if (!JFile::exists($path)) {
			$path = JPATH_SITE.DS.'plugins/uploaders/'.$name.'/'.$name.'.php';
			if (!JFile::exists($path)) {
				$message = JText::_('Cannot load the uploader');
				JError::raiseWarning(500, $message);
				return false;
			}
		}

		// Require plugin file
		require_once $path;

		// Get the plugin
		$plugin	= &JPluginHelper::getPlugin('uploaders', $this->_name);
		$params	= new JParameter($plugin->params);
		$params->loadArray($config);
		$plugin->params = $params;

		// Build editor plugin classname
		$name = 'plgUploader'.$this->_name;
		$this->_uploader = new $name ($this, (array)$plugin);
	}
}