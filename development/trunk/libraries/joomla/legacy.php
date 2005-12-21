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

/* _ISO defined not used anymore. All output is forced as utf-8 */
DEFINE('_ISO','charset=utf-8');

/**
* Legacy constant, use _JEXEC instead
* @deprecated As of version 1.1
*/
define( '__VALID_MOS', 1 );

/**
* Legacy function, use JPath::clean instead
* @deprecated As of version 1.1
*/
function mosPathName($p_path, $p_addtrailingslash = true) {
	return JPath::clean( $p_path, $p_addtrailingslash );
}

/**
* Legacy function, use JFolder::files or JFolder::folders instead
* @deprecated As of version 1.1
*/
function mosReadDirectory( $path, $filter='.', $recurse=false, $fullpath=false  ) {
	$arr = array(null);

	// Get the files and folders
	$files = JFolder::files($path, $filter, $recurse, $fullpath);
	$folders = JFolder::folders($path, $filter, $recurse, $fullpath);
	// Merge files and folders into one array
	$arr = array_merge($files, $folders);
	// Sort them all
	asort($arr);
	return $arr;
}

/**
 * Legacy function, use JFolder::delete($path)
 * @deprecated As of version 1.1
 */
function deldir( $dir ) {
	$current_dir = opendir( $dir );
	$old_umask = umask(0);
	while ($entryname = readdir( $current_dir )) {
		if ($entryname != '.' and $entryname != '..') {
			if (is_dir( $dir . $entryname )) {
				deldir( mosPathName( $dir . $entryname ) );
			} else {
                @chmod($dir . $entryname, 0777);
				unlink( $dir . $entryname );
			}
		}
	}
	umask($old_umask);
	closedir( $current_dir );
	return rmdir( $dir );
}

/**
* Legacy function, use JFolder::create
* @deprecated As of version 1.1
*/
function mosMakePath($base, $path='', $mode = NULL) {
	return JFolder::create($base.$path, $mode);
}

/**
 * Legacy function, use JPath::setPermissions instead
 * @deprecated As of version 1.1
 */
function mosChmod( $path ) {
	return JPath::setPermissions( $path );
}

/**
 * Legacy function, use JPath::setPermissions instead
 * @deprecated As of version 1.1
 */
function mosChmodRecursive( $path, $filemode=NULL, $dirmode=NULL ) {
	return JPath::setPermissions( $path, $filemode, $dirmode );
}

/**
* Legacy function, use JPath::canCHMOD
* @deprecated As of version 1.1
*/
function mosIsChmodable( $file ) {
	return JPath::canChmod( $file );
}

/**
* Legacy function, replaced by geshi bot
* @deprecated As of version 1.1
*/
function mosShowSource( $filename, $withLineNums=false ) {

	ini_set('highlight.html', '000000');
	ini_set('highlight.default', '#800000');
	ini_set('highlight.keyword','#0000ff');
	ini_set('highlight.string', '#ff00ff');
	ini_set('highlight.comment','#008000');

	if (!($source = @highlight_file( $filename, true ))) {
		return JText::_( 'Operation Failed' );
	}
	$source = explode("<br />", $source);

	$ln = 1;

	$txt = '';
	foreach( $source as $line ) {
		$txt .= "<code>";
		if ($withLineNums) {
			$txt .= "<font color=\"#aaaaaa\">";
			$txt .= str_replace( ' ', '&nbsp;', sprintf( "%4d:", $ln ) );
			$txt .= "</font>";
		}
		$txt .= "$line<br /><code>";
		$ln++;
	}
	return $txt;
}

/**
* Legacy function, use mosLoadModules('breadcrumbs); instead
* @deprecated As of version 1.1
*/
function mosPathWay() {
	mosLoadModules('breadcrumbs', -1);
}


/**
* Legacy class, derive from JApplication instead
* @deprecated As of version 1.1
*/
class mosMainFrame extends JApplication {
	/**
	 * Class constructor
	 * @param database A database connection object
	 * @param string The url option [DEPRECATED]
	 * @param string The path of the mos directory [DEPRECATED]
	 */
	function __construct( &$db, $option, $basePath=null, $client=0 ) {
		parent::__construct( $option, $client );
	}

