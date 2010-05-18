<?php
/**
 * @version		$Id: defines.php 2010-05-13 06:47:48Z Joomila $
 * @package		Joomla.REST
 * @subpackage	Application
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Joomla! Application define.
 */

//Global definitions.
//Joomla framework path definitions.
$parts = explode(DS, JPATH_BASE);
array_pop($parts);

//Defines.
define('JPATH_ROOT',			implode(DS, $parts));

define('JPATH_SERVICE',			JPATH_BASE);
define('JPATH_CONFIGURATION',	JPATH_ROOT);
define('JPATH_LIBRARIES',		JPATH_ROOT.DS.'libraries');