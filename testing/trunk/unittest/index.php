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

/**
 * Controller class.
 */
require_once( dirname(__FILE__) . '/UnitTestController.php' );

/**
 * If a path is provided, run the test,
 * otherwise show a fancy default start page
 */
$input =& UnitTestHelper::getProperty('Controller', 'Input');

//die(jutdump($input,__FILE__.__LINE__));

if ( !empty($input->info->testclass) )
{
	return UnitTestController::main( $input->info );
}
else
{
	UnitTestController::getUnitTestsList();

	$tests =& UnitTestHelper::getProperty('Controller', 'Tests');
//die(jutdump($tests,__FILE__.__LINE__));

	if ( file_exists(JUNITTEST_VIEWS.'/default_'.$input->output.'.php') ) {
		include( JUNITTEST_VIEWS.'/default_'.$input->output.'.php' );
	} else {
		include( JUNITTEST_VIEWS.'/default.html' );
	}
}

