<?php
/**
 * Joomla! Unit Test Facility.
 *
 * Loads the testing configuration. This is useful for utilites that need to use
 * the test environment but not run tests.
 *
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

if (! defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

define('JUNIT_CLI', (PHP_SAPI == 'cli'));

/**
 * Map relative paths to JUnit directory, leaving absolute paths alone.
 *
 * @param string Directory path. If the leading character is a directory
 * separator then the path is taken as absolute.
 * @param string Optional subpath or file to append to directory
 * @return string Absolute path or path relative to the unit test root.
 */
function junit_path($path, $more = '')
{
    if ($path && ($path[0] == '/' || $path[0] == '\\')) {
        $jpath = $path;
    } else {
        $jpath = JUNIT_ROOT . DS . $path;
    }
    if ($more !== '') {
        $jpath .= DS . $more;
    }
    $jpath = preg_replace('![\\/]+!', DS, $jpath);
    return $jpath;
}

/*
 * Read in user-defined test configuration if available; otherwise, read default
 * test configuration.
 */
if (!class_exists( 'JUnit_Config' )) {
	if (is_readable(dirname(__FILE__) . '/TestConfiguration.php')) {
	    require_once dirname(__FILE__) . '/TestConfiguration.php';
	    define('JUNIT_USERCONFIG', true);
	} else {
	    require_once dirname(__FILE__) . '/TestConfiguration-dist.php';
	    define('JUNIT_USERCONFIG', false);
	}
}

/*
 *  Sanity check: Verify /libraries/joomla exists in JPATH_BASE.
 */
if (! is_dir(JPATH_BASE . '/libraries/joomla')) {
    $eol = (JUNIT_CLI) ? PHP_EOL : '<br/>';
    echo $eol, ' JPATH_BASE does not point to a valid Joomla! installation:', $eol,
        'JPATH_BASE = ', JPATH_BASE, $eol,
        ' Please modify your copy of "TestConfiguration.php"', $eol;
    exit(0);
}

//
// Make sure our tests only find the target "Joomla!" framework and PHPUnit.
//
set_include_path(
    '.' . PATH_SEPARATOR
    . JUNIT_ROOT . PATH_SEPARATOR
    . JPATH_BASE . PATH_SEPARATOR
    . junit_path(JUnit_Config::$libDir) . PATH_SEPARATOR
    . junit_path(JUnit_Config::$pearDir) . PATH_SEPARATOR
    . get_include_path()
);

require dirname( __FILE__ ).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'defines.php';
