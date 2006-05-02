<?php
/**
* @version $Id: weblinks.searchbot.php,v 1.1 2005/08/25 14:23:44 johanjanssens Exp $
* @package Mambo
* @copyright (C) 2000 - 2005 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$_MAMBOTS->registerFunction( 'onSearch', 'botSearchWeblinks' );
$_MAMBOTS->registerFunction( 'onSearchAreas', 'botSearchWeblinksAreas' );

$GLOBALS['_SEARCH_WEBLINKS_AREAS'] = array(
	'weblinks' => 'Weblinks'
);

/**
 * @return array An array of search areas
 */
function &botSearchWeblinksAreas() {
	return $GLOBALS['_SEARCH_WEBLINKS_AREAS'];
}

/**
 * Weblink Search method
 *
 * The sql must return the following fields that are used in a common display
 * routine: href, title, section, created, text, browsernav
 * @param string Target search string
 * @param string mathcing option, exact|any|all
 * @param string ordering option, newest|oldest|popular|alpha|category
 * @param mixed An array if the search it to be restricted to areas, null if search all
 */
function botSearchWeblinks( $text, $phrase='', $ordering='', $areas=null ) {
	global $database, $my, $_LANG;

	if ( is_array( $areas ) ) {
		if ( !array_intersect( $areas, array_keys( $GLOBALS['_SEARCH_WEBLINKS_AREAS'] ) ) ) {
			return array();
		}
	}

	$text = trim( $text );
	if ($text == '') {
		return array();
	}
	$section 	= $_LANG->_( 'WEBLINKS_TITLE' );

	$wheres 	= array();
	switch ($phrase) {
		case 'exact':
			$wheres2 = array();

			$wheres2[] = "LOWER( a.url ) LIKE '%$text%'";
			$wheres2[] = "LOWER( a.description ) LIKE '%$text%'";
			$wheres2[] = "LOWER( a.title ) LIKE '%$text%'";
			$where = '(' . implode( ') OR (', $wheres2 ) . ')';
			break;
		case 'all':
		case 'any':
		default:
			$words 	= explode( ' ', $text );
			$wheres = array();
			foreach ($words as $word) {
				$wheres2 = array();
		  	    $wheres2[] 	= "LOWER( a.url ) LIKE '%$word%'";
			    $wheres2[] 	= "LOWER( a.description ) LIKE '%$word%'";
			    $wheres2[] 	= "LOWER( a.title ) LIKE '%$word%'";
				$wheres[] 	= implode( ' OR ', $wheres2 );
			}
			$where 	= '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
			break;
	}

	switch ( $ordering ) {
		case 'oldest':
			$order = 'a.date ASC';
			break;

		case 'popular':
			$order = 'a.hits DESC';
			break;

		case 'alpha':
			$order = 'a.title ASC';
			break;

		case 'category':
			$order = 'b.title ASC, a.title ASC';
			break;

		case 'newest':
		default:
			$order = 'a.date DESC';
	}

	$query = "SELECT a.title AS title,"
	. "\n a.description AS text,"
	. "\n a.date AS created,"
	. "\n CONCAT_WS( ' / ', '$section', b.title ) AS section,"
	. "\n '1' AS browsernav,"
	. "\n a.url AS href"
	. "\n FROM #__weblinks AS a"
	. "\n INNER JOIN #__categories AS b ON b.id = a.catid AND b.access <= '$my->gid'"
	. "\n WHERE ( $where )"
	. "\n ORDER BY $order"
	;
	$database->setQuery( $query );
	$rows = $database->loadObjectList();
	return $rows;
}
?>