<?php
/**
 * @version		$Id: plugins.php 13109 2009-10-08 18:15:33Z ian $
 * @package		Joomla.Administrator
 * @subpackage	Plugins
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_plugins')) {
	return JError::raiseWarning(404, JText::_('ALERTNOTAUTH'));
}

// Include dependancies
jimport('joomla.application.component.controller');

// TODO: Refactor to support latest MVC pattern.

require_once JPATH_COMPONENT.DS.'controller.php';

// Create the controller
$controller	= new PluginsController();
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();