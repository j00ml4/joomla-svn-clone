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
if (!JFactory::getUser()->authorise('core.manage', JRequest::getCmd('extension'))) {
	return JError::raiseWarning(404, JText::_('ALERTNOTAUTH'));
}

// Include dependancies
jimport('joomla.application.component.controller'); 
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_projects'.DS.'tables'); 


// Execute the task.
$controller	= &JController::getInstance('projects');
$controller->execute(JRequest::getVar('task'));
$controller->redirect(); 