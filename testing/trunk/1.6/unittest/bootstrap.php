<?php
/**
 * @version	$Id$
 */

define('_JEXEC', 1);

//
// Joomla is assumed to include the /unittest/ directory
// eg, /path/to/joomla/unittest/
//
define('JPATH_BASE', dirname(dirname(__FILE__)));

//
// Include minimalist framework
//
define('DS', DIRECTORY_SEPARATOR);

require_once (JPATH_BASE .DS.'includes'.DS.'defines.php');
//require_once (JPATH_BASE .DS.'includes'.DS.'framework.php');
