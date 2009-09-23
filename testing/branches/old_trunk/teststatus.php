<?php
/**
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

require_once 'configure.php';

require_once JUNIT_ROOT . DS . 'libraries' . DS . 'junit' . DS . 'teststatus.php';

$uts = new JUnit_TestStatus;
$uts->scan(JPATH_BASE, JUNIT_ROOT . '/tests');
echo $uts->renderHtml();