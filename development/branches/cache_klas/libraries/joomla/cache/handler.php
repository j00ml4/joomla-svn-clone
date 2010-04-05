<?php
/**
 * @version		$Id:storage.php 6961 2007-03-15 16:06:53Z tcp $
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @copyright	Copyright (C) 2010 Klas Berlič
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;



/**
 * Abstract cache handler
 *
 * @abstract
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @since		1.6
 */
class JCacheHandler

{	
	public $cache;
	public $options;
	
	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	array	$options	options
	*/
	
	function __construct($options) {
		
		$this->cache = new JCache($options);
		$this->options = $this->cache->_options;
		
		// Overwrite default options with given options
		foreach ($options AS $option=>$value) {
			if (isset($options[$option])) {
				$this->options[$option] = $options[$option];
			}
		}
	}
	
	function __call ($name, $arguments) {

		$nazaj = call_user_func_array (array ($this->cache,$name),$arguments);
		return $nazaj;
	}
	
	/**
	 * Returns a reference to a cache adapter object, always creating it
	 *
	 * @static
	 * @param	string	$type	The cache object type to instantiate
	 * @return	object	A JCache object
	 * @since	1.5
	 */
	function getInstance($type = 'output', $options = array())
	{	
		JCacheHandler::addIncludePath(JPATH_LIBRARIES.DS.'joomla'.DS.'cache'.DS.'handler');
		
		$type = strtolower(preg_replace('/[^A-Z0-9_\.-]/i', '', $type));

		$class = 'JCacheHandler'.ucfirst($type);

		if (!class_exists($class))
		{	
			// Search for the class file in the JCache include paths.
			jimport('joomla.filesystem.path');
			if ($path = JPath::find(JCacheHandler::addIncludePath(), strtolower($type).'.php')) {
			//$path = dirname(__FILE__).DS.'handler'.DS.$type.'.php';

			//if (file_exists($path)) {
				require_once $path;
			} else {
				JError::raiseError(500, 'Unable to load Cache Handler: '.$type);
			}
		}

		return new $class($options);
	}
	
	/**
	 * Set caching enabled state
	 *
	 * @access	public
	 * @param	boolean	$enabled	True to enable caching
	 * @return	void
	 * @since	1.5
	 */
	function setCaching($enabled)
	{
		$this->cache->setCaching($enabled);
	}

	/**
	 * Set cache lifetime
	 *
	 * @access	public
	 * @param	int	$lt	Cache lifetime
	 * @return	void
	 * @since	1.5
	 */
	function setLifeTime($lt)
	{
		$this->cache->setLifeTime($lt);
	}
	
		/**
	 * Add a directory where JCache should search for handlers. You may
	 * either pass a string or an array of directories.
	 *
	 * @param	string	A path to search.
	 * @return	array	An array with directory elements
	 * @since	1.6
	 */
	
	public static function addIncludePath($path='')
	{
		static $paths;

		if (!isset($paths)) {
			$paths = array();
		}
		if (!empty($path) && !in_array($path, $paths)) {
			jimport('joomla.filesystem.path');
			array_unshift($paths, JPath::clean($path));
		}
		return $paths;
	}
	
	/**
	 * Store the cached data by id and group
	 *
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @param	mixed	$data	The data to store
	 * @return	boolean	True if cache stored
	 * @since	1.6
	 */
	function get($id, $group=null)
	{	$data = unserialize($this->cache->get($id, $group=null));
		return $data;
	}
	
	/**
	 * Store the cached data by id and group
	 *
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @param	mixed	$data	The data to store
	 * @return	boolean	True if cache stored
	 * @since	1.6
	 */
	function store($data, $id, $group=null)
	{
		return $this->cache->store(serialize($data), $id, $group=null);
	}

}