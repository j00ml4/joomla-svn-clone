<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Plugins
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/*
 * Make sure the user is authorized to view this page
 */
$user = & JFactory::getUser();
if (!$user->authorize('core.manage', 'com_plugins')) {
		$mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
}

require_once(JPATH_COMPONENT.DS.'controller.php');

// Create the controller
$controller	= new PluginsController();

$controller->execute(JRequest::getCmd('task'));
$controller->redirect();