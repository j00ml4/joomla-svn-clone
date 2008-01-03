<?php
/**
 * Joomla! v1.5 UnitTest Platform
 *
 * Bootstrap file, see README.txt for usage examples.
 *
 * @version $Id$
 * @package Joomla
 * @subpackage UnitTest
 */

/**
 * Make sure we get all errors.
 */
error_reporting(E_ALL);

define('PHP_VERSION_MINIMUM', '5.2.0');
define('JUNIT_VERSION_MINIMUM', '3.2.0');

if (version_compare(PHP_VERSION, PHP_VERSION_MINIMUM) < 0) {
	die('Sorry. Requires PHP ' . PHP_VERSION_MINIMUM . ' or later.');
}
if (version_compare(PHPUnit_Runner_Version::id(), JUNIT_VERSION_MINIMUM) < 0) {
	die('Found PHPUnit version ' . PHPUnit_Runner_Version::id()
		. '. Requires ' . JUNIT_VERSION_MINIMUM
		. ' (this is probably a PEAR related configuration problem).'
	);
}

/**
 * Controller class.
 */
require_once dirname(__FILE__) . '/UnitTestController.php';

/**
 * If a path is provided, run the test,
 * otherwise show a fancy default start page
 */
$input =& JUnit_Setup::getProperty('Controller', 'Input');


if (! empty($input->info->testclass)) {
	return UnitTestController::main($input->info);
} else {
	UnitTestController::getUnitTestsList();

	$tests =& JUnit_Setup::getProperty('Controller', 'Tests');

	if (file_exists(JUNIT_VIEWS.'/default_'.$input->output.'.php')) {
		include(JUNIT_VIEWS.'/default_'.$input->output.'.php');
	} else {
		include(JUNIT_VIEWS.'/default.html');
	}
}

