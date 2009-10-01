<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Modules
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$user = & JFactory::getUser();
if (!$user->authorize('core.manage', 'com_modules')) {
	JFactory::getApplication()->redirect('index.php', JText::_('ALERTNOTAUTH'));
}

// Helper classes
JHtml::addIncludePath(JPATH_COMPONENT.DS.'classes');

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

$controller	= new ModulesController();

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();