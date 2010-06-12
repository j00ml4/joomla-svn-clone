<?php
/**
 * Media Controller for mediagalleries Component
 * 
 * @package  			Joomla
 * @subpackage 	mediagalleries Suite
 * @link 				http://3den.org
 * @license		GNU/GPL
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_mediagalleries')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

//  Check Media Plugin
$mediapath = JPATH_SITE.'/plugins/content/media/media.php';
if (!is_file($mediapath)){
	return JError::raiseWarning(404, JText::_('MEDIA_PLUGIN_NOT_INSTALLED')); 
}

// Include dependancies
jimport('joomla.application.component.controller');
require_once $mediapath;
require_once JPATH_COMPONENT.'/helpers/mediagalleries.php';

// Define paths
define('URI_ASSETS', JURI::base().'../components/com_mediagalleries/assets/' );
define('PATH_HELPERS', JPATH_COMPONENT_SITE.DS.'helpers'.DS );

// Load Action Controller
$controller	= JController::getInstance('Mediagalleries');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();