	/**
	 * Class constructor
	 * @param database A database connection object
	 * @param string The url option [DEPRECATED]
	 * @param string The path of the mos directory [DEPRECATED]
	 */
	function mosMainFrame( &$db, $option, $basePath=null, $client=0 ) {
		parent::__construct( $option, $client );
	}

	/**
	 * Initialises the user session
	 *
	 * Old sessions are flushed based on the configuration value for the cookie
	 * lifetime. If an existing session, then the last access time is updated.
	 * If a new session, a session id is generated and a record is created in
	 * the mos_sessions table.
	 */
	function initSession( ) {
		//do nothing, contructor handles session creation
	}
	
	/**
	 * Gets the base path for the client
	 * @param mixed A client identifier
	 * @param boolean True (default) to add traling slash
	 */
	function getBasePath( $client=0, $addTrailingSlash=true ) {
		global $mosConfig_absolute_path;

		switch ($client) {
			case '0':
			case 'site':
			case 'front':
			default:
				return mosPathName( $mosConfig_absolute_path, $addTrailingSlash );
				break;

			case '2':
			case 'installation':
				return mosPathName( $mosConfig_absolute_path . '/installation', $addTrailingSlash );
				break;

			case '1':
			case 'admin':
			case 'administrator':
				return mosPathName( $mosConfig_absolute_path . '/administrator', $addTrailingSlash );
				break;

		}
	}
}

/**
* Legacy class, derive from JModel instead
* @deprecated As of version 1.1
*/
jimport( 'joomla.models.model' );
/**
 * @package Joomla
 * @deprecated As of version 1.1
 */
class mosDBTable extends JModel {
	/**
	 * Constructor
	 */
	function __construct($table, $key, &$db) {
		parent::__construct( $table, $key, $db );
	}

	function mosDBTable($table, $key, &$db) {
		parent::__construct( $table, $key, $db );
	}
}

/**
* Legacy class, derive from JModel instead
* @deprecated As of version 1.1
*/
jimport( 'joomla.database.database' );
jimport( 'joomla.database.adapters.mysql' );
/**
 * @package Joomla
 * @deprecated As of version 1.1
 */
class database extends JDatabaseMySQL {
	function __construct ($host='localhost', $user, $pass, $db='', $table_prefix='', $offline = true) {
		parent::__construct( $host, $user, $pass, $db, $table_prefix );
	}
}

/**
* Legacy class, use JFactory::getCache instead
* @deprecated As of version 1.1
*/
class mosCache {
	/**
	* @return object A function cache object
	*/
	function &getCache(  $group=''  ) {
		return JFactory::getCache($group);
	}
	/**
	* Cleans the cache
	*/
	function cleanCache( $group=false ) {
		$cache =& JFactory::getCache($group);
		$cache->cleanCache($group);
	}
}

/**
* Legacy class, use JProfiler instead
* @deprecated As of version 1.1
*/
class mosProfiler extends JProfiler {
	/**
	* @return object A function cache object
	*/
	function JProfiler (  $prefix=''  ) {
		parent::__construct($prefix);
	}
}

/**
 * Legacy class, use JParameters instead
 * @deprecated As of version 1.1
 */
class mosParameters extends JParameters {

	/**
	* @param string The raw parms text
	* @param string Path to the xml setup file
	* @param string The type of setup file
	*/
	function __construct($text, $path = '', $type = 'component') {
		$this->_params = $this->parse($text);
		$this->_raw = $text;
		$this->_path = $path;
		$this->_type = $type;
	}

}

/**
 * Legacy class, will be replaced by full MVC implementation in 1.2
 * @deprecated As of version 1.1
 */
class mosAbstractTasker {
	/** @var array An array of the class methods to call for a task */
	var $_taskMap 	= null;
	/** @var string The name of the current task*/
	var $_task 		= null;
	/** @var array An array of the class methods*/
	var $_methods 	= null;
	/** @var string A url to redirect to */
	var $_redirect 	= null;
	/** @var string A message about the operation of the task */
	var $_message 	= null;

	// action based access control

	/** @var string The ACO Section */
	var $_acoSection 		= null;
	/** @var string The ACO Section value */
	var $_acoSectionValue 	= null;

