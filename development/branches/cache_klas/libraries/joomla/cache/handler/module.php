<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @copyright	Copyright (C) 2010 Klas Berlič
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

/**
 * Joomla! Cache module handler
 *
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @since		1.6
 */
class JCacheHandlerModule extends JCacheHandler
{
	/**
	* Constructor
	*
	* @param array $options optional parameters
	*/
	public function __construct($options = array())
	{
		parent::__construct($options);
	}
	/**
	 * Get the cached module data
	 *
	 * @param	string	$modulehelper	The modulehelper class to cache output for
	 * @param	string	$methodarr	The method name of the modulehelper class to cache output for
	 * @param	string	$id		The cache data id
	 * @return	mixed	Result of the function call (either from cache or function)
	 * @since	1.6
	 */
	public function get($modulehelper, $methodarr, $id=false, $wrkarounds=false)
	{
		// Initialise variables.
		$data = false;
		
		$method=$methodarr[0];
		$args=$methodarr[1];
		
		// If an id is not given generate it from the request
		if ($id == false) {
			$id = $this->_makeId($modulehelper, $method);
		}

		$data = $this->cache->get($id);
		
		$locktest = new stdClass;
		$locktest->locked = null;
		$locktest->locklooped = null;
		
		if ($data === false) 
		{
			$locktest = $this->cache->lock($id,null);
			if ($locktest->locked == true && $locktest->locklooped == true) $data = $this->cache->get($id);
		
		}
		
		if ($data !== false) {
		
			$cached = unserialize($data);
			$output = $wrkarounds==false ? $cached['output'] : JCache::getWorkarounds($cached['output']);
			$result = $cached['result'];
			if ($locktest->locked == true) $this->cache->unlock($id);
		
		} else {

		/*
		 * No hit so we have to execute the view
		 */
			if ($locktest->locked == false) $locktest = $this->cache->lock($id,null);
			// Capture and echo output
			ob_start();
			ob_implicit_flush(false);
			$result = call_user_func(array($modulehelper,$method),$args);
			$output= ob_get_contents();
			ob_end_clean();

			/*
			 * For a view we have a special case.  We need to cache not only the output from the view, but the state
			 * of the document head after the view has been rendered.  This will allow us to properly cache any attached
			 * scripts or stylesheets or links or any other modifications that the view has made to the document object
			 */
			
			$cached = array();
			$cached['output'] = $wrkarounds==false ? $output : JCache::setWorkarounds($output);
			$cached['result'] = $result;
			
			// Store the cache data
			$this->cache->store(serialize($cached), $id);
			if ($locktest->locked == true) $this->cache->unlock($id);
		}
		
		echo $output;
		return $result;
	}

	/**
	 * Generate a module cache id
	 *
	 * @param	object	$modulehelper	The view object to cache output for
	 * @param	string	$method	The method name to cache for the view object
	 * @return	string	MD5 Hash : module cache id
	 * @since	1.6
	 */
	private function _makeId(&$modulehelper, $method)
	{
		return md5(serialize( array( JRequest::getVar('Itemid',null,'default','INT'), $modulehelper, $methodarr)));
	}
}
