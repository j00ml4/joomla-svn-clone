<?php
/**
* @version $Id: admin.weblinks.php,v 1.1 2005/08/25 14:17:38 johanjanssens Exp $
* @package Mambo
* @subpackage Weblinks
* @copyright (C) 2000 - 2005 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// ensure user has access to this function
if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )
 | $acl->acl_check( 'com_weblinks', 'manage', 'users', $my->usertype ))) {
	mosRedirect( 'index2.php', $_LANG->_('NOT_AUTH') );
}

mosFS::load( '@class' );
mosFS::load( '@admin_html' );

switch ( $task ) {
	case 'new':
		editWeblink( $option, 0 );
		break;

	case 'edit':
		editWeblink( $option, $cid[0] );
		break;

	case 'editA':
		editWeblink( $option, $id );
		break;

	case 'save':
	case 'apply':
		saveWeblink( $task );
		break;

	case 'remove':
		removeWeblinks( $cid );
		break;

	case 'publish':
		publishWeblinks( $cid, 1 );
		break;

	case 'unpublish':
		publishWeblinks( $cid, 0 );
		break;

	case 'approve':
		break;

	case 'cancel':
		cancelWeblink( );
		break;

	case 'orderup':
		orderWeblinks( $cid[0], -1 );
		break;

	case 'orderdown':
		orderWeblinks( $cid[0], 1 );
		break;

	case 'saveorder':
		saveOrder( $cid );
		break;

	case 'checkin':
		checkin( $id );
		break;

	default:
		showWeblinks( $option );
		break;
}

/**
* Compiles a list of records
* @param database A database connector object
*/
function showWeblinks( $option ) {
	global $database, $mainframe, $mosConfig_list_limit;

	$filter_state	= $mainframe->getUserStateFromRequest( "filter_state{$option}", 'filter_state', NULL );
	$catid 			= $mainframe->getUserStateFromRequest( "catid{$option}", 'catid', 0 );
	$limit 			= $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
	$limitstart 	= $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 );
	$search 		= $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	$search 		= trim( strtolower( $search ) );
	$tOrder			= mosGetParam( $_POST, 'tOrder', 'category' );
	$tOrder_old		= mosGetParam( $_POST, 'tOrder_old', 'category' );
	
	mosFS::load( '@class', 'com_components' );

	// table column ordering values
	if ( $tOrder_old <> $tOrder && ( $tOrder <> 'a.published' ) ) {
		$tOrderDir = 'ASC';
	} else {
		$tOrderDir = mosGetParam( $_POST, 'tOrderDir', 'ASC' );
	}
	if ( $tOrderDir == 'ASC' ) {
		$lists['tOrderDir'] 	= 'DESC';
	} else {
		$lists['tOrderDir'] 	= 'ASC';
	}
	$lists['tOrder'] = $tOrder;
	
	$where = array();

	if ($catid > 0) {
		$where[] = "a.catid='$catid'";
	}
	if ($search) {
		$where[] = "LOWER(a.title) LIKE '%$search%'";
	}
	if ( $filter_state <> NULL ) {
		$where[] = "a.published = '$filter_state'";
	}
	if ( count( $where ) ) {
		$where = "\n WHERE ". implode( ' AND ', $where );	
	} else {
		$where = '';
	}


	// table column ordering
	switch ( $tOrder ) {
		default:
			$order = "\n ORDER BY $tOrder $tOrderDir, category ASC, a.ordering ASC";		
			break;
	}		

	// get the total number of records
	$query = "SELECT COUNT( * )"
	. "\n FROM #__weblinks AS a"
	. $where
	;
	$database->setQuery( $query );
	$total = $database->loadResult();

	// load navigation files
	mosFS::load( '@pageNavigationAdmin' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );

	// main query 
	$query = "SELECT a.*, cc.name AS category, u.name AS editor"
	. "\n FROM #__weblinks AS a"
	. "\n LEFT JOIN #__categories AS cc ON cc.id = a.catid"
	. "\n LEFT JOIN #__users AS u ON u.id = a.checked_out"
	. $where
	. $order
	//. "\n ORDER BY a.catid, a.ordering"
	;
	$database->setQuery( $query, $pageNav->limitstart, $pageNav->limit );

	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		mosErrorAlert( $database->stderr() );
	}

	// get list of State for dropdown filter
	$javascript 	= 'onchange="document.adminForm.submit();"';
	$lists['state']	= mosAdminHTML::stateList( 'filter_state', $filter_state, $javascript );

	// build list of categories
	$javascript = 'onchange="document.adminForm.submit();"';
	$lists['catid'] = mosComponentFactory::buildCategoryList( 'catid', $option, intval( $catid ), $javascript );

	$lists['search'] = stripslashes( $search );

	HTML_weblinks::showWeblinks( $option, $rows, $lists, $pageNav );
}

