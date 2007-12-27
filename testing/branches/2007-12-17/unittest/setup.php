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

define('JUNIT_CLI', (PHP_SAPI == 'cli')); // SimpleReporter::inCli()

/*
 *  Sanity check: Verify /libraries/joomla exists in JPATH_BASE.
 */
if (! is_dir(JPATH_BASE . '/libraries/joomla')) {
	$EOL = (JUNIT_CLI) ? PHP_EOL : '<br />';
	echo $EOL, ' JPATH_BASE does not point to a valid Joomla! installation:', $EOL,
		'JPATH_BASE = ', JPATH_BASE, $EOL,
		' Please modify your copy of "TestConfiguration.php"', $EOL;
	exit(0);
}

/* TestCases are main files */
define('_JEXEC', 1);

// Make sure our tests only find one Joomla! framework
set_include_path(
	'.' . PATH_SEPARATOR 
	. JUNIT_ROOT . PATH_SEPARATOR 
	. JPATH_BASE . PATH_SEPARATOR 
	. get_include_path()
);

require_once(JUNIT_LIBS . '/helper.php');

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

if (! JUNIT_CLI) {
	$input->path   = @$_REQUEST['path'];
	$input->output = @$_REQUEST['output'];
	if (get_magic_quotes_gpc()) {
		$input->path = stripslashes($input->path);
	}
	unset($_REQUEST['path'], $_GET['path']);
	unset($_REQUEST['output'], $_GET['output']);
} elseif (count($_SERVER['argv']) > 1) {
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

/**
 * Set PHP error reporting level and output directives
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


require_once 'libraries/loader.php';

require_once 'includes/defines.php';
require_once 'libraries/joomla/base/object.php'; // JObject

