<?php
/**
 * @package Joomla
 * @subpackage UnitTest
 * @copyright    Copyright (C) 2005 - 2007 Open Source Matters, Inc.
 * @license        GNU/GPL, see LICENSE.php
 * @version $Id: loader.php 9743 2007-12-24 04:18:48Z instance $
 *
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

if (! defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

/**
 * Unit test intelligent file importer.
 *
 * This function has to be defined after the Joomla framework loader is in
 * place.
 *
 * @param string A dot syntax path
 */
function junit_mockimport($path)
{
	return JLoader::import($path);
	/*
	$filepath = JPATH_BASE . 'libraries' . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $path);
	require_once $filepath;
	return true;
	*/
}
