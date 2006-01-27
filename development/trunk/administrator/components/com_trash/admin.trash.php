<?php
/**
* @version $Id$
* @package Joomla
* @subpackage Trash
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// ensure user has access to this function
if (!($acl->acl_check( 'com_trash', 'manage', 'users', $my->usertype ))) {
	mosRedirect( 'index2.php', JText::_('ALERTNOTAUTH') );
}

require_once( JApplicationHelper::getPath( 'admin_html' ) );
require_once( JApplicationHelper::getPath( 'class', 'com_frontpage' ) );

$cid = mosGetParam( $_POST, 'cid', array(0) );
$mid = mosGetParam( $_POST, 'mid', array(0) );
if ( !is_array( $cid ) ) {
	$cid = array(0);
}

switch ($task) {
	case 'deleteconfirm':
		viewdeleteTrash( $cid, $mid, $option );
		break;

	case 'delete':
		deleteTrash( $cid, $option );
		break;

	case 'restoreconfirm':
		viewrestoreTrash( $cid, $mid, $option );
		break;

	case 'restore':
		restoreTrash( $cid, $option );
		break;
	
	case 'viewMenu':
		viewTrashMenu( $option );
		break;	

	case 'viewContent':
		viewTrashContent( $option );
		break;
		
	default:	
		$return = mosGetParam( $_POST, 'return', 'viewContent' );
		if ( $return == 'viewMenu' ) {
			viewTrashMenu( $option );
		} else {
			viewTrashContent( $option );
		}
		break;
}


/**
* Compiles a list of trash items
*/
function viewTrashContent( $option ) {
	global $database, $mainframe;
	
	$filter_order		= $mainframe->getUserStateFromRequest( "$option.viewContent.filter_order", 		'filter_order', 	's.name' );
	$filter_order_Dir	= $mainframe->getUserStateFromRequest( "$option.viewContent.filter_order_Dir",	'filter_order_Dir',	'' );
	$limit 				= $mainframe->getUserStateFromRequest( "limit", 								'limit', 			$mainframe->getCfg('list_limit') );
	$limitstart 		= $mainframe->getUserStateFromRequest( "$option.viewContent.limitstart", 		'limitstart', 		0 );	
	$orderby = "\n ORDER BY $filter_order $filter_order_Dir, s.name, cc.name, c.title";

	// get the total number of content
	$query = "SELECT count(*)"
	. "\n FROM #__content AS c"
	. "\n LEFT JOIN #__categories AS cc ON cc.id = c.catid"
	. "\n LEFT JOIN #__sections AS s ON s.id = cc.section AND s.scope = 'content'"
	. "\n WHERE c.state = -2"
	;
	$database->setQuery( $query );
	$total = $database->loadResult();
	
	require_once( JPATH_ADMINISTRATOR . '/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );
	
	// Query content items
	$query = "SELECT c.*, g.name AS groupname, cc.name AS catname, s.name AS sectname"
	. "\n FROM #__content AS c"
	. "\n LEFT JOIN #__categories AS cc ON cc.id = c.catid"
	. "\n LEFT JOIN #__sections AS s ON s.id = cc.section AND s.scope='content'"
	. "\n INNER JOIN #__groups AS g ON g.id = c.access"
	. "\n LEFT JOIN #__users AS u ON u.id = c.checked_out"
	. "\n WHERE c.state = -2"
	. $orderby
	;
	$database->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$contents = $database->loadObjectList();
	
	for ( $i = 0; $i < $total; $i++ ) {
		if ( ( $contents[$i]->sectionid == 0 ) && ( $contents[$i]->catid == 0 ) ) {
			$contents[$i]->sectname = 'Typed Content';
		}
	}	
	// table ordering
	if ( $filter_order_Dir == 'DESC' ) {
		$lists['order_Dir'] = 'ASC';
	} else {
		$lists['order_Dir'] = 'DESC';
	}
	$lists['order'] = $filter_order;
	
	HTML_trash::showListContent( $option, $contents, $pageNav, $lists );
}

/**
* Compiles a list of trash items
*/
function viewTrashMenu( $option ) {
	global $database, $mainframe;

	$filter_order		= $mainframe->getUserStateFromRequest( "$option.viewMenu.filter_order", 	'filter_order', 	'm.menutype' );
	$filter_order_Dir	= $mainframe->getUserStateFromRequest( "$option.viewMenu.filter_order_Dir",	'filter_order_Dir',	'' );
	$limit 				= $mainframe->getUserStateFromRequest( "limit", 							'limit', 			$mainframe->getCfg('list_limit') );
	$limitstart 		= $mainframe->getUserStateFromRequest( "$option.viewMenu.limitstart", 		'limitstart', 		0 );
	
	$orderby = "\n ORDER BY $filter_order $filter_order_Dir, m.menutype, m.ordering, m.ordering, m.name";	

	$query = "SELECT count(*)"
	. "\n FROM #__menu AS m"
	. "\n LEFT JOIN #__users AS u ON u.id = m.checked_out"
	. "\n WHERE m.published = -2"
	;
	$database->setQuery( $query );
	$total = $database->loadResult();
	
	require_once( JPATH_ADMINISTRATOR . '/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

	// Query menu items
	$query = "SELECT m.*"
	. "\n FROM #__menu AS m"
	. "\n LEFT JOIN #__users AS u ON u.id = m.checked_out"
	. "\n WHERE m.published = -2"
	. $orderby
	;
	$database->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$menus = $database->loadObjectList();

	// table ordering
	if ( $filter_order_Dir == 'DESC' ) {
		$lists['order_Dir'] = 'ASC';
	} else {
		$lists['order_Dir'] = 'DESC';
	}
	$lists['order'] = $filter_order;
	
	HTML_trash::showListMenu( $option, $menus, $pageNav, $lists );
}


/**
* Compiles a list of the items you have selected to permanently delte
*/
function viewdeleteTrash( $cid, $mid, $option ) {
	global $database;

	$return = mosGetParam( $_POST, 'return', 'viewContent' );

	// seperate contentids
	$cids = implode( ',', $cid );
	$mids = implode( ',', $mid );

	if ( $cids ) {
		// Content Items query
		$query = 	"SELECT a.title AS name"
		. "\n FROM #__content AS a"
		. "\n WHERE ( a.id IN ( $cids ) )"
		. "\n ORDER BY a.title"
		;
		$database->setQuery( $query );
		$items = $database->loadObjectList();
		$id = $cid;
		$type = "content";
	} else if ( $mids ) {
		// Content Items query
		$query = 	"SELECT a.name"
		. "\n FROM #__menu AS a"
		. "\n WHERE ( a.id IN ( $mids ) )"
		. "\n ORDER BY a.name"
		;
		$database->setQuery( $query );
		$items = $database->loadObjectList();
		$id = $mid;
		$type = "menu";
	}

	HTML_trash::showDelete( $option, $id, $items, $type, $return );
}


/**
* Permanently deletes the selected list of trash items
*/
function deleteTrash( $cid, $option ) {
	global $database;

	$return = mosGetParam( $_POST, 'return', 'viewContent' );
	$type 	= mosGetParam( $_POST, 'type', array(0) );

	$total = count( $cid );

	if ( $type == 'content' ) {
		$obj =& JModel::getInstance('content', $database );
		$fp = new JFrontPageModel( $database );
		foreach ( $cid as $id ) {
			$id = intval( $id );
			$obj->delete( $id );
			$fp->delete( $id );
		}
	} else if ( $type == "menu" ) {
		$obj =& JModel::getInstance('menu', $database );
		foreach ( $cid as $id ) {
			$id = intval( $id );
			$obj->delete( $id );
		}
	}

	$msg = sprintf( JText::_( 'Item(s) successfully Deleted' ), $total );
	mosRedirect( "index2.php?option=$option&task=$return&mosmsg=$msg" );
}


/**
* Compiles a list of the items you have selected to permanently delte
*/
function viewrestoreTrash( $cid, $mid, $option ) {
	global $database;

	$return = mosGetParam( $_POST, 'return', 'viewContent' );
	
	// seperate contentids
	$cids = implode( ',', $cid );
	$mids = implode( ',', $mid );

	if ( $cids ) {
		// Content Items query
		$query = "SELECT a.title AS name"
		. "\n FROM #__content AS a"
		. "\n WHERE ( a.id IN ( $cids ) )"
		. "\n ORDER BY a.title"
		;
		$database->setQuery( $query );
		$items = $database->loadObjectList();
		$id = $cid;
		$type = "content";
	} else if ( $mids ) {
		// Content Items query
		$query = "SELECT a.name"
		. "\n FROM #__menu AS a"
		. "\n WHERE ( a.id IN ( $mids ) )"
		. "\n ORDER BY a.name"
		;
		$database->setQuery( $query );
		$items = $database->loadObjectList();
		$id = $mid;
		$type = "menu";
	}

	HTML_trash::showRestore( $option, $id, $items, $type, $return );
}


/**
* Restores items selected to normal - restores to an unpublished state
*/
function restoreTrash( $cid, $option ) {
	global $database;

	$return = mosGetParam( $_POST, 'return', 'viewContent' );
	$type 	= mosGetParam( $_POST, 'type', array(0) );

	$total = count( $cid );

	// restores to an unpublished state
	$state 		= 0;
	$ordering 	= 9999;
	//seperate contentids
	$cids = implode( ',', $cid );

	if ( $type == 'content' ) {
		$query = "UPDATE #__content"
		. "\n SET state = $state, ordering = $ordering"
		. "\n WHERE id IN ( $cids )"
		;
	} else if ( $type == "menu" ) {
		$query = "UPDATE #__menu"
		. "\n SET published = $state, ordering = 9999"
		. "\n WHERE id IN ( $cids )"
		;
	}

	$database->setQuery( $query );
	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = sprintf( JText::_( 'Item(s) successfully Restored' ), $total );
	mosRedirect( "index2.php?option=$option&task=$return&mosmsg=$msg" );
}
?>