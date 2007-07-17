<?php
/**
 * Joomla! v1.5 UnitTest Platform
 *
 * Bootstrap file, see README.txt for usage examples.
 *
 * @version	$Id$
 * @package 	Joomla
 * @subpackage 	UnitTest
 */

/**
 * Make sure we get all errors.
 */
error_reporting(E_ALL);

// glob() and GLOB_ONLYDIR used in: UnitTestController::_files()
if ( version_compare(PHP_VERSION, '4.3.3') < 0 ) {
	die('Sorry. PHP 4.3.3 or later required.');
}

define('JUNITTEST_MAIN_METHOD', '-stub-');

require_once( dirname(__FILE__) . '/UnitTestController.php' );

$input =& UnitTestHelper::getProperty('Controller', 'Input', 'object');

jutdump($input, __FILE__);


/* If a path is provided, run the test,
 * otherwise show the fancy default start page */
if( !empty($path) )
{
	return UnitTestController::main( $path, $output, $path );
}
else
{
	UnitTestController::getUnitTestsList();

$tests    =& UnitTestHelper::getProperty('Controller', 'Tests');
$disabled =& UnitTestHelper::getProperty('Controller', 'Disabled');
$enabled  =& UnitTestHelper::getProperty('Controller', 'Enabled');

jutdump($enabled, 'testCases '.__FILE__.__LINE__);
exit;

	if ( isset($list) || JUNITTEST_CLI ) {
		$disabled_tests = array();
	} else {
		include( JUNITTEST_VIEWS.'/default.html' );
	}

}

