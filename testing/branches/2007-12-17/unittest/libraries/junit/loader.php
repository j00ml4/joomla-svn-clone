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

if (! defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
/**
 * This is a copy of the "real" JLoader...
 */
class JLoader
{
	 /**
	 * Loads a class from specified directories.
	 *
	 * @param string $name  The class name to look for ( dot notation ).
	 * @param string $base  Search this directory for the class.
	 * @param string $key   String used as a prefix to denote the full path of the file ( dot notation ).
	 * @return void
	 * @since 1.5
	 */
	function import( $filePath, $base = null, $key = 'libraries.' )
	{
		static $paths;

		if (!isset($paths)) {
			$paths = array();
		}

		$keyPath = $key ? $key . $filePath : $filePath;

		if (!isset($paths[$keyPath]))
		{
			if ( ! $base ) {
				$base =  JPATH_BASE . DS . 'libraries';
			}

			$parts = explode( '.', $filePath );

			$classname = array_pop( $parts );
			switch($classname)
			{
				case 'helper' :
					$classname = ucfirst(array_pop( $parts )).ucfirst($classname);
					break;

				default :
					$classname = ucfirst($classname);
					break;
			}

			$path  = str_replace( '.', DS, $filePath );

			if (strpos($filePath, 'joomla') === 0)
			{
				//If we are loading a joomla class prepend the classname with a capital J
				$classname  = 'J'.$classname;
				$classes    = JLoader::register($classname, $base.DS.$path.'.php');
				$rs         = isset($classes[strtolower($classname)]);
			}
			else
			{
				// If it is not in the joomla namespace then we have no idea if it uses our pattern
				// for class names/files so just include.
				$rs   = include($base.DS.$path.'.php');
			}

			$paths[$keyPath] = $rs;
		}

		return $paths[$keyPath];
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
				JLoader::load($class);
			}

		}

		return $classes;
	}


	/**
	 * Load the file for a class
	 *
	 * @access  public
	 * @param   string  $class  The class that will be loaded
	 * @return  boolean True on success
	 * @since   1.5
	 */
	function load( $class )
	{
		$class = strtolower($class); //force to lower case

		if (class_exists($class)) {
			  return;
		}

		$classes = JLoader::register();
		if(array_key_exists( strtolower($class), $classes)) {
			include($classes[$class]);
			return true;
		}
		return false;
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
	$parts = explode('_', $class);
	switch (strtolower($parts[0])) {
		case 'junit': {
			require_once 'libraries' . DS . strtolower(implode(DS, $parts)) . '.php';
			return true;
		}
		break;

		case 'phpunit': {
			require_once implode(DS, $parts) . '.php';
			return true;
		}
		break;

		default: {
			if (JLoader::load($class)) {
				return true;
			}
		}
		break;
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
	return JLoader::import($path);
	/*
	$filepath = JPATH_BASE . 'libraries' . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $path);
	require_once $filepath;
	return true;
	*/
}