/**
* Compiles information to add or edit
* @param integer The unique id of the record to edit (0 if new)
*/
function editWeblink( $option, $id ) {
	global $database, $my, $mainframe, $mosConfig_absolute_path, $mosConfig_live_site;
	global $_LANG;
	
	$mainframe->set('disableMenu', true);

	$lists = array();

	$row = new mosWeblink( $database );
	// load the row from the db table
	$row->load( $id );

	// fail if checked out not by 'me'
	if ($row->isCheckedOut()) {
		mosRedirect( 'index2.php?option='. $option, $_LANG->_( 'The module' ) .' '. $row->title .' '. $_LANG->_( 'descBeingEditted' ) );
	}

	mosFS::load( '@class', 'com_components' );

	if ($id) {
		$row->checkout( $my->id );
	} else {
		// initialise new record
		$row->published 		= 1;
		$row->approved 		= 1;
		$row->order 			= 0;
		$row->catid = mosGetParam( $_POST, 'catid', 0 );
	}

	// build the html select list for ordering
	$query = "SELECT ordering AS value, title AS text"
	. "\n FROM #__weblinks"
	. "\n WHERE catid='$row->catid'"
	. "\n ORDER BY ordering"
	;
	$lists['ordering'] 			= mosAdminMenus::SpecificOrdering( $row, $id, $query, 1 );

	// build list of categories
	$lists['catid'] 			= mosComponentFactory::buildCategoryList( 'catid', $option, intval( $row->catid ) );
	// build the html select list
	$lists['approved'] 			= mosHTML::yesnoRadioList( 'approved', 'class="inputbox"', $row->approved );
	// build the html select list
	$lists['published'] 		= mosHTML::yesnoRadioList( 'published', 'class="inputbox"', $row->published );

	$file = $mosConfig_absolute_path .'/administrator/components/com_weblinks/weblinks_item.xml';
	$params =& new mosParameters( $row->params, $file, 'component' );

	HTML_weblinks::editWeblink( $row, $lists, $params, $option );
}

/**
* Saves the record on an edit form submit
* @param database A database connector object
*/
function saveWeblink( $task ) {
	global $database, $my;
	global $_LANG;

	$row = new mosWeblink( $database );
	
	if (!$row->bind( $_POST )) {
		mosErrorAlert( $_LANG->_( $row->getError() ) );
	}
	
	// save params
	$params = mosGetParam( $_POST, 'params', '' );
	if (is_array( $params )) {
		$txt = array();
		foreach ( $params as $k=>$v) {
			$txt[] = "$k=$v";
		}
		$row->params = implode( "\n", $txt );
	}

	$row->date = date( "Y-m-d H:i:s" );
	if (!$row->check()) {
		mosErrorAlert( $_LANG->_( $row->getError() ) );
	}
	if (!$row->store()) {
		mosErrorAlert( $_LANG->_( $row->getError() ) );
	}
	$row->checkin();
	$row->updateOrder( "catid='$row->catid'" );

	switch ( $task ) {
		case 'apply':
			$msg = $_LANG->_( 'Successfully Saved changes' );
			mosRedirect( 'index2.php?option=com_weblinks&task=editA&id='. $row->id, $msg );
			break;

		case 'save':
		default:
			$msg = $_LANG->_( 'Successfully Saved' );
			mosRedirect( 'index2.php?option=com_weblinks', $msg );
			break;
	}
}