	/**
	 * Constructor
	 * @param string Set the default task
	 */
	function mosAbstractTasker( $default='' ) {
		$taskMap = array();
		$this->_methods = array();
		foreach (get_class_methods( get_class( $this ) ) as $method) {
			if (substr( $method, 0, 1 ) != '_') {
				$this->_methods[] = strtolower( $method );
				// auto register public methods as tasks
				$this->_taskMap[strtolower( $method )] = $method;
			}
		}
		$this->_redirect = '';
		$this->_message = '';
		if ($default) {
			$this->registerDefaultTask( $default );
		}
	}

	/**
	 * Sets the access control levels
	 * @param string The ACO section (eg, the component)
	 * @param string The ACO section value (if using a constant value)
	 */
	function setAccessControl( $section, $value=null ) {
		$this->_acoSection = $section;
		$this->_acoSectionValue = $value;
	}
	/**
	 * Access control check
	 */
	function accessCheck( $task ) {
		global $acl, $my;

		// only check if the derived class has set these values
		if ($this->_acoSection) {
			// ensure user has access to this function
			if ($this->_acoSectionValue) {
				// use a 'constant' task for this task handler
				$task = $this->_acoSectionValue;
			}
			return $acl->acl_check( $this->_acoSection, $task, 'users', $my->usertype );
		} else {
			return true;
		}
	}

	/**
	 * Set a URL to redirect the browser to
	 * @param string A URL
	 */
	function setRedirect( $url, $msg = null ) {
		$this->_redirect = $url;
		if ($msg !== null) {
			$this->_message = $msg;
		}
	}
	/**
	 * Redirects the browser
	 */
	function redirect() {
		if ($this->_redirect) {
			mosRedirect( $this->_redirect, $this->_message );
		}
	}
	/**
	 * Register (map) a task to a method in the class
	 * @param string The task
	 * @param string The name of the method in the derived class to perform for this task
	 */
	function registerTask( $task, $method ) {
		if (in_array( strtolower( $method ), $this->_methods )) {
			$this->_taskMap[strtolower( $task )] = $method;
		} else {
			$this->methodNotFound( $method );
		}
	}
	/**
	 * Register the default task to perfrom if a mapping is not found
	 * @param string The name of the method in the derived class to perform if the task is not found
	 */
	function registerDefaultTask( $method ) {
		$this->registerTask( '__default', $method );
	}
	/**
	 * Perform a task by triggering a method in the derived class
	 * @param string The task to perform
	 * @return mixed The value returned by the function
	 */
	function performTask( $task ) {
		$this->_task = $task;

		$task = strtolower( $task );
		if (isset( $this->_taskMap[$task] )) {
			$doTask = $this->_taskMap[$task];
		} else if (isset( $this->_taskMap['__default'] )) {
			$doTask = $this->_taskMap['__default'];
		} else {
			return $this->taskNotFound( $this->_task );
		}

		if ($this->accessCheck( $doTask )) {
			return call_user_func( array( &$this, $doTask ) );
		} else {
			return $this->notAllowed( $task );
		}
	}
	/**
	 * Get the last task that was to be performed
	 * @return string The task that was or is being performed
	 */
	function getTask() {
		return $this->_task;
	}
	/**
	 * Basic method if the task is not found
	 * @param string The task
	 * @return null
	 */
	function taskNotFound( $task ) {
		echo JText::_( 'Task' ) .' ' . $task . ' '. JText::_( 'not found' );
		return null;
	}
	/**
	 * Basic method if the registered method is not found
	 * @param string The name of the method in the derived class
	 * @return null
	 */
	function methodNotFound( $name ) {
		echo JText::_( 'Method' ) .' ' . $name . ' '. JText::_( 'not found' );
		return null;
	}
	/**
	 * Basic method if access is not permitted to the task
	 * @param string The name of the method in the derived class
	 * @return null
	 */
	function notAllowed( $name ) {
		echo JText::_( 'ALERTNOTAUTH' );

		return null;
	}
}

/**
 * Legacy class, use JEventDispatcher instead
 * @deprecated As of version 1.1
 */
class mosMambotHandler extends JEventDispatcher {
	function __construct() {
		parent::__construct();
	}

	/**
	* Loads all the bot files for a particular group
	* @param string The group name, relates to the sub-directory in the mambots directory
	*/
	function loadBotGroup( $group ) {
		return JPluginHelper::importGroup($group);
	}
	/**
	 * Loads the bot file
	 * @param string The folder (group)
	 * @param string The elements (name of file without extension)
	 * @param int Published state
	 * @param string The params for the bot
	 */
	function loadBot( $folder, $element, $published, $params='' ) {
		return JPluginHelper::import($folder, $element, $published, $params='' );
	}
	
