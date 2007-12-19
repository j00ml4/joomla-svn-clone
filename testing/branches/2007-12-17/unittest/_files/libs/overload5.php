<?php
/**
 * Joomla! v1.5 UnitTest Platform Overloads - PHP5 only.
 *
 * Some code and CMS specific dependencies in the Joomla! Framework
 * prevents us from writing truly independent TestCases for THE FRAMEWORK.
 * Such dependencies are located in JFactory and most noteably: JLoader.
 * Because the latter renders PHP's 'include_path' feature pretty useless
 * there's no (easy and) comfortable way to use MockObject for classes
 * that you "must not care about" in a specific TestCase.
 *
 * This file attempts to "fix" these issues with a little help of the
 * "Advanced PHP debugger" available to PHP 5. That is YOU MUST have
 * APD installed in order to run some of the TestCases or they will
 * either fail ... or lie.
 *
 * Resources.
 * - PECL extension: http://pecl.php.net/package/apd
 * - PECL extension: http://pecl4win.php.net/ext.php/php_apd.dll
 * - PHP manual:     http://www.php.net/manual/ref.apd.php
 *
 * @version 	$Id$
 * @package 	Joomla.Framework
 * @subpackage 	UnitTest
 * @copyright 	Copyright (C) 2007 Rene Serradeil. All rights reserved.
 * @license		GNU/GPL
 */

if ( !function_exists('rename_function') && !@extension_loaded('apd') ) {
	return false;
} else {
	define('JUNITTEST_OVERLOADS', constant('APD_VERSION'));
}

if ( defined('JUNITTEST_OVERLOADS') ) {

	/**
	 * jimport() claims to be a huge speed improvement over PHP's
	 * require_once() -- we use full qualified paths in this stub
	 * which allows to override core classes with MockObjects.
	 */
	rename_function('jimport', 'JUNITTEST_jimport' );
	function JUNITTEST_jimport( $path )
	{
		$filepath = JPATH_BASE . 'libraries' . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $path);
		require_once($filepath);
		return true;
	}

}

