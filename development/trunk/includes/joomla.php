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

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );
define( '_MOS_MAMBO_INCLUDED', 1 );

class JBase 
{
    /**
    * Loads a class from specified directories.
    *
    * @param string $name The class name to look for.
    * @param string|array $dirs Search these directories for the class.
    * @return void
    * @since 1.1
    */
   function import($filePath) 
   {
	   global  $mosConfig_absolute_path;
			
		$parts = explode('.', $filePath);
		
		$base =  $mosConfig_absolute_path. DIRECTORY_SEPARATOR . 'libraries';		
		
		if(array_pop($parts) == '*') 
		{
			$path = $base.DIRECTORY_SEPARATOR.implode( DIRECTORY_SEPARATOR, $parts);
		
			if(!is_dir($path)) {
				return; //TODO : throw error
			}
			
			$dir = dir($path);
			while($file = $dir->read()) {
				if(ereg('\.php$', $file)) {	
					include_once $path.DIRECTORY_SEPARATOR.$file;
				}
			}
			$dir->close();
		} 
		else 
		{
			$path = str_replace('.', DIRECTORY_SEPARATOR, $filePath); 
			
			$found = false;
			foreach(array('.php', '.class.php', 'lib.php') as $suffix) {
				if(file_exists($base.DIRECTORY_SEPARATOR.$path.$suffix)) {
					$found = true;
					break;
				}
			}
			
			if($found) {
				include_once $base.DIRECTORY_SEPARATOR.$path.$suffix;
			} else {
				return;  //TODO : throw error
			}
		}
	
		return;
	}

   /**
    * A common object factory.
    *     * Assumes that the class constructor takes only one parameter, an
    * associative array of construction options.
    *     * Attempts to load the class automatically.
    *
    * @param string $class The class name to instantiate.
    * @param array $options An associative array of options (default null).
    * @return object An object instance.
    */
   function &factory($class, $options = null) {
       JBase::import($class);
       $obj = new $class($options);
       return $obj;
   }
}

/**
 * Intelligent file importer
 * @param string A dot syntax path
 * @param boolean True to use require_once, false to use require
 */
function jimport( $path ) {
	JBase::import($path);
}

if (phpversion() < '4.2.0') {
	jimport('joomla.compat.php41x' );
}
if (phpversion() < '4.3.0') {
	jimport('joomla.compat.php42x' );
}
if (in_array( 'globals', array_keys( array_change_key_case( $_REQUEST, CASE_LOWER ) ) ) ) {
	die( 'Fatal error.  Global variable hack attempted.' );
}
if (in_array( '_post', array_keys( array_change_key_case( $_REQUEST, CASE_LOWER ) ) ) ) {
	die( 'Fatal error.  Post variable hack attempted.' );
}
if (version_compare( phpversion(), '5.0' ) < 0) {
	jimport('joomla.compat.php50x' );
}

@set_magic_quotes_runtime( 0 );

if (@$mosConfig_error_reporting === 0) {
	error_reporting( 0 );
} else if (@$mosConfig_error_reporting > 0) {
	error_reporting( $mosConfig_error_reporting );
}

// experimenting

jimport( 'joomla.database.mysql' );

require_once( $mosConfig_absolute_path . '/includes/phpmailer/class.phpmailer.php' );

jimport( 'phpinputfilter.inputfilter' );
jimport( 'joomla.classes.object' );
jimport( 'joomla.version' );
jimport( 'joomla.functions' );
jimport( 'joomla.classes' );
jimport( 'joomla.models.*' );
jimport( 'joomla.html' );
jimport( 'joomla.factory' );
jimport( 'joomla.files' );
jimport( 'joomla.xml' );


/** @global $database */
$database =& JFactory::getDBO();

/** @global $acl */
$acl =& JFactory::getACL();

/** @global $_MAMBOTS */
$_MAMBOTS = new mosMambotHandler();

/** @global $_VERSION */
$_VERSION = new JVersion();

//TODO : implement mambothandler class as singleton, add getBotHandler to JFactory

//TODO : implement editor functionality as a class
jimport( 'joomla.editor' );


//TODO : implement mambothandler class as singleton, add getVersion to JFactory
jimport( 'joomla.legacy' );
?>
