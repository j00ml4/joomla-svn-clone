<?php
/**
 * @version		$Id: menus.php 13031 2009-10-02 21:54:22Z louis $
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_menus')) {
	return JError::raiseWarning(404, JText::_('ALERTNOTAUTH'));
}

// Include dependancies
jimport('joomla.application.component.controller');

// Execute the task.
$controller	= &JController::getInstance('Menus');
$controller->execute(JRequest::getVar('task'));
$controller->redirect();
