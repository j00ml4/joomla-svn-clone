<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).DS.'helper.php';

$menu	= &JSite::getMenu();
$active	= $menu->getActive();
$active_id = isset($active) ? $active->id : $menu->getDefault()->id;
$path	= isset($active) ? $active->tree : array();
$showAll	= $params->get('showAllChildren');



$list = modMenuHelper::getList($params);


require JModuleHelper::getLayoutPath('mod_menu', $params->get('layout', 'default'));