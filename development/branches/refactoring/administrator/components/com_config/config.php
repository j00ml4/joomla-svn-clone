<?php
/**
 * @version		$Id: $
 * @package		Joomla
 * @subpackage	Cache
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Make sure the user is authorized to view this page
$user = & JFactory::getUser();
if (!$user->authorize( 'com_config', 'manage' )) {
	$mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
}

// Helper classes
JHTML::addIncludePath(JPATH_COMPONENT.DS.'classes');

// Require specific controller if requested
if($controller = JRequest::getWord('controller', 'application')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

// Create the controller
$classname	= 'ConfigController'.ucfirst($controller);
$controller	= new $classname( );

// What is this for?
JResponse::setHeader( 'Expires', 'Mon, 26 Jul 1997 05:00:00 GMT', true );

// Perform the Request task
$controller->execute( JRequest::getCmd( 'task' ) );
$controller->redirect();