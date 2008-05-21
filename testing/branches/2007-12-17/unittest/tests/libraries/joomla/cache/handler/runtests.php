<?php
/**
 * Joomla! Unit Test Facility.
 *
 * Invoke the default test runner.
 *
 * @version $Id: runtests.php 9762 2007-12-30 00:56:45Z instance $
 * @package Joomla
 * @subpackage UnitTest
 */

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

/*
 * Now that the context is set up, the runtests.php in the unit test root does
 * all the work.
 */
require_once $JUnit_root . DIRECTORY_SEPARATOR . basename(__FILE__);
?>