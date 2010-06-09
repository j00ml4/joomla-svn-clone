<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Error
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

// TODO: Wack this into a language file when this gets merged
JError::raiseWarning(100, "JLog has moved to jimport('joomla.log.log'), please update your code.");
JError::raiseWarning(100, "JLog has changed its behaviour between 1.6 and 1.7; please update your code.");
require_once(JPATH_LIBRARIES.'/joomla/log/log.php');


