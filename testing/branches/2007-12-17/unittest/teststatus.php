<?php
/**
 * Joomla! Unit Test Facility.
 *
 * Produce a listing of unit test status by class
 *
 * @package Joomla
 * @subpackage UnitTest
 * @copyright Copyright (C) 2005 - 2008 Open Source Matters, Inc.
 * @version $Id: $
 */

require_once 'configure.php';

require_once JUNIT_ROOT . DS . 'libraries' . DS . 'junit' . DS . 'teststatus.php';

$uts = new JUnit_TestStatus;
$uts -> scan(JPATH_BASE, JUNIT_ROOT . '/tests');
echo $uts -> renderHtml();