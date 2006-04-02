<?php
/**
* @version $Id: content.rssbot.php,v 1.0 2005/09/05 00:00:00 stingrey Exp $
* @package Joomla!
* @Copyright (C) 2005 - 2006 Open Source Matters Inc.
* @Copyright (C) 2005 - 2006 Rey Gigataras
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Joomla! is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$mainframe->registerEvent( 'onSyndicate', 'botSyndicateContact' );

/**
* Content RSS method
 * The sql must return the following fields that are used in a common display
 * routine: title, link, description, date
* @param string URL info
*/
function botSyndicateContact( $url, $limit ) {
	global $database, $mainframe;

	// pull data from referral url and place into array
	$parts = null;
    parse_str( $url, $parts );

    // check if this is the correct bot to be loaded for component
    if ( $parts['type'] == 'com_contact' ) {    
        $where  = "\n WHERE a.published = 1";
		$catid  = @$parts['catid'];
		
		if ( $catid ) {
			$where .= "\n AND a.catid = $catid";
		}		

		$link = $mainframe->getBaseURL() .'index.php?option=com_contact&catid='; 
		
		/*
		* All SyndicateBots must return
		* title
		* link
		* description
		* date
		* category
		*/
    	$query = "SELECT"
    	. "\n a.name AS title,"	
    	. "\n CONCAT( '$link', a.catid, '&id=', a.id ) AS link,"
    	. "\n CONCAT( a.con_position, ' - ',a.misc ) AS description,"
    	. "\n '' AS date,"
		. "\n c.title AS category,"
    	. "\n a.id AS id"
    	. "\n FROM #__contact_details AS a"
		. "\n LEFT JOIN #__categories AS c ON c.id = a.catid"
    	. $where
    	. "\n ORDER BY a.catid, a.ordering"
    	;
		$database->setQuery( $query, 0, $limit );
    	$rows = $database->loadObjectList();
    	
    	$count = count( $rows );
    	for ( $i=0; $i < $count; $i++ ) {
    	    $Itemid = $mainframe->getItemid( $rows[$i]->id );
    	    $rows[$i]->link = $rows[$i]->link .'&Itemid='. $Itemid;   
    	}
    	
    	return $rows;
    }
}
?>