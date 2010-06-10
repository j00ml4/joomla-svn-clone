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

// Submenu
/*TODO add uniComments
$link = 'index.php?option=com_unicomments&section=com_mediagalleries';
JSubMenuHelper::addEntry(JText::_('Comments'), $link);
*/

//  Import Media Plugin or Error
$mediapath = JPATH_SITE.DS.'plugins'.DS.'content'.DS.'media.php';
if( !is_file($mediapath) )
{
	$msg = JText::_( 'MEDIA_PLUGIN_NOT_INSTALLED' );
	$mainframe->enqueueMessage( $msg, 'error' ); 
	return;
}
require_once( $mediapath );

// Define paths
define('URI_ASSETS', JURI::base().'../components/com_mediagalleries/assets/' );
define('PATH_HELPERS', JPATH_COMPONENT_SITE.DS.'helpers'.DS );
	
// Imports
require_once( JPATH_COMPONENT.DS.'controller.php' );

// Create the controller
$controller    = new mediagalleriesController();

// Perform the Request task
Jrequest::clean(); //Clean the request from script injection.
$controller->execute( JRequest::getVar('task') );
$controller->redirect();
