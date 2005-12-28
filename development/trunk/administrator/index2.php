<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__) );

require_once ( 'includes/defines.php'     );
require_once ( 'includes/application.php' );
require_once ( 'includes/template.php'    );

// initialise some common request directives
$option 	= strtolower( mosGetParam( $_REQUEST, 'option', 'com_admin' ) );
$task 		= mosGetParam( $_REQUEST, 'task', '' );
$section 	= mosGetParam( $_REQUEST, 'section', '' );
$no_html 	= strtolower( mosGetParam( $_REQUEST, 'no_html', 0 ) );
$id         = intval( mosGetParam( $_REQUEST, 'id' ) );
$cid		= mosGetParam( $_POST, 'cid', null );

// create the mainframe object
$mainframe =& new JAdministrator($option);

//get the database object
$database =& $mainframe->getDBO();

// load system plugin group
JPluginHelper::importGroup( 'system' );

$_PROFILER->mark( 'onBeforeStart' );

// trigger the onStart events
$mainframe->triggerEvent( 'onBeforeStart' );

//get the acl object (for backwards compatibility)
$acl =& JFactory::getACL();

// create the session
$mainframe->setSession( $mainframe->getCfg('live_site').$mainframe->_client );

if (is_null(JSession::get('guest')) || JSession::get('guest')) {
	mosRedirect( 'index.php');
}

// trigger the onStart events
$mainframe->triggerEvent( 'onAfterStart' );

$_PROFILER->mark( 'onAfterStart' );

if ($option == 'logout') {
	$mainframe->logout();
	mosRedirect( JURL_SITE );
}

// get the information about the current user from the sessions table
$my   = $mainframe->getUser();

// set for overlib check
$mainframe->set( 'loadOverlib', false );

$_PROFILER->mark( 'onBeforeBuffer' );

$_PROFILER->mark( 'onAfterBuffer' );

$_PROFILER->mark( 'onBeforeOutput' );

//render raw component output
if($no_html == 1) {
	$path = $mainframe->getPath( 'admin', $option);
	
	//load common language files
	$lang =& $mainframe->getLanguage();
	$lang->load($option);
	require_once( $path );	
	exit();	
}

// loads template file
$cur_template = $mainframe->getTemplate();
$file     = 'index.php';

$document =& $mainframe->getDocument();
$document->parse($cur_template, $file);

initDocument($document); //initialise the document

$document->display( $file, $mainframe->getCfg('gzip') );

$_PROFILER->mark( 'onAfterOutput' );

if ($mainframe->getCfg('debug')) {
	echo $_PROFILER->report();
	echo $_PROFILER->getMemory();
}
?>