	/**
	* Registers a function to a particular event group
	* 
	* @param string The event name
	* @param string The function name
	*/
	function registerEvent( $event, $function ) {
		$this->attach(array( $event => $function ));
	}
}

/**
 * Legacy class, removed
 * @deprecated As of version 1.1
 */
class mosEmpty {
	function def( $key, $value='' ) {
		return 1;
	}
	function get( $key, $default='' ) {
		return 1;
	}
}
/**
 * Legacy global
 * 	use JApplicaiton->registerEvent and JApplication->triggerEvent for event handling
 *  use JBotLoader::importBot and JBotLoader::import to load bot code
 *  @deprecated As of version 1.1
 */
$_MAMBOTS = new mosMambotHandler();

/**
* Legacy function, use JApplication::getBrowser() instead
* @deprecated As of version 1.1
*/
function mosGetBrowser( $agent ) {
	$browser = JApplication::getBrowser();
	return $browser->getBrowser();
}

/**
* Legacy function, use JApplication::getBrowser() instead
* @deprecated As of version 1.1
*/
function mosGetOS( $agent ) {
	$browser = JApplication::getBrowser();
	return $browser->getPlatform();
}

/**
* Legacy function, use mosParameters::parse()
* @deprecated As of version 1.1
*/
function mosParseParams( $txt ) {
	return mosParameters::parse( $txt );
}

/**
* Legacy function, handled by JTemplate Zlib outputfilter
* @deprecated As of version 1.1
*/
function initGzip() {
	global $mosConfig_gzip, $do_gzip_compress;
	$do_gzip_compress = FALSE;
	if ($mosConfig_gzip == 1) {
		$phpver = phpversion();
		$useragent = mosGetParam( $_SERVER, 'HTTP_USER_AGENT', '' );
		$canZip = mosGetParam( $_SERVER, 'HTTP_ACCEPT_ENCODING', '' );

		if ( $phpver >= '4.0.4pl1' &&
				( strpos($useragent,'compatible') !== false ||
				  strpos($useragent,'Gecko')	  !== false
				)
			) {
			if ( extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
				// You cannot specify additional output handlers if
				// zlib.output_compression is activated here
				ob_start("ob_gzhandler" );
				return;
			}
		} else if ( $phpver > '4.0' ) {
			if ( strpos($canZip,'gzip') !== false ) {
				if (extension_loaded( 'zlib' )) {
					$do_gzip_compress = TRUE;
					ob_start();
					ob_implicit_flush(0);

					header( 'Content-Encoding: gzip' );
					return;
				}
			}
		}
	}
	ob_start();
}

/**
* Legacy function, handled by JTemplate Zlib outputfilter
* @deprecated As of version 1.1
*/
function doGzip() {
	global $do_gzip_compress;
	if ( $do_gzip_compress ) {
		/**
		*Borrowed from php.net!
		*/
		$gzip_contents = ob_get_contents();
		ob_end_clean();

		$gzip_size = strlen($gzip_contents);
		$gzip_crc = crc32($gzip_contents);

		$gzip_contents = gzcompress($gzip_contents, 9);
		$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

		echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		echo $gzip_contents;
		echo pack('V', $gzip_crc);
		echo pack('V', $gzip_size);
	} else {
		ob_end_flush();
	}
}

/**
* Legacy function, use $_VERSION->getLongVersion() instead
* @deprecated As of version 1.1
*/
global $_VERSION;
$version = $_VERSION->PRODUCT .' '. $_VERSION->RELEASE .'.'. $_VERSION->DEV_LEVEL .' '
. $_VERSION->DEV_STATUS
.' [ '.$_VERSION->CODENAME .' ] '. $_VERSION->RELDATE .' '
. $_VERSION->RELTIME .' '. $_VERSION->RELTZ;


/**
* Load the site language file (the old way - to be deprecated)
* @deprecated As of version 1.1
*/
global $mosConfig_lang;
$file = JPATH_SITE .'/language/' . $mosConfig_lang .'.php';
if (file_exists( $file )) {
	require_once( $file);
} else {
	$file = JPATH_SITE .'/language/english.php';
	if (file_exists( $file )) {
		require_once( $file );
	}
}

?>
