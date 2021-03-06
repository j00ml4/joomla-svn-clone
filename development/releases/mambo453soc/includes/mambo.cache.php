<?php
/**
* @version $Id: mambo.cache.php,v 1.1 2005/08/25 14:21:09 johanjanssens Exp $
* @package Mambo
* @copyright (C) 2000 - 2005 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mosConfig_absolute_path . '/includes/Cache/Lite.php' );

/**
* Base class for caching handlers
* @package Mambo
* @abstract
*/
class mosCache extends Cache_Lite {

	 var $_defaultGroup  = 'mosCache';
	 var $_validateCache = false;

	/**
	* Constructor
	*
	* $options is an assoc. To have a look at availables options,
	* see the constructor of the Cache_Lite class in 'Cache_Lite.php'
	*
	* Comparing to Cache_Lite constructor, there is another option :
	* $options = array(
	*	 (...) see Cache_Lite constructor
	*	 'defaultGroup' => default cache group for function caching (string)
	* );
	*
	* @param array $options options
	* @access public
	*/

	function mosCache($options = array(NULL)){
		$this->_construct($options);
	}

	/**
	 * Constructor
	 *
	 * @param array $options options
	 * @access protected
	 */
	function _construct($options) {
		if (isset($options['defaultGroup'])) {
			$this->_defaultGroup = $options['defaultGroup'];
		}
		$this->Cache_Lite($options);
	}

   /**
    * Enable/disbale caching, if caching is enabled
    *
    * @param boolean $enable If true enable caching.
    * @access public
    */
	function setCaching($enable) {
		if($this->_caching) {
			$this->_caching = $enable;
		}
		return $this->_caching;
	}

	 /**
    * Enable/disbale cache validation
    *
    * @param boolean $validateCache If true enable cache validation.
    * @access public
    */
	function setCacheValidation($validateCache) {
		$this->_validateCache = $validateCache;
	}

	 /**
    * Make a control key with the string containing datas
    *
    * @param string $data data
    * @param string $controlType type of control 'md5', 'crc32' or 'strlen'
    * @return string control key
    * @access public
    */
	function generateId($data, $controlType = 'md5') {
		return $this->_hash($data, $controlType);
	}

	/**
	* Cleans the cache
	*/
	function cleanCache( $group=false, $mode='ingroup' ) {
		global $mosConfig_caching, $mosConfig_absolute_path;

		if ( $mosConfig_caching ) {
			$cache =& mosCache::getCache( $group );
			$cache->clean( $group, $mode );

			// delete feedcreator syndication cache files
			$path 	= $mosConfig_absolute_path .'/cache/';
			$files = mosReadDirectory( $path, '.xml' );
			foreach ( $files as $file ) {
				$file = $path . $file;
				unlink( $file );
			}
		}
	}

	/**
	* Deprecated, use mosFactory createCache instead
	* @since 4.5.3
	*/
	function &getCache(  $group=''  ) {
		return mosFactory::getCache($group);
	}
}

/**
* Class to support function caching
* @package Mambo
*/
class mosCache_Function extends mosCache
{
	/**
	 * Constructor
	 *
	 * @param array $options options
	 * @access protected
	 */
	function _construct($options) {
		parent::_construct($options);
	}

	/**
	 * Calls a cacheable function or method (or not if there is already a cache for it)
	 *
	 * Arguments of this method are read with func_get_args. So it doesn't appear
	 * in the function definition. Synopsis :
	 * call('functionName', $arg1, $arg2, ...)
	 * (arg1, arg2... are arguments of 'functionName')
	 *
	 * @return mixed result of the function/method
	 * @access public
	 */
	function call() {
		$array = func_get_args();
		$function = $array[0];
		unset( $array[0] );
		return $this->callId( $function, $array, serialize( $array ) );
	}

	/**
	 * Calls a cacheable function or method (or not if there is already a cache for it)
	 * and specify a specific id
	 *
	 * @param string Function to call
	 * @param array  Argument of the function
	 * @param id	 Cache id
	 * @return mixed result of the function/method
	 * @access public
	 */
	function callId( $target, $arguments, $id ){
		$id = $this->generateId($id); // Generate a cache id

		$data = $this->get( $id, $this->_defaultGroup, !$this->_validateCache );
		if ($data !== false) {
			$array = unserialize( $data );
			$output = $array['output'];
			$result = $array['result'];
		} else {
			ob_start();
			ob_implicit_flush( false );

			//$target = array_shift($arguments);
			if (strstr( $target, '::' )) { // classname::staticMethod
				list( $class, $method ) = explode( '::', $target );
				$result = call_user_func_array( array( $class, $method ), $arguments );
			} else if (strstr( $target, '->' )) { // object->method
				// use a stupid name ($objet_123456789 because) of problems when the object
				// name is the same as this var name
				list( $object_123456789, $method ) = explode('->', $target);
				global $$object_123456789;
				$result = call_user_func_array( array( $$object_123456789, $method ), $arguments );
			} else { // function
				$result = call_user_func_array( $target, $arguments );
			}

			$output = ob_get_contents();
			ob_end_clean();

			$array['output'] = $output;
			$array['result'] = $result;
			$this->save( serialize( $array ), $id, $this->_defaultGroup );
		}
		echo $output;
		return $result;
	}
}

