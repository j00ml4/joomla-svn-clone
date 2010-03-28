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

/**
 * Joomla! Cache view type object
 *
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @since		1.5
 */
class JCacheView extends JCache
{
	/**
	 * Get the cached view data
	 *
	 * @access	public
	 * @param	object	$view	The view object to cache output for
	 * @param	string	$method	The method name of the view method to cache output for
	 * @param	string	$group	The cache data group
	 * @param	string	$id		The cache data id
	 * @return	boolean	True if the cache is hit (false else)
	 * @since	1.5
	 */
	function get(&$view, $method, $id=false, $wrkarounds=true)
	{	
		$data = false;

		// If an id is not given generate it from the request
		if ($id == false) {
			$id = $this->_makeId($view, $method);
		}

		$data = parent::get($id);
		
		if ($data !== false) {
			$data		= unserialize($data);	
			
			if ($wrkarounds === true) {
				echo parent::getWorkarounds($data);
			}
			
			else {  // no workarounds, all data is stored in one piece
				echo (isset($data)) ? $data : null;
			}
			return true;
		}

		/*
		 * No hit so we have to execute the view
		 */
		if (method_exists($view, $method))
		{


			// Capture and echo output
			ob_start();
			ob_implicit_flush(false);
			$view->$method();
			$data = ob_get_contents();
			ob_end_clean();
			echo $data;

			/*
			 * For a view we have a special case.  We need to cache not only the output from the view, but the state
			 * of the document head after the view has been rendered.  This will allow us to properly cache any attached
			 * scripts or stylesheets or links or any other modifications that the view has made to the document object
			 */
			$cached = array();

			$cached = $wrkarounds == true ? parent::setWorkarounds($data) : $data;

			// Store the cache data
			$this->store(serialize($cached), $id);
		}
		return false;
	}

	/**
	 * Generate a view cache id
	 *
	 * @access	private
	 * @param	object	$view	The view object to cache output for
	 * @param	string	$method	The method name to cache for the view object
	 * @return	string	MD5 Hash : view cache id
	 * @since	1.5
	 */
	function _makeId(&$view, $method)
	{	
		return md5(serialize(array(parent::makeId(), get_class($view), $method)));
	}
}
