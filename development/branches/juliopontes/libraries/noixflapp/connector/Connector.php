<?php
/**
 * @version		$Id: connector.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

/**
 * Application Connectors Object.
 *
 * @package	NoixFLAPP.Framework
 * @since	1.0
 */
class JFlappConnector
{
	/**
	 * Get instance of connector type
	 * 
	 * @param String $connectorType
	 * @return mixed
	 * @since 1.0
	 */
	public static function getInstance($connectorType,$name,$config=array())
	{
		$connectorTypeInstance = false;
		
		if($file = JPath::find(self::addIncludePath(),$connectorType.DS.$name.DS.$name.'.php')){
			require_once $file;
			
			$className = 'JFlappConnector'.$connectorType.$name;
			$connectorInstance = new $className($config);
			$connectorTypeInstance = $connectorInstance->connect();
		}
		
		return $connectorTypeInstance;
	}
	
	/**
	 * add Connector Path
	 *
	 * @access	public
	 * @param	string	A path to search.
	 * @return	array	An array with directory elements
	 * @since	1.0
	 */
	public static function addIncludePath( $path='' )
	{
		static $paths;

		if (!isset($paths)) {
			//default dir
			$basePath = dirname(__FILE__);
			$paths = array( $basePath );
		}

		// force path to array
		settype($path, 'array');

		// loop through the path directories
		foreach ($path as $dir)
		{
			if (!empty($dir) && !in_array($dir, $paths)) {
				
				$dir = trim($dir);
				// Remove double slashes and backslahses and convert all slashes and backslashes to DS
				$dir = preg_replace('#[/\\\\]+#', DS, $dir);
				
				array_unshift($paths, $dir);
			}
		}

		return $paths;
	}
}