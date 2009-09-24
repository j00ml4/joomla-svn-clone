<?php
/**
 * Prepares a minimalist framework for unit testing.
 *
 * Joomla is assumed to include the /unittest/ directory.
 * eg, /path/to/joomla/unittest/
 *
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.phpunit.de/manual/current/en/installation.html
 */

// Define expected Joomla constants.

define('DS',			DIRECTORY_SEPARATOR);
define('_JEXEC',		1);
define('JPATH_BASE',	dirname(dirname(__FILE__)));

// Fix magic quotes.

@set_magic_quotes_runtime(0);

// Maximise error reporting.

@ini_set('zend.ze1_compatibility_mode', '0');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include relative constants, JLoader and the jimport and jexit functions.

require_once JPATH_BASE.DS.'includes'.DS.'defines.php';
require_once JPATH_LIBRARIES.DS.'joomla'.DS.'import.php';
