<?php
/**
 * Joomla! v1.5 UnitTest Platform.
 * Setup file for UnitTestController and TestCases.
 *
 * @version	$Id$
 * @package 	Joomla.Framework
 * @subpackage 	UnitTest
 * @copyright 	Copyright (C) 2007 Rene Serradeil. All rights reserved.
 * @license		GNU/GPL
 */

error_reporting(E_ALL);

/**
 * Simple variable dumper.
 *
 * recommended usage: jutdump($data, 'hint '.__FILE__.__LINE__)
 *
 * For anthing but JUNITTEST_CLI, output in wrapped in <xmp>,
 * which is deprecated in HTML but extremely handy. Does anybody
 * case for compliance in debug mode??
 *
 * @ignore
 */
function jutdump($data, $rem='')
{
	list($po,$pc,$lf) = (JUNITTEST_CLI)
					? array(PHP_EOL, PHP_EOL, PHP_EOL)
					: array('<xmp style="border:1px solid;padding:1ex;">', PHP_EOL.'</xmp>', PHP_EOL);

	if (!empty($rem)) {
		$rem = preg_replace('/(?:[\.\-\:]?)(\d+)$/', ' (\1)', $rem);
	} else {
		if ( function_exists('debug_backtrace') ) {
			$trace = array_shift(debug_backtrace());
			$rem = $trace['file'].' ('.$trace['line']. ')';
		}
	}
	echo $po, 'DEBUG: ', $rem, $lf, print_r($data, true), $pc;
}

/* if included by a testcase */
unset($JUNITTEST_ROOT);

// 4.3.10- and PHP 5.0.2-
!defined('PHP_EOL') && define('PHP_EOL', "\n");

define('JUNITTEST_PREFIX', 'JUT_');
define('JUNITTEST_CLI', ( PHP_SAPI == 'cli') ); // SimpleReporter::inCli()

/* Read in user-defined test configuration if available;
 * otherwise, read default test configuration.
 */
if (is_readable(dirname(__FILE__) . '/TestConfiguration.php') ) {
    require_once dirname(__FILE__) . '/TestConfiguration.php';
    define('JUNITTEST_USERCONFIG', true);
} else {
    require_once dirname(__FILE__) . '/TestConfiguration-dist.php';
    define('JUNITTEST_USERCONFIG', false);
}

/* TestCases are main files */
define( '_JEXEC', 1 );

/* assume parent folder to be the base path. this only works
 * if the unittest folder is located in the Joomla! directory.
 */
!defined('JPATH_BASE') && define('JPATH_BASE', dirname( dirname(__FILE__) ));

define('JUNITTEST_HOME_URL', ((int)PHP_VERSION == 4) ? JUNITTEST_HOME_PHP4 : JUNITTEST_HOME_PHP5);

/* make sure our tests only run into ONE JOOMLA! FRAMEWORK */
set_include_path( '.' .
	PATH_SEPARATOR. JUNITTEST_ROOT .
	PATH_SEPARATOR. JPATH_BASE     .
	PATH_SEPARATOR. JUNITTEST_LIBS .
	PATH_SEPARATOR. get_include_path()
	);

if ((int)PHP_VERSION >= 5) {
	require_once( JUNITTEST_LIBS . '/overload5.php' );
}

require_once( JUNITTEST_LIBS . '/helper.php' );

/* check /libraries/joomla folder in JPATH_BASE */
if ( !is_dir(JPATH_BASE . '/libraries/joomla') ) {
   $EOL = (JUNITTEST_CLI) ? PHP_EOL : '<br />';
   echo $EOL, ' JPATH_BASE does not point to a valid Joomla! installation:',
	    $EOL, ' - ', JPATH_BASE,
	    $EOL, ' Please modify your copy of "TestConfiguration.php"',
	    $EOL;
   exit(0);
}


/* Simpletest location is NOT an option. see README.txt */
define('SIMPLE_TEST', dirname(__FILE__) . '/simpletest/');
require_once( SIMPLE_TEST.'unit_tester.php' );
require_once( JUNITTEST_LIBS . '/suite.php' );

/**
 * If run thru browser use $_REQUEST, from the command line use $argv
 * - path    : file to test
 * - output  : renderer output
 * - list    : list mode
 * - renderer: reporter class
 */

$input         = &UnitTestHelper::getProperty('Controller', 'Input');
$input         = new stdClass;
$input->path   = '';
$input->output = '';
$input->list   = false;

if (JUNITTEST_CLI == false )
{
	$input->path   = @$_REQUEST['path'];
	$input->output = @$_REQUEST['output'];
	if (get_magic_quotes_gpc()) {
		$input->path = stripslashes($input->path);
	}
	unset($_REQUEST['path'], $_GET['path']);
	unset($_REQUEST['output'], $_GET['output']);
}
else if ( count($_SERVER['argv']) > 1 )
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

if ( empty($input->output) ) {
	$input->output = $input->reporter['output'];
}

$input->info = UnitTestHelper::getInfoObject($input->path);

/**
 * from here on we use '/' rather than DIRECTORY_SEPARATOR
 * which "WAMP" can perfectly handle for our means
 */
if ( empty($input->path) ) {
	$input->path = $input->info->path;
} else {
	$input->path = preg_replace('#[/\\\\]+#', '/', $input->path);
	$input->path = urldecode( ltrim($input->path, '\\/') );
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

if (is_writable( dirname(JUNITTEST_PHP_ERRORLOG) )) {
	ini_set('error_log' , JUNITTEST_PHP_ERRORLOG);	// -> TestConfiguration.php
}

/* and of course ... */
ini_set('register_globals', 'Off');

#
#  EXPERIMENTAL!
#
/* Mockup for jimport() - not used for Framework packages itself ;) */
if ($input->info->is_test !== JUNITTEST_IS_FRAMEWORK && !function_exists('jimport')) {
	require_once( JUNITTEST_LIBS . '/uloader.php' );
}

if (!function_exists('jimport')) {
	require_once( 'libraries/loader.php' );
}

require_once( 'includes/defines.php' );
require_once( 'libraries/joomla/base/object.php' ); // JObject

/* load a TestCase' helper file */
if ( basename($input->path, '.php') !== 'AllTests') {
	if ($input->info->enabled && !empty($input->info->helper['location'])) {
		include_once($input->info->helper['location']);
		if (is_callable(array($input->info->helper['classname'], 'setUpTestCase'))) {
			call_user_func(array($input->info->helper['classname'], 'setUpTestCase'));
		}
	}
}

/* clean up */
unset($input);
