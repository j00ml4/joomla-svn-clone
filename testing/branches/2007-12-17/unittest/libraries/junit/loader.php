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
 * Unit test autoloader.
 *
 * This function intercepts the one defined in the framework to allow for the
 * injection of mock classes. There is a two-phase "boot" process. This function
 * is defined before the JLoader class is defined, which overrides the
 * __autoload() function in the Joomla framework. It initially calls {@see
 * junit_autoload()}. Once JLoader has been defined, the test facility changes
 * the callback function to {@see junit_mockload()} by passing the new callback
 * as an array element.
 *
 * @param string|array when passed as a string, the class name to load. When
 * passed as an array, the first element of the array is the callback function
 * to use.
 */
function __autoload($class)
{
	static $autoloader = 'junit_autoload';

	if (is_array($class)) {
		$autoloader = reset($class);
		return;
	}
	return call_user_func($autoloader, $class);
}

/**
 * Unit test intelligent file importer.
 *
 * This function intercepts the one defined in the framework to allow for the
 * injection of mock classes. There is a two-phase "boot" process. This function
 * is defined before the JLoader class is defined, which overrides the
 * __autoload() function in the Joomla framework. The initial callback is empty.
 * Once JLoader has been defined, the test facility changes the callback
 * function to {@see junit_mockimport()} by passing the new callback as an array
 * element.
 *
 * @param string|array When passed as a string, a dot syntax path. When passed
 * as an array, the first element of the array is the callback function to use.
 */
function jimport($path)
{
	static $jimporter;

	if (is_array($path)) {
		$jimporter = reset($path);
		return;
	}
	if ($jimporter) {
		return call_user_func($jimporter, $path);
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
function junit_autoload($class)
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

	}

	return false;
}
