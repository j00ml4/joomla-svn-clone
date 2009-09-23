<?php
/**
 * Joomla! Unit Test Facility.
 *
 * Setup file for unit testing. This file is included by the first executable
 * file. It ensures that the testing environment is set up, supported, and ready
 * to run.
 *
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

require_once 'configure.php';

error_reporting(E_ALL);

define('PHP_VERSION_MINIMUM', '5.2.0');
define('JUNIT_VERSION_MINIMUM', '3.2.0');

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

//
// Use our JLoader, autoloader and jimport.
//
require_once 'libraries/junit/loader.php';

if (version_compare(PHPUnit_Runner_Version::id(), JUNIT_VERSION_MINIMUM) < 0) {
	die('Found PHPUnit version ' . PHPUnit_Runner_Version::id()
		. '. Requires ' . JUNIT_VERSION_MINIMUM
		. ' (this is probably a PEAR related configuration problem).'
	);
}

$JUnit_setup = new JUnit_Setup();
if ($JUnit_start[0] == DIRECTORY_SEPARATOR) {
	$JUnit_setup->setStartDir($JUnit_start);
}
else {
	$JUnit_setup->setStartDir(dirname(__FILE__) . DIRECTORY_SEPARATOR . $JUnit_start);
}

/*
 * Extract configuration overrides from command line or request variables.
 */
if (JUNIT_CLI) {
	/*
	 * Parse command line arguments
	 */
	list($options, $junk) = PHPUnit_Util_Getopt::getopt(
		$GLOBALS['argv'],
		array(),
		JUnit_Setup::getCliOptionDefs()
	);
	foreach ($options as $pair) {
		$opt = substr($pair[0], 2);
		$JUnit_setup->setOption($opt, $pair[1]);
	}
} else {
	JUnit_Setup::$eol = '<br/>' . PHP_EOL;
	/*
	 * Extract settings from request
	 */
	foreach (JUnit_Setup::getOptionDefs() as $opt => $info) {
		if ($info[1]) {
			if (isset($_REQUEST[$opt])) {
				$JUnit_setup->setOption($opt, $_REQUEST[$opt]);
			}
		} else {
			$JUnit_setup->setOption($opt, isset($_REQUEST['debug']));
		}
	}
}

if (is_writable(junit_path(JUnit_Config::$logDir))) {
	ini_set('error_log' , junit_path(JUnit_Config::$logDir, 'php_errors.log'));
}
JUnit_Setup::setProperty('JUnit_Setup', 'setup', $JUnit_setup);

define( 'PATH_MOCKS', dirname( __FILE__ ).DS.'mocks' );
