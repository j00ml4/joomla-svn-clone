<?php
/**
 * Joomla! Unit Test Facility.
 *
 * Setup file for unit testing. This file is included by the first executable
 * file. It ensures that the testing environment is set up, supported, and ready
 * to run.
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright Copyright (C) 2007 Open Source Matters, Inc. All rights reserved.
 * @license GNU/GPL
 * @version $Id$
 */

error_reporting(E_ALL);

define('PHP_VERSION_MINIMUM', '5.2.0');
define('JUNIT_VERSION_MINIMUM', '3.2.0');

if (! defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

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

if (version_compare(PHP_VERSION, PHP_VERSION_MINIMUM) < 0) {
	die('Sorry. Requires PHP ' . PHP_VERSION_MINIMUM . ' or later.');
}

/*
 * Set PHP error reporting level and output directives
 */
ini_set('display_errors'         , 'On');
ini_set('ignore_repeated_errors' , 'Off');
ini_set('ignore_repeated_source' , 'Off');
ini_set('html_errors'            , 'Off');
ini_set('log_errors'             , 'Off');
ini_set('log_errors_max_len'     , 512);
/* and of course ... */
ini_set('register_globals', 'Off');

define('JUNIT_CLI', (PHP_SAPI == 'cli'));

/*
 * Read in user-defined test configuration if available; otherwise, read default
 * test configuration.
 */
if (is_readable(dirname(__FILE__) . '/TestConfiguration.php')) {
	require_once dirname(__FILE__) . '/TestConfiguration.php';
	define('JUNIT_USERCONFIG', true);
} else {
	require_once dirname(__FILE__) . '/TestConfiguration-dist.php';
	define('JUNIT_USERCONFIG', false);
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

$JUnit_config = new JUnit_Config();

/*
 * Make sure our tests only find the target "Joomla!" framework and PHPUnit.
 */
set_include_path(
	'.' . PATH_SEPARATOR
	. JUNIT_ROOT . PATH_SEPARATOR
	. JPATH_BASE . PATH_SEPARATOR
	. junit_path($JUnit_config -> libDir) . PATH_SEPARATOR
	. junit_path($JUnit_config -> pearDir) . PATH_SEPARATOR
	. get_include_path()
);

/*
 * Use our JLoader, autoloader and jimport.
 */
require_once 'libraries/junit/loader.php';

if (version_compare(PHPUnit_Runner_Version::id(), JUNIT_VERSION_MINIMUM) < 0) {
	die('Found PHPUnit version ' . PHPUnit_Runner_Version::id()
		. '. Requires ' . JUNIT_VERSION_MINIMUM
		. ' (this is probably a PEAR related configuration problem).'
	);
}
$JUnit_setup = new JUnit_Setup();
$JUnit_setup -> setStartDir(dirname(__FILE__) . DIRECTORY_SEPARATOR . $JUnit_start);

/*
 * Extract configuration overrides from command line or request variables.
 */
if (JUNIT_CLI) {
	/*
	 * Parse command line arguments
	 */
	list($options, $junk) = PHPUnit_Util_Getopt::getopt(
		$argv,
		array(),
		JUnit_Setup::getCliOptionDefs()
	);
	foreach ($options as $pair) {
		$opt = substr($pair[0], 2);
		$JUnit_setup -> setOption($opt, $pair[1]);
	}
} else {
	JUnit_Setup::$eol = '<br/>' . PHP_EOL;
	/*
	 * Extract settings from request
	 */
	foreach (JUnit_Setup::getOptionDefs() as $opt => $info) {
		if ($info[1]) {
			if (isset($_REQUEST[$opt])) {
				$JUnit_setup -> setOption($opt, $_REQUEST[$opt]);
			}
		} else {
			$JUnit_setup -> setOption($opt, isset($_REQUEST['debug']));
		}
	}
}

if (is_writable(junit_path($JUnit_config -> logDir))) {
	ini_set('error_log' , junit_path($JUnit_config -> logDir, 'php_errors.log'));
}
JUnit_Setup::setProperty('JUnit_Setup', 'setup', $JUnit_setup);

/*
 * Now load the Joomla environment
 */
define('_JEXEC', 1);
require_once JPATH_BASE . '/includes/defines.php';
/*
 * We now return to our regularly scheduled environment.
 */
require_once JPATH_LIBRARIES . '/joomla/import.php';
