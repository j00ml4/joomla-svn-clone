<?php
/**
* @version $Id: index.php 1244 2005-11-29 02:39:31Z Jinx $
* @package Joomla
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
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

require_once ( JPATH_BASE .'/includes/application.php' );
require_once ( JPATH_BASE .'/includes/template.php'  );

// create the mainframe object
$mainframe = new JSite();

// set the configuration
$mainframe->setConfiguration(JPATH_CONFIGURATION . DS . 'configuration.php');

//get the database object (for backwards compatibility)
$database =& $mainframe->getDBO();

// load system plugin group
JPluginHelper::importPlugin( 'system' );

// trigger the onStart events
$mainframe->triggerEvent( 'onBeforeStart' );

// Get the global option variable and create the pathway
$option = strtolower( JRequest::getVar( 'option' ) );
$mainframe->_createPathWay( );

// create the session
$mainframe->setSession( $mainframe->getCfg('live_site').$mainframe->getClientId() );

// login user
//if ($option == 'login')  {
//	$mainframe->login();
//}

// logout user
//if ($option == 'logout') {
//	$mainframe->logout();
//}

$Itemid = JRequest::getVar( 'Itemid', 0, '', 'int' );

if ($option == '' || $option == 'login' || $option == 'logout')
{
	if ($Itemid) {
		$query = "SELECT id, link"
		. "\n FROM #__menu"
		. "\n WHERE menutype = 'mainmenu'" 
		. "\n AND id = '$Itemid'"
		. "\n AND published = '1'"
		;
		$database->setQuery( $query );
	} else {
		$query = "SELECT id, link"
		. "\n FROM #__menu"
		. "\n WHERE menutype = 'mainmenu'"
		. "\n AND published = 1"
		. "\n ORDER BY parent, ordering LIMIT 1"
		;
		$database->setQuery( $query );
	}
	$menu =& JTable::getInstance('menu', $database );
	if ($database->loadObject( $menu )) {
		$Itemid = $menu->id;
	}
	$link = $menu->link;
	if (($pos = strpos( $link, '?' )) !== false) {
		$link = substr( $link, $pos+1 ). '&Itemid='.$Itemid;
	}
	parse_str( $link, $temp );
	/** this is a patch, need to rework when globals are handled better */
	foreach ($temp as $k=>$v) {
		$GLOBALS[$k] = $v;
		$_REQUEST[$k] = $v;
		if ($k == 'option') {
			$option = $v;
		}
	}
}

if ( !$Itemid ) {
// when no Itemid give a default value
	$Itemid = 99999999;
}

// set language
//$mainframe->setLanguage($mainframe->getUserStateFromRequest( "lang", 'lang' ));

// trigger the onAfterStart events
$mainframe->triggerEvent( 'onAfterStart' );

JDEBUG ? $_PROFILER->mark( 'afterStartFramework' ) : null;

// get the information about the current user from the sessions table
// Note: Moved to allow for single sign-on bots that can't run with onBeforeStart due to extra setup
$user	= & $mainframe->getUser();
$my		= $user->_table;

// checking if we can find the Itemid thru the content
if ( $option == 'com_content' && $Itemid === 0 ) {
	$id = JRequest::getVar( 'id', 0, '', 'int' );
	require_once (JApplicationHelper::getPath('helper', 'com_content'));
	$Itemid = JContentHelper::getItemid($id);
}

/** do we have a valid Itemid yet?? */
if ( $Itemid === 0 ) {
	/** Nope, just use the homepage then. */
	$query = "SELECT id"
	. "\n FROM #__menu"
	. "\n WHERE menutype = 'mainmenu'"
	. "\n AND published = 1"
	. "\n ORDER BY parent, ordering"
	. "\n LIMIT 1"
	;
	$database->setQuery( $query );
	$Itemid = $database->loadResult();
}

// patch to lessen the impact on templates
if ($option == 'search') {
	$option = 'com_search';
}

// set for overlib check
$mainframe->set( 'loadOverlib', false );

$cur_template = JRequest::getVar( 'template', $mainframe->getTemplate(), 'default', 'string' );
$no_html 	  = JRequest::getVar( 'no_html', 0, '', 'int' );
$format 	  = JRequest::getVar( 'format', $no_html ? 'raw' : 'html',  '', 'string'  );
$tmpl 	 	  = JRequest::getVar( 'tmpl', isset($tmpl) ? $tmpl : 'index.php',  '', 'string'  );


if ($mainframe->getCfg('offline') && $user->get('gid') < '23' ) {
	$tmpl = 'offline.php';
}

$params = array(
	'outline'   => JRequest::getVar('tp', 0 ),
	'template' 	=> $cur_template,
	'file'		=> $tmpl 
);


$document =& $mainframe->getDocument($format);
$document->setTitle( $mainframe->getCfg('sitename' ));
$document->display( !$user->get('id') && $mainframe->getCfg('caching_page'), $mainframe->getCfg('gzip'), $params);

JDEBUG ? $_PROFILER->mark( 'afterDisplayOutput' ) : null;

JDEBUG ? $_PROFILER->report() : null;

?>