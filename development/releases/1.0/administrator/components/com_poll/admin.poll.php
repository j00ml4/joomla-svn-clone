<?php
/**
* @version $Id$
* @package Joomla
* @subpackage Polls
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

// ensure user has access to this function
if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )
		| $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_poll' ))) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}

require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mainframe->getPath( 'class' ) );

$cid = josGetArrayInts( 'cid' );

switch( $task ) {
	case 'new':
		editPoll( 0, $option );
		break;

	case 'edit':
		editPoll( intval( $cid[0] ), $option );
		break;

	case 'editA':
		editPoll( $id, $option );
		break;

	case 'save':
		savePoll( $option );
		break;

	case 'remove':
		removePoll( $cid, $option );
		break;

	case 'publish':
		publishPolls( $cid, 1, $option );
		break;

	case 'unpublish':
		publishPolls( $cid, 0, $option );
		break;

	case 'cancel':
		cancelPoll( $option );
		break;

	default:
		showPolls( $option );
		break;
}

function showPolls( $option ) {
	global $database, $mainframe, $mosConfig_list_limit;

	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );

	$query = "SELECT COUNT(*)"
	. "\n FROM #__polls"
	;
	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );

	$query = "SELECT m.*, u.name AS editor,"
	. "\n COUNT(d.id) AS numoptions"
	. "\n FROM #__polls AS m"
	. "\n LEFT JOIN #__users AS u ON u.id = m.checked_out"
	. "\n LEFT JOIN #__poll_data AS d ON d.pollid = m.id AND d.text != ''"
	. "\n GROUP BY m.id"
	;
	$database->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$rows = $database->loadObjectList();

	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}

	HTML_poll::showPolls( $rows, $pageNav, $option );
}

function editPoll( $uid=0, $option='com_poll' ) {
	global $database, $my;

	$row = new mosPoll( $database );
	// load the row from the db table
	$row->load( (int)$uid );

	// fail if checked out not by 'me'
	if ($row->isCheckedOut( $my->id )) {
		mosRedirect( 'index2.php?option='. $option, 'The poll '. $row->title .' is currently being edited by another administrator.' );
	}

	$options = array();

	if ($uid) {
		$row->checkout( $my->id );
		$query = "SELECT id, text"
		. "\n FROM #__poll_data"
		. "\n WHERE pollid = " . (int) $uid
		. "\n ORDER BY id"
		;
		$database->setQuery($query);
		$options = $database->loadObjectList();
	} else {
		$row->lag 		= 3600 * 24;
		$row->published = 1;
	}

	// get selected pages
	if ( $uid ) {
		$query = "SELECT menuid AS value"
		. "\n FROM #__poll_menu"
		. "\n WHERE pollid = " . (int) $row->id
		;
		$database->setQuery( $query );
		$lookup = $database->loadObjectList();
	} else {
		$lookup = array( mosHTML::makeOption( 0, 'All' ) );
	}

	// build the html select list
	$lists['select'] 	= mosAdminMenus::MenuLinks( $lookup, 1, 1 );

	// build the html select list for published
	$lists['published'] = mosAdminMenus::Published( $row );
	
	HTML_poll::editPoll($row, $options, $lists );
}

function savePoll( $option ) {
	global $database, $my;
	
	josSpoofCheck();

	// save the poll parent information
	$row = new mosPoll( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$isNew = ($row->id == 0);

	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	// save the poll options
	$options = mosGetParam( $_POST, 'polloption', array() );

	foreach ($options as $i=>$text) {
		if (!get_magic_quotes_gpc()) {
			// The poll module has always been this way, so we'll just stick with that and add
			// additional backslashes if needed. They will be stripped upon display
			$text = addslashes( $text );
		}
		if ($isNew) {
			$query = "INSERT INTO #__poll_data"
			. "\n ( pollid, text )"
			. "\n VALUES ( " . (int) $row->id . ", " . $database->Quote( $text ) . " )"
			;
			$database->setQuery( $query );
			$database->query();
		} else {
			$query = "UPDATE #__poll_data"
			. "\n SET text = " . $database->Quote($text)
			. "\n WHERE id = " . (int) $i
			. "\n AND pollid = " . (int) $row->id
			;
			$database->setQuery( $query );
			$database->query();
		}
	}

	// update the menu visibility
	$selections = mosGetParam( $_POST, 'selections', array() );

	$query = "DELETE FROM #__poll_menu"
	. "\n WHERE pollid = " . (int) $row->id
	;
	$database->setQuery( $query );
	$database->query();

	for ($i=0, $n=count($selections); $i < $n; $i++) {
		$query = "INSERT INTO #__poll_menu"
		. "\n SET pollid = " . (int) $row->id . ", menuid = " . (int) $selections[$i]
		;
		$database->setQuery( $query );
		$database->query();
	}

	mosRedirect( 'index2.php?option='. $option );
}

function removePoll( $cid, $option ) {
	global $database;
	
	josSpoofCheck();
	
	$msg = '';
	for ($i=0, $n=count($cid); $i < $n; $i++) {
		$poll = new mosPoll( $database );
		if (!$poll->delete( $cid[$i] )) {
			$msg .= $poll->getError();
		}
	}
	mosRedirect( 'index2.php?option='. $option .'&mosmsg='. $msg );
}

/**
* Publishes or Unpublishes one or more records
* @param array An array of unique category id numbers
* @param integer 0 if unpublishing, 1 if publishing
* @param string The current url option
*/
function publishPolls( $cid=null, $publish=1, $option ) {
	global $database, $my;
	
	josSpoofCheck();

	if (!is_array( $cid ) || count( $cid ) < 1) {
		$action = $publish ? 'publish' : 'unpublish';
		echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
		exit;
	}

	mosArrayToInts( $cid );
	$cids = 'id=' . implode( ' OR id=', $cid );

	$query = "UPDATE #__polls"
	. "\n SET published = " . intval( $publish )
	. "\n WHERE ( $cids )"
	. "\n AND ( checked_out = 0 OR ( checked_out = " . (int) $my->id  . " ) )"
	;
	$database->setQuery( $query );
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if (count( $cid ) == 1) {
		$row = new mosPoll( $database );
		$row->checkin( $cid[0] );
	}
	mosRedirect( 'index2.php?option='. $option );
}

function cancelPoll( $option ) {
	
	josSpoofCheck();

	global $database;
	$row = new mosPoll( $database );
	$row->bind( $_POST );
	$row->checkin();
	mosRedirect( 'index2.php?option='. $option );
}
?>
