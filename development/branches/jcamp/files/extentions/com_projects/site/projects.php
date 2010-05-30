<?php
/**
 * @version     $Id$
 * @package     Joomla
 * @subpackage	jCamp
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

defined("_JEXEC") or die("Restricted access");

// Include dependancies 
jimport('joomla.application.component.controller');

// Execute the task.
$controller	= &JController::getInstance('jCamp');
$controller->execute(JRequest::getVar('task','list'));
$controller->redirect(); 