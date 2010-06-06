<?php
/**
 * @version     $Id$
 * @package     Joomla.Administrator
 * @subpackage	Projects
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

// Execute the task.
$controller	= &JController::getInstance('projects');
$controller->execute(JRequest::getVar('task','list'));
$controller->redirect(); 