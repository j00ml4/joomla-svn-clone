<?php
/**
 * Joomla! Unit Test Facility.
 *
 * Setup file for UnitTestController and TestCases. This file is included by the
 * first executable file. It ensures that the testing environment is set up,
 * supported, and ready to run.
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright Copyright (C) 2007 Open Source Matters, Inc. All rights reserved.
 * @license GNU/GPL
 * @version $Id$
 */

error_reporting(E_ALL);

/* if included by a testcase */
unset($JUNIT_ROOT);

define('JUNIT_PREFIX', 'JUT_');
define('JUNIT_CLI', (PHP_SAPI == 'cli')); // SimpleReporter::inCli()

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

/* TestCases are main files */
define('_JEXEC', 1);

/* assume parent folder to be the base path. this only works
 * if the unittest folder is located in the Joomla! directory.
 */
!defined('JPATH_BASE') && define('JPATH_BASE', dirname(dirname(__FILE__)));

define('JUNIT_HOME_URL', ((int)PHP_VERSION == 4) ? JUNIT_HOME_PHP4 : JUNIT_HOME_PHP5);

// Make sure our tests only find one Joomla! framework
set_include_path('.' .
	PATH_SEPARATOR. JUNIT_ROOT .
	PATH_SEPARATOR. JPATH_BASE     .
	PATH_SEPARATOR. JUNIT_LIBS .
	PATH_SEPARATOR. get_include_path()
	);

require_once(JUNIT_LIBS . '/helper.php');

// check /libraries/joomla folder in JPATH_BASE
if (!is_dir(JPATH_BASE . '/libraries/joomla')) {
   $EOL = (JUNIT_CLI) ? PHP_EOL : '<br />';
   echo $EOL, ' JPATH_BASE does not point to a valid Joomla! installation:',
		$EOL, ' - ', JPATH_BASE,
		$EOL, ' Please modify your copy of "TestConfiguration.php"',
		$EOL;
   exit(0);
}



define('TEST_LIBRARY', dirname(__FILE__) . '/libraries/pear/');
require_once TEST_LIBRARY . 'PHPUnit.php';
require_once  JUNIT_LIBS . '/suite.php';

/**
 * If run through browser use $_REQUEST, from the command line use $argv path:
 * file to test; output: renderer output; list: list mode; renderer: reporter
 * class.
 */

$input         = &UnitTestHelper::getProperty('Controller', 'Input');
$input         = new stdClass;
$input->path   = '';
$input->output = '';
$input->list   = false;

if (JUNIT_CLI == false)
{
	$input->path   = @$_REQUEST['path'];
	$input->output = @$_REQUEST['output'];
	if (get_magic_quotes_gpc()) {
		$input->path = stripslashes($input->path);
	}
	unset($_REQUEST['path'], $_GET['path']);
	unset($_REQUEST['output'], $_GET['output']);
}
else if (count($_SERVER['argv']) > 1)
{
	// kick scriptname
	array_shift($_SERVER['argv']);

	while ($token = array_shift($_SERVER['argv'])) {
		switch ($token) {
		case '-path':
			$input->path   = trim(array_shift($_SERVER['argv']));
			break;
		case '-output':
			$input->output = trim(array_shift($_SERVER['argv']));
			break;
		case '-list':
			$input->list   = true;
			break;
		}
	}
	unset($token, $_SERVER['argv'], $_SERVER['args']);
}

if (preg_match('#[^a-z0-9\-_./]#i', $input->path)) {
	trigger_error('Security check: Illegal character in filepath', E_USER_ERROR);
}
$input->reporter = UnitTestHelper::getReporterInfo();

if (empty($input->output)) {
	$input->output = $input->reporter['output'];
}

$input->info = UnitTestHelper::getInfoObject($input->path);

/**
 * from here on we use '/' rather than DIRECTORY_SEPARATOR
 * which "WAMP" can perfectly handle for our means
 */
if (empty($input->path)) {
	$input->path = $input->info->path;
} else {
	$input->path = preg_replace('#[/\\\\]+#', '/', $input->path);
	$input->path = urldecode(ltrim($input->path, '\\/'));
	$input->info = UnitTestHelper::getInfoObject($input->path);
}


/**
 * Set PHP error reporting level and output directives
 *  -> TestConfiguration.php ?
 */
ini_set('display_errors'         , 'On');
ini_set('ignore_repeated_errors' , 'Off');
ini_set('ignore_repeated_source' , 'Off');
ini_set('html_errors'            , 'Off');
ini_set('log_errors'             , 'Off');
ini_set('log_errors_max_len'     , 512);

if (is_writable(dirname(JUNIT_PHP_ERRORLOG))) {
	ini_set('error_log' , JUNIT_PHP_ERRORLOG);    // -> TestConfiguration.php
}

/* and of course ... */
ini_set('register_globals', 'Off');

#
#  EXPERIMENTAL!
#
/* Mockup for jimport() - not used for Framework packages itself ;) */
if ($input->info->is_test !== JUNIT_IS_FRAMEWORK && !function_exists('jimport')) {
	require_once JUNIT_LIBS . '/uloader.php';
}

require_once 'libraries/loader.php';

require_once 'includes/defines.php';
require_once 'libraries/joomla/base/object.php'; // JObject

/* load a TestCase' helper file */
if (basename($input->path, '.php') !== 'AllTests') {
	if ($input->info->enabled && !empty($input->info->helper['location'])) {
		include_once $input->info->helper['location'];
		if (is_callable(array($input->info->helper['classname'], 'setUpTestCase'))) {
			call_user_func(array($input->info->helper['classname'], 'setUpTestCase'));
		}
	}
}

/* clean up */
unset($input);
