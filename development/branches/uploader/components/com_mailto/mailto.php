<?php
/**
 * @version		$Id: mailto.php 13109 2009-10-08 18:15:33Z ian $
 * @package		Joomla.Site
 * @subpackage	MailTo
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');

require_once JPATH_COMPONENT.DS.'controller.php';

$controller	= new MailtoController();
$controller->registerDefaultTask('mailto');
$controller->execute(JRequest::getCmd('task'));

//$controller->redirect();