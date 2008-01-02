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

if (! defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

if (! isset($JUnit_root)) {
	$JUnit_home = DS . 'unittest' . DS;
	if (($JUnit_posn = strpos(__FILE__, $JUnit_home)) === false) {
		die('Unable to find ' . $JUnit_home . ' in path.');
	}
	$JUnit_posn += strlen($JUnit_home) - 1;
	$JUnit_root = substr(__FILE__, 0, $JUnit_posn);
	$JUnit_start = substr(
		__FILE__,
		$JUnit_posn + 1,
		strlen(__FILE__) - strlen(basename(__FILE__)) - $JUnit_posn - 2
	) . DS . 'tests';
}

require_once $JUnit_root . DS . 'setup.php';

define('JUNIT_MAIN_METHOD', '');
JUnit_Setup::getProperty('JUnit_Setup', 'setup') -> run();
?>
