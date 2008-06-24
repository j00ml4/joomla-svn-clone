<?php
/**
 * @version $Id$
 * @package		Joomla.Framework
 * @subpackage 	MockObjects
 * @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

if (!defined('DS')) {
	define( 'DS', DIRECTORY_SEPARATOR );
}

class JUTLoader
{
	 /**
	  * Loads a class from specified directories.
	  *
	  * @param string $name	The class name to look for ( dot notation ).
	  * @param string $base	Search this directory for the class.
	  * @param string $key	String used as a prefix to denote the full path of the file ( dot notation ).
	  * @return boolean True if the requested class has been successfully included
	  * @since 1.5
	  */
	function import( $filePath, $base = null, $key = null )
	{
		if ( $key ) {
			$keyPath = $key . $filePath;
		} else {
			$keyPath = $filePath;
		}

		$trs   = 1;
		$parts = explode( '.', $filePath );

		if ( ! $base ) {
			$base = 'libraries';
		}

		if (array_pop( $parts ) == '*') {
			$path = $base . DS . implode( DS, $parts );
			$rs   = 0;
			foreach ($files = glob($base . DS . $path . '/*.php') as $file) {
//echo '<br> * ',$file;
//				$rs += UnitTestHelper::loadFile(basename($file), null, true);
				$rs += (require_once $file);
			}
			$trs = (int)(bool)$rs;
		} else {
			$path  = str_replace( '.', DS, $filePath );
			$path .= '.php';
//echo '<br> - ',$path;
//			$trs  = UnitTestHelper::loadFile(basename($path), null, true);
			$trs  = (require_once $base . DS . $path);
		}

		return $trs;
	}

}

/**
 * Even more intelligent file importer
 *
 * @access public
 * @param string $path A dot syntax path
 * @since 1.5
 */
function jimport( $path ) {
	return JUTLoader::import($path, null, 'libraries.' );
}

