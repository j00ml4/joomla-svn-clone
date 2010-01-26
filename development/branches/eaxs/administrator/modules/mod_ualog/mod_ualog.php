<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	mod_ualog
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// no direct access
defined('_JEXEC') or die;

require_once dirname(__FILE__).'/helper.php';
require JModuleHelper::getLayoutPath('mod_ualog', $params->get('layout', 'default'));