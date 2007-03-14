<?php
/**
 * Joomla! v1.5 UnitTest Platform.
 * Setup file for UnitTestController and TestCases.
 *
 * @version	$Id$
 * @package 	Joomla
 * @subpackage 	UnitTest
 */

error_reporting(E_ALL);

/* if we come from a testcase */
unset($JUNITTEST_ROOT);

define('JUNITTEST_PREFIX', 'JUT_');
define('JUNITTEST_CLI', ( PHP_SAPI == 'cli') ); // SimpleReporter::inCli()

/* Simpletest location is NOT an option. see README.txt */
define('SIMPLE_TEST', dirname(__FILE__) . '/simpletest/');
require_once( SIMPLE_TEST.'unit_tester.php' );
require_once( SIMPLE_TEST.'reporter.php' );

require_once('views/libs/suite.php' );

/* Read in user-defined test configuration if available;
 * otherwise, read default test configuration.
 */
if (is_readable( dirname(__FILE__) . '/TestConfiguration.php') ) {
    require_once dirname(__FILE__) . '/TestConfiguration.php';
} else {
    require_once dirname(__FILE__) . '/TestConfiguration-dist.php';
}

require_once( JUNITTEST_LIBS . '/helper.php' );

/* J! specifics */
define( '_JEXEC', 1 );
!defined('JPATH_BASE') && define('JPATH_BASE', dirname(__FILE__) );

/* make sure our tests only run into ONE JOOMLA! FRAMEWORK */
set_include_path( '.' .
	PATH_SEPARATOR. JUNITTEST_ROOT .
	PATH_SEPARATOR. JPATH_BASE     .
	PATH_SEPARATOR. JUNITTEST_LIBS .
	PATH_SEPARATOR. get_include_path()
	);

//echo '<pre>', print_r(explode(PATH_SEPARATOR, get_include_path()));

/* load a TestCase' helper file */
if ( strpos(JUNITTEST_MAIN_METHOD, 'AllTests') === false) {
	$filepath = strstr($_SERVER['SCRIPT_FILENAME'], JUNITTEST_BASE);
	$helper   = UnitTestHelper::getTestcaseHelper($filepath);
	if ($helper['location']) {
		include_once($helper['location']);
	}
}

/* Set PHP error reporting level and output directives. */
ini_set('display_errors'         , 'On');	// -> TestConfiguration.php ?
ini_set('display_startup_errors' , 'On');
ini_set('ignore_repeated_errors' , 'Off');
ini_set('ignore_repeated_source' , 'Off');
ini_set('html_errors'            , 'Off');
ini_set('log_errors'             , 'Off');
ini_set('log_errors_max_len'     , 512);

if (is_writable(JUNITTEST_ROOT . '/.')) {
	ini_set('error_log' , JUNITTEST_PHP_ERRORLOG);	// -> TestConfiguration.php
}
/* and of course ... */
ini_set('register_globals', 'Off');

# {{ TODO: get rid of these!
require_once( JPATH_BASE.'/libraries/loader.php' ); // -> Mock it
require_once( JPATH_BASE.'/includes/defines.php' );
# }}

require_once( JPATH_BASE.'/libraries/joomla/base/object.php' ); // JObject
