<?php
/**
* @version $Id: mod_archive.php,v 1.2 2005/08/29 15:52:20 alekandreev Exp $
* @package Mambo
* @copyright (C) 2000 - 2005 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class modArchiveData {

	function getLists( &$params ) {
		global $Itemid, $mainframe, $database;

		$count 				= intval( $params->def( 'count', 10 ) );
		$moduleclass_sfx 	= $params->get( 'moduleclass_sfx' );
		$now 				= $mainframe->getDateTime();

		$query = "SELECT MONTH(created) AS created_month, created, id, sectionid, title, YEAR(created) AS created_year"
		. "\n FROM #__content"
		. "\n WHERE ( state = '-1'"
		. "\n AND active = 1 AND checked_out = '0'"
		. "\n AND sectionid > '0' )"
		. "\n GROUP BY created_year DESC, created_month DESC";
		$database->setQuery( $query, 0, $count );
		$rows = $database->loadObjectList();

		$i = 0;
		foreach ( $rows as $row ) {
			$created_month 	= mosFormatDate ( $row->created, "%m" );
			$month_name 	= mosFormatDate ( $row->created, "%B" );
			$created_year 	= mosFormatDate ( $row->created, "%Y" );
			// Search for global blog - content section
			$query = "SELECT id "
				. "\n FROM #__menu "
				. "\n WHERE type = 'content_archive_category'"
				. "\n AND published = '1'"
				;
			$database->setQuery( $query );
			$_Itemid = $database->loadResult();
			if ( $_Itemid == '' ) {
				// If no Itemid found, use Itemid of page
				$_Itemid = $Itemid;
			}

			$lists[$i]->link	= sefRelToAbs( 'index.php?option=com_content&amp;task=archivecategory&amp;year='. $created_year .'&amp;month='. $created_month .'&amp;module=1&amp;Itemid='.$_Itemid );
			$lists[$i]->text	= $month_name .', '. $created_year;
			$i++;
		}
		return $lists;
	}
}

class modArchive {

	function show ( &$params ) {
		global $my;
		$cache  = mosFactory::getCache( "mod_archive" );

		$cache->setCaching($params->get('cache', 1));
		$cache->setCacheValidation(false);

		$cache->callId( "modArchive::_display", array( $params ), "mod_archive" );
	}

	function _display( &$params ) {
		$tmpl =& moduleScreens::createTemplate( 'mod_archive.html' );

		$lists = modArchiveData::getLists( $params );
		$tmpl->addVar( 'mod_archive', 'class', $params->get( 'moduleclass_sfx' ) );
		$tmpl->addObject( 'mod_archive', '' );
		$tmpl->addObject( 'mod_archive-items', $lists, 'row_' );

		$tmpl->displayParsedTemplate( 'mod_archive' );
	}
}

modArchive::show( $params );
?>
