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


jimport( 'joomla.application.application' );


// Submenu
/*TODO add uniComments
$link = 'index.php?option=com_unicomments&section=com_mediagalleries';
JSubMenuHelper::addEntry(JText::_('Comments'), $link);
*/

//  Import Media Plugin or Error

$mediapath = JPATH_SITE.DS.'plugins'.DS.'content'.DS.'media'.DS.'media.php';
if( !is_file($mediapath) )
{
	//JApplication::enqueueMessage("Media Plugin Not Installed", 'error');
	JError::raiseError(1,"Media Plugin Not Installed<br/> Cannot continue","Info");
	//$msg = JText::_( 'Media pluging is not installed' );
	//$mainframe->enqueueMessage( $msg, 'error' ); 
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