/**
* Class to support output caching
* @package Mambo
*/
class mosCache_Output extends mosCache {
	/**
	 * Constructor
	 *
	 * @param array $options options
	 * @access protected
	 */
	function _construct( $options ) {
		 parent::_construct($options);
	}

	/**
	 * Start the cache
	 *
	 * @param string $id cache id
	 * @param string $group name of the cache group
	 * @return boolean true if the cache is hit (false else)
	 * @access public
	 */
	function start( $id, $group = 'default') {
		$data = $this->get($id, $group, !$this->_validateCache);
		if ($data !== false) {
			echo($data);
			return true;
		} else {
			ob_start();
			ob_implicit_flush( false );
			return false;
		}
	}

	/**
	 * Stop the cache
	 *
	 * @access public
	 */
	function end() {
		$data = ob_get_contents();
		ob_end_clean();
		$this->save( $data, $this->_id, $this->_group );
		echo( $data );
	}
}

/**
* Class to support page caching
* @package Mambo
*/
class mosCache_Page extends mosCache {

	/**
	 * Constructor
	 *
	 * @param array $options options
	 * @access protected
	 */
	function _construct($options) {
		 parent::_construct($options);
	}

	/**
    * Enable/disbale caching
    *
    * @param boolean $enable If true enable caching.
    * @access public
    */
	function setCaching($enable) {
		$this->_caching = $enable;
	}

	/**
	 * Start the cache
	 *
	 * @param string $id cache id
	 * @param string $group name of the cache group
	 * @return boolean true if the cache is hit (false else)
	 * @access public
	 */
	function start( $id, $group = 'default' ) {

		if ( !headers_sent() && isset($_SERVER['HTTP_IF_NONE_MATCH']) ){
			$etag = stripslashes( $_SERVER['HTTP_IF_NONE_MATCH'] );
			if( $etag == $id) {
				$this->sendNoChangeHttpHeader($id);
				return true;
			}
		}

		$data = $this->get($id, $group, !$this->_validateCache);

		if ($data !== false) {
			$this->sendEtagHttpHeader($this->_id);
			echo($data);
			return true;
		} else {
			ob_start();
			ob_implicit_flush( false );
			return false;
		}
	}

	/**
	 * Stop the cache
	 *
	 * @access public
	 */
	function end(){
		$data = ob_get_contents();
		ob_end_clean();

		$this->save( $data, $this->_id, $this->_group );
		echo $data;
	}

	function generateId($data) {
		return md5(serialize($data));
	}

	function sendNoChangeHttpHeader(){
		header( 'HTTP/1.x 304 Not Modified', true );
	}

	function sendEtagHttpHeader($md5) {
		header( 'Etag: '.$md5 );
	}
}

/**
* Class to support caching of a String and returning a URL or File Path to it
* @package Mambo
* @copyright (C) 2005 Richard Allinson www.ratlaw.co.uk
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/
class mosCache_File extends mosCache
{
	var $_urlToCache = null;
	
	function _construct( $options )
	{
		$options['fileNameProtection'] = false;
		$options['readControl'] = false;
		$options['caching'] = true;
		
		parent::_construct( $options );
	}
	
	function setCacheUrl( $urlToCache )
	{
		$this->_urlToCache = $urlToCache;
	}
    
    function _buildUrl()
    {
    	return $this->_urlToCache ? $this->_urlToCache.'/'.$this->_fileName : null;
    }
	
	function getUrl( $data, $type )
	{
		$this->save( $data, md5( $data ).$type, $this->_defaultGroup );
		return $this->_buildUrl();
	}
	
	function callUrl( $target, $arguments, $id )
	{
		$this->callPath( $target, $arguments, $id );
		return $this->_buildUrl();
	}
	
	function getPath( $data, $type )
	{
		$this->save( $data, md5( $data ).$type, $this->_defaultGroup );
		return $this->_file;
	}
	
    function callPath( $target, $arguments, $id )
    {
		$data = $this->get( $id, $this->_defaultGroup, true );
	    
		if ( $data == false )
		{ 
			ob_start();
			ob_implicit_flush(false);
		 
			list( $class, $method ) = explode( '::', $target );
			call_user_func_array( array( $class, $method ), $arguments );
		  
			$output = ob_get_contents();
			ob_end_clean();
		 
			$this->save( $output, $id, $this->_defaultGroup );
		}
		else
		{
			$this->_setFileName( $id, $this->_defaultGroup );
		}
		
		return $this->_file;
    }
}
?>
