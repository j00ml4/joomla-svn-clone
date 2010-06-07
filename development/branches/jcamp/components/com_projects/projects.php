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

// Execute the task.
$controller	= &JController::getInstance('projects');
$controller->execute(JRequest::getVar('task'));
$controller->redirect(); 