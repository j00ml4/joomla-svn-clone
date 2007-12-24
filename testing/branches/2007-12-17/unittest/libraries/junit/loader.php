<?php
/**
 * @package Joomla
 * @subpackage UnitTest
 * @copyright    Copyright (C) 2005 - 2007 Open Source Matters, Inc.
 * @license        GNU/GPL, see LICENSE.php
 * @version $Id$

 *  * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

class JUnit_Loader
{
	 /**
	  * Loads a class from specified directories.
	  *
	  * @param string The class name to look for (dot notation).
	  * @param string Search this directory for the class.
	  * @param string String used as a prefix to denote the full path of the
	  * file (dot notation).
	  * @return boolean True if the requested class has been successfully
	  * included.
	  * @since 1.5
	  */
	function import($filePath, $base = null, $key = null)
	{
		if ($key) {
			$keyPath = $key . $filePath;
		} else {
			$keyPath = $filePath;
		}

		$trs = 1;
		$parts = explode('.', $filePath);

		if (! $base) {
			$base = 'libraries';
		}

		if (array_pop($parts) == '*') {
			$path = $base . DS . implode(DS, $parts);
			$rs   = 0;
			foreach ($files = glob($base . DS . $path . '/*.php') as $file) {
//echo '<br> * ',$file;
//                $rs += UnitTestHelper::loadFile(basename($file), null, true);
				$rs += (require_once $file);
			}
			$trs = (int)(bool)$rs;
		} else {
			$path = str_replace('.', DS, $filePath);
			$path .= '.php';
//echo '<br> - ',$path;
//            $trs = UnitTestHelper::loadFile(basename($path), null, true);
			$trs = (require_once $base . DS . $path);
		}

		return $trs;
	}

	/**
	 * Load the file for a class
	 *
	 * @param   string The class that will be loaded
	 * @return  boolean True on success
	 * @since   1.5
	 */
	function load($class)
	{
		$class = strtolower($class); //force to lower case

		if (class_exists($class)) {
			  return;
		}

		$classes = JLoader::register();
		if(array_key_exists(strtolower($class), $classes)) {
			include($classes[$class]);
			return true;
		}
		return false;
	}

}

/**
 * Even more intelligent file importer
 *
 * @access public
 * @param string A dot syntax path
 * @since 1.5
 */
function jimport($path) {
	return JUnit_Loader::import($path, null, 'libraries.');
}
/**
 * Unit test autoloader.
 *
 * This function intercepts the one defined in the framework to allow for the
 * injection of mock classes.
 *
 * @param string The class name to load
 */
function __autoload($class)
{
	if (JUnit_Loader::load($class)) {
		return true;
	}

	return false;
}

/**
 * Unit test intelligent file importer
 *
 * @param string A dot syntax path
 */
function jimport($path)
{
	static $btdt = false;
	if (! $btdt) {
		echo 'this is the overridden jimport.' . PHP_EOL;
		$btdt = true;
	}
	return JUnit_Loader::import($path);
	$filepath = JPATH_BASE . 'libraries' . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $path);
	require_once($filepath);
	return true;
}


