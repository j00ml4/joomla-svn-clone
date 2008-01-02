<?php
/**
 * @package Joomla
 * @subpackage UnitTest
 * @copyright    Copyright (C) 2005 - 2007 Open Source Matters, Inc.
 * @license        GNU/GPL, see LICENSE.php
 * @version $Id: loader.php 9743 2007-12-24 04:18:48Z instance $

 *  * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
return;
if (! defined('DS')) {
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
//                $rs += JUnit_Setup::loadFile(basename($file), null, true);
				$rs += (require_once $file);
			}
			$trs = (int)(bool)$rs;
		} else {
			$path = str_replace('.', DS, $filePath);
			$path .= '.php';
//echo '<br> - ',$path;
//            $trs = JUnit_Setup::loadFile(basename($path), null, true);
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

		$classes = JUnit_Loader::register();
		if(array_key_exists(strtolower($class), $classes)) {
			include($classes[$class]);
			return true;
		}
		return false;
	}

	/**
	 * Add a class to autoload
	 *
	 * @param   string $classname   The class name
	 * @param   string $file        Full path to the file that holds the class
	 * @return  array|boolean       Array of classes
	 * @since   1.5
	 */
	function & register ($class = null, $file = null)
	{
		static $classes;

		if(!isset($classes)) {
			$classes    = array();
		}

		if($class && is_file($file))
		{
			$class = strtolower($class); //force to lower case
			$classes[$class] = $file;

			// In php4 we load the class immediately
			if((version_compare( phpversion(), '5.0' ) < 0)) {
				JUnit_Loader::load($class);
			}

		}

		return $classes;
	}

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
	return JUnit_Loader::import($path);
	$filepath = JPATH_BASE . 'libraries' . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $path);
	require_once($filepath);
	return true;
}


