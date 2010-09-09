<?php
/**
 * @version     $Id$
 * @package     Joomla.Administrator
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

defined("_JEXEC") or die("Restricted access");

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_projects')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller'); 
require_once JPATH_COMPONENT.'/helpers/projects.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/acl.php';

// Execute the task.
$controller	= &JController::getInstance('projects');
$controller->execute(JRequest::getVar('task','list'));
$controller->redirect(); 