/**
* Deletes one or more records
* @param array An array of unique category id numbers
* @param string The current url option
*/
function removeWeblinks( $cid ) {
	global $database;
	global $_LANG;

	if (!is_array( $cid ) || count( $cid ) < 1) {
		mosErrorAlert( $_LANG->_( 'Select an item to delete' ) );
	}
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__weblinks WHERE id IN ($cids)";
		$database->setQuery( $query );
		if (!$database->query()) {
			mosErrorAlert( $database->getErrorMsg() );
		}
	}

	mosRedirect( 'index2.php?option=com_weblinks' );
}

/**
* Publishes or Unpublishes one or more records
* @param array An array of unique category id numbers
* @param integer 0 if unpublishing, 1 if publishing
* @param string The current url option
*/
function publishWeblinks( $cid=null, $publish=1 ) {
	global $database, $my;
	global $_LANG;

	$catid = mosGetParam( $_POST, 'catid', array(0) );

	if (!is_array( $cid ) || count( $cid ) < 1) {
		$action = $publish ? 'publish' : 'unpublish';
		mosErrorAlert( $_LANG->_( 'Select an item to' ) .' '. $action );
	}

	$cids = implode( ',', $cid );

	$query = "UPDATE #__weblinks"
	. "\n SET published = '$publish'"
	. "\n WHERE id IN ( $cids )"
	. "\n AND ( checked_out = 0 OR ( checked_out = '$my->id' ) )"
	;
	$database->setQuery( $query );
	if (!$database->query()) {
		mosErrorAlert( $database->getErrorMsg() );
	}

	if ( count( $cid ) == 1 ) {
		$row = new mosWeblink( $database );
		$row->checkin( $cid[0] );
	}
	mosRedirect( 'index2.php?option=com_weblinks' );
}
/**
* Moves the order of a record
* @param integer The increment to reorder by
*/
function orderWeblinks( $uid, $inc ) {
	global $database;
	
	$row = new mosWeblink( $database );
	$row->load( $uid );
	$row->move( $inc, "published >= 0" );

	mosRedirect( 'index2.php?option=com_weblinks' );
}

/**
* Cancels an edit operation
* @param string The current url option
*/
function cancelWeblink( ) {
	global $database;
	
	$row = new mosWeblink( $database );
	$row->bind( $_POST );
	$row->checkin();
	
	mosRedirect( 'index2.php?option=com_weblinks' );
}

function saveOrder( &$cid ) {
	global $database;
  	global $_LANG;
	
	$total		= count( $cid );
	$order 		= mosGetParam( $_POST, 'order', array(0) );
	$row 		= new mosWeblink( $database );
	$conditions = array();

	// update ordering values
	for( $i=0; $i < $total; $i++ ) {
		$row->load( $cid[$i] );
		
		if ( $row->ordering != $order[$i] ) {
			$row->ordering = $order[$i];
			if (!$row->store()) {
				mosErrorAlert( $database->getErrorMsg() );
			} // if
			
			// remember to updateOrder this group
			$condition = "catid='$row->catid'";
			$found = false;
			foreach ( $conditions as $cond ) {
				if ($cond[1]==$condition) {
					$found = true;
					break;
				} // if
			}   
			if ( !$found ) {
				$conditions[] = array($row->id, $condition);
			}
		} // if
	} // for

	// execute updateOrder for each group
	foreach ( $conditions as $cond ) {
		$row->load( $cond[0] );
		$row->updateOrder( $cond[1] );
	} // foreach

	$msg = $_LANG->_( 'New ordering saved' );
	mosRedirect( 'index2.php?option=com_weblinks', $msg );
} // saveOrder

function checkin( $id ) {	
	global $database;
	global $_LANG;
	
	$row = new mosWeblink( $database );
	$row->load( $id );
	// checkin item
	$row->checkin();

	$msg = $_LANG->_( 'Item Checked In' );
	mosRedirect( 'index2.php?option=com_weblinks', $msg );	
}
?>