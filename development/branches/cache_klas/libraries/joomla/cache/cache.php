<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @copyright	Copyright (C) 2010 Klas Berlič
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

//Register the session storage class with the loader
JLoader::register('JCacheStorage', dirname(__FILE__).DS.'storage.php');

JCache::addIncludePath(JPATH_LIBRARIES.DS.'joomla'.DS.'cache'.DS.'handler');

/**
 * Joomla! Cache base object
 *
 * @abstract
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @since		1.5
 */
class JCache extends JObject
{
	/**
	 * Storage Handler
	 * @access	private
	 * @var		object
	 */
	var $_handler;

	/**
	 * Cache Options
	 * @access	private
	 * @var		array
	 */
	var $_options;

	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	array	$options	options
	 */
	function __construct($options)
	{
		$conf = &JFactory::getConfig();

		$this->_options = array(
			'cachebase'		=> $conf->get('cache_path',JPATH_ROOT.DS.'cache'),
			'lifetime'		=> $conf->get('cachetime') * 60,	// minutes to seconds
			'language'		=> $conf->get('language','en-GB'),
			'storage'		=> $conf->get('cache_handler', 'file'),
			'defaultgroup'=>'default',
			'locking'=>true,
			'caching'=>true
		);

		// Overwrite default options with given options
		foreach ($this->_options AS $option=>$value) {
			if (isset($options[$option])) {
				$this->_options[$option] = $options[$option];
			}
		}

		// Fix to detect if template positions are enabled...
		//@todo remove, moved to safeuri parameters, no need to disable cache
		/*if (JRequest::getCMD('tpl',0)) {
		$this->_options['caching'] = false;
		}*/
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
		$type = strtolower(preg_replace('/[^A-Z0-9_\.-]/i', '', $type));

		$class = 'JCache'.ucfirst($type);

		if (!class_exists($class))
		{	
			// Search for the class file in the JCache include paths.
			jimport('joomla.filesystem.path');
			if ($path = JPath::find(JCache::addIncludePath(), strtolower($type).'.php')) {
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
	 * Get the storage handlers
	 *
	 * @access public
	 * @return array An array of available storage handlers
	 */
	function getStores()
	{
		jimport('joomla.filesystem.folder');
		$handlers = JFolder::files(dirname(__FILE__).DS.'storage', '.php');

		$names = array();
		foreach($handlers as $handler)
		{
			$name = substr($handler, 0, strrpos($handler, '.'));
			$class = 'JCacheStorage'.$name;

			if (!class_exists($class)) {
				require_once dirname(__FILE__).DS.'storage'.DS.$name.'.php';
			}

			if (call_user_func_array(array(trim($class), 'test'), array())) {
				$names[] = $name;
			}
		}

		return $names;
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
		$this->_options['caching'] = $enabled;
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
		$this->_options['lifetime'] = $lt;
	}

	/**
	 * Get cached data by id and group
	 *
	 * @abstract
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @return	mixed	Boolean false on failure or a cached data string
	 * @since	1.5
	 */
	function get($id, $group=null)
	{
		// Get the default group
		$group = ($group) ? $group : $this->_options['defaultgroup'];

		// Get the storage
		$handler = &$this->_getStorage();
		if (!JError::isError($handler) && $this->_options['caching']) {
			return $handler->get($id, $group, (isset($this->_options['checkTime']))? $this->_options['checkTime'] : true);
		}
		return false;
	}

	/**
	 * Get a list of all cached data
	 *
	 * @abstract
	 * @access	public
	 * @return	mixed	Boolean false on failure or an object with a list of cache groups and data
	 * @since	1.6
	 */
	function getAll()
	{
		// Get the storage
		$handler = &$this->_getStorage();
		if (!JError::isError($handler) && $this->_options['caching']) {
			return $handler->getAll();
		}
		return false;
	}

	/**
	 * Store the cached data by id and group
	 *
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @param	mixed	$data	The data to store
	 * @return	boolean	True if cache stored
	 * @since	1.5
	 */
	function store($data, $id, $group=null)
	{
		// Get the default group
		$group = ($group) ? $group : $this->_options['defaultgroup'];

		// Get the storage and store the cached data
		$handler = &$this->_getStorage();
		if (!JError::isError($handler) && $this->_options['caching']) {
			return $handler->store($id, $group, $data);
		}
		return false;
	}

	/**
	 * Remove a cached data entry by id and group
	 *
	 * @abstract
	 * @access	public
	 * @param	string	$id		The cache data id
	 * @param	string	$group	The cache data group
	 * @return	boolean	True on success, false otherwise
	 * @since	1.5
	 */
	function remove($id, $group=null)
	{
		// Get the default group
		$group = ($group) ? $group : $this->_options['defaultgroup'];

		// Get the storage
		$handler = &$this->_getStorage();
		if (!JError::isError($handler)) {
			return $handler->remove($id, $group);
		}
		return false;
	}

	/**
	 * Clean cache for a group given a mode.
	 *
	 * group mode		: cleans all cache in the group
	 * notgroup mode	: cleans all cache not in the group
	 *
	 * @access	public
	 * @param	string	$group	The cache data group
	 * @param	string	$mode	The mode for cleaning cache [group|notgroup]
	 * @return	boolean	True on success, false otherwise
	 * @since	1.5
	 */
	function clean($group=null, $mode='group')
	{
		// Get the default group
		$group = ($group) ? $group : $this->_options['defaultgroup'];

		// Get the storage handler
		$handler = &$this->_getStorage();
		if (!JError::isError($handler)) {
			return $handler->clean($group, $mode);
		}
		return false;
	}

	/**
	 * Garbage collect expired cache data
	 *
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 * @since	1.5
	 */
	function gc()
	{
		// Get the storage handler
		$handler = &$this->_getStorage();
		if (!JError::isError($handler)) {
			return $handler->gc();
		}
		return false;
	}

	/**
	 * Get the cache storage handler
	 *
	 * @access protected
	 * @return object A JCacheStorage object
	 * @since	1.5
	 */
	function _getStorage()
	{
		if (is_a($this->_handler, 'JCacheStorage')) {
			return $this->_handler;
		}

		$this->_handler = &JCacheStorage::getInstance($this->_options['storage'], $this->_options);
		return $this->_handler;
	}

	/**
	 * Perform workarounds on retrieved cached data
	 * @param	string	$data		Cached data
	 * @return	string	$body		Body of cached data
	 * @since	1.6
	 */
	static function getWorkarounds($data) {
			
		// Initialise variables.
		$app 		= &JFactory::getApplication();
		$document	= &JFactory::getDocument();
		$body = null;
			
		// Get the document head out of the cache.
		$document->setHeadData((isset($data['head'])) ? $data['head'] : array());

		// If the pathway buffer is set in the cache data, get it.
		if (isset($data['pathway']) && is_array($data['pathway']))
		{
			// Push the pathway data into the pathway object.
			$pathway = &$app->getPathWay();
			$pathway->setPathway($data['pathway']);
		}

		// @todo chech if the following is needed, seems like it should be in page cache
		// If a module buffer is set in the cache data, get it.
		if (isset($data['module']) && is_array($data['module']))
		{
			// Iterate through the module positions and push them into the document buffer.
			foreach ($data['module'] as $name => $contents) {
				$document->setBuffer($contents, 'module', $name);
			}
		}
			
		if (isset($data['body'])) {
			// the following code searches for a token in the cached page and replaces it with the
			// proper token.
			$token	= JUtility::getToken();
			$search = '#<input type="hidden" name="[0-9a-f]{32}" value="1" />#';
			$replacement = '<input type="hidden" name="'.$token.'" value="1" />';
			$data['body'] = preg_replace($search, $replacement, $data['body']);
			$body = $data['body'];
		}
			
		// Get the document body out of the cache.
		return $body;
	}
	
	/**
	 * Create workarounded data to be cached
	 * @param	string	$data		Cached data
	 * @return	string	$cached		Data to be cached
	 * @since	1.6
	 */
	
	static function setWorkarounds($data) {
			
		// Initialise variables.
		$app = &JFactory::getApplication();
		$document	= &JFactory::getDocument();

		// Get the modules buffer before component execution.
		$buffer1 = $document->getBuffer();

		// Make sure the module buffer is an array.
		if (!isset($buffer1['module']) || !is_array($buffer1['module'])) {
			$buffer1['module'] = array();
		}
			
		// View body data
		$cached['body'] = $data;

		// Document head data
		$cached['head'] = $document->getHeadData();

		// Pathway data
		$pathway			= &$app->getPathWay();
		if (isset($pathway)) {$cached['pathway'] = $pathway->getPathway();}

		// @todo chech if the following is needed, seems like it should be in page cache
		// Get the module buffer after component execution.
		$buffer2 = $document->getBuffer();
			
		// Make sure the module buffer is an array.
		if (!isset($buffer2['module']) || !is_array($buffer2['module'])) {
			$buffer2['module'] = array();
		}

		// Compare the second module buffer against the first buffer.
		$cached['module'] = array_diff_assoc($buffer2['module'], $buffer1['module']);
			
		return $cached;
	}
	
	/**
	 * Create safe id for cached data from url parameters set by plugins and framework
	 * @return	string	md5 encoded cacheid
	 * @since	1.6
	 */

	static function makeId() {
		
		$app = & JFactory::getApplication();
		// get url parameters set by plugins
		$registeredurlparams = $app->get('registeredurlparams');

		if (empty($registeredurlparams)) {
			$registeredurlparams=new stdClass();
		}
		// framework defaults
		$registeredurlparams->protocol='WORD';
		$registeredurlparams->option='WORD';
		$registeredurlparams->view='WORD';
		$registeredurlparams->layout='WORD';
		$registeredurlparams->tpl='CMD';
		$registeredurlparams->id='INT';

		$safeuriaddon=new stdClass();

		foreach ($registeredurlparams AS $key => $value) {
			$safeuriaddon->$key = JRequest::getVar($key, null,'default',$value);

		}

		return md5(serialize($safeuriaddon));
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
	

}