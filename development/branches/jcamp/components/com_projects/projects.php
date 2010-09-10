<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

defined("_JEXEC") or die("Restricted access");

// Include dependancies 
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT.'/helpers/projects.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/acl.php';

// Execute the task.
$controller	= &JController::getInstance('Projects');
$controller->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.'/models');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect(); 