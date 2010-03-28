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
 * Joomla! Cache view type object
 *
 * @package		Joomla.Framework
 * @subpackage	Cache
 * @since		1.5
 */
class JCacheModule extends JCache
{
	/**
	 * Get the cached module data
	 *
	 * @access	public
	 * @param	string	$modulehelper	The modulehelper class to cache output for
	 * @param	string	$methodarr	The method name of the modulehelper class to cache output for
	 * @param	string	$id		The cache data id
	 * @return	mixed	Result of the function call (either from cache or function)
	 * @since	1.5
	 */
	function get($modulehelper, $methodarr, $id=false, $wrkarounds=false)
	{
		// Initialise variables.
		$data = false;
		
		$method=$methodarr[0];
		$args=$methodarr[1];
		
		// If an id is not given generate it from the request
		if ($id == false) {
			$id = $this->_makeId($modulehelper, $method);
		}

		$data = parent::get($id);
		
		if ($data !== false) {
		
			$cached = $wrkarounds==false ? unserialize($data) : parent::getWorkarounds(unserialize($data));
			
			$output = $cached['output'];
			$result = $cached['result'];
		
		} else {

		/*
		 * No hit so we have to execute the view
		 */

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
			$cached['output'] = $wrkarounds==false ? $output : parent::setWorkarounds($output);
			$cached['result'] = $result;
			
			// Store the cache data
			$this->store(serialize($cached), $id);
		}
		
		echo $output;
		return $result;
	}

	/**
	 * Generate a view cache id
	 *
	 * @access	private
	 * @param	object	$modulehelper	The view object to cache output for
	 * @param	string	$method	The method name to cache for the view object
	 * @return	string	MD5 Hash : view cache id
	 * @since	1.5
	 */
	function _makeId(&$modulehelper, $method)
	{
		return md5(serialize( array( JRequest::getVar('Itemid',null,'default','INT'), $modulehelper, $methodarr)));
	}
}
