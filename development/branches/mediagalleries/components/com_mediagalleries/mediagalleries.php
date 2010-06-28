<?php
/**
 * @package		Joomla
 * @subpackage	StreamALL Media
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Try to Import Media Plugin
$mediapath = JPATH_SITE.DS.'plugins'.DS.'content'.DS.'media'.DS.'media.php';
if(!is_file($mediapath)){
	$msg = JText::_( 'Media is not installed' );
	$mainframe->enqueueMessage( $msg, 'error' ); 
	return; 
}
require_once( $mediapath );

// Define paths
define('URI_ASSETS', 
	JURI::base().'components/com_mediagalleries/assets/' );
define('PATH_HELPERS', 
	JPATH_COMPONENT_SITE.DS.'helpers'.DS );

// Imports
require_once(JPATH_COMPONENT.DS.'controller.php');
include_once( PATH_HELPERS.'player.php' );

// Create the controller
$controller   = new MediagalleriesController();

// Perform the Request task
$controller->execute( JRequest::getCmd('task') );
$controller->redirect();