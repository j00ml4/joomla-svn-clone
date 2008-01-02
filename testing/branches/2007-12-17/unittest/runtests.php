<?php
/**
 * Joomla! Unit Test Facility.
 *
 * This is a default test runner. If started from a subdirectory, it expects the
 * global variable $JUnit_root to reference the base unittest directory, and
 * $JUnit_start to be set to the relative directory where tests are to be run.
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version $Id: $
 * @author Alan Langford <addr>
 */

if (! isset($JUnit_root)) {
	$JUnit_home = DIRECTORY_SEPARATOR . 'unittest' . DIRECTORY_SEPARATOR;
	if (($JUnit_posn = strpos(__FILE__, $JUnit_home)) === false) {
		die('Unable to find ' . $JUnit_home . ' in path.');
	}
	$JUnit_posn += strlen($JUnit_home) - 1;
	$JUnit_root = substr(__FILE__, 0, $JUnit_posn);
	$JUnit_start = substr(
		__FILE__,
		$JUnit_posn + 1,
		strlen(__FILE__) - strlen(basename(__FILE__)) - $JUnit_posn - 2
	);
}

require_once $JUnit_root . DIRECTORY_SEPARATOR . 'setup.php';
// This should autoload but force it for now...
require_once $JUnit_root . '/libraries/junit/setup.php';
//require_once $JUnit_root . '/libraries/junit/loader.php';
/*
 * Now load the Joomla environment
 */
define('_JEXEC', 1);
define( 'DS', DIRECTORY_SEPARATOR );
require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_LIBRARIES . '/loader.php';
require_once JPATH_LIBRARIES . '/joomla/import.php';

$JUnit_setup = new JUnit_Setup();
$JUnit_setup -> setStartDir(dirname(__FILE__) . DIRECTORY_SEPARATOR . $JUnit_start);
$JUnit_setup -> classFilter = $JUnit_options['class-filter'];
$JUnit_setup -> testFilter = $JUnit_options['test-filter'];
unset($JUnit_start);
define('JUNIT_MAIN_METHOD', '');
$JUnit_setup -> run();
?>
