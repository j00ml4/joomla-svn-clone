<?php
/**
* @version $Id: mod_stats.php,v 1.2 2005/08/29 15:52:20 alekandreev Exp $
* @package Mambo
* @copyright (C) 2000 - 2005 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class modStatsData {

	function &getRows( &$params ){
		global $database;
		global $mosConfig_offset, $mosConfig_caching, $mosConfig_enable_stats, $mosConfig_gzip;
		global $_LANG;

		$serverinfo 		= $params->get( 'serverinfo' );
		$siteinfo 			= $params->get( 'siteinfo' );
		$moduleclass_sfx 	= $params->get( 'moduleclass_sfx' );
		$counter 			= $params->get( 'counter' );
		$increase 			= $params->get( 'increase' );

		$i = 0;
		if ( $serverinfo ) {
			$rows[$i]->title 	= $_LANG->_( 'OS' );
			$rows[$i]->data 	= substr( php_uname(), 0, 7 );
			$i++;
			$rows[$i]->title 	= $_LANG->_( 'PHP' );
			$rows[$i]->data 	= phpversion();
			$i++;
			$rows[$i]->title 	= $_LANG->_( 'MySQL' );
			$rows[$i]->data 	= mysql_get_server_info();
			$i++;
			$rows[$i]->title 	= $_LANG->_( 'Time' );
			$rows[$i]->data 	= date( 'H:i', time() + ( $mosConfig_offset * 60 * 60 ) );
			$i++;
			$rows[$i]->title 	= $_LANG->_( 'Caching' );
			$rows[$i]->data 	= $mosConfig_caching ? $_LANG->_( 'Enabled' ):$_LANG->_( 'Disabled' );
			$i++;
			$rows[$i]->title 	= $_LANG->_( 'GZip' );
			$rows[$i]->data 	= $mosConfig_gzip ? $_LANG->_( 'Enabled' ):$_LANG->_( 'Disabled' );;
			$i++;
		}

		if ( $siteinfo ) {
			$query = "SELECT COUNT( id ) AS count_users"
			. "\n FROM #__users"
			;
			$database->setQuery( $query );
			$members = $database->loadResult();

			$query = "SELECT COUNT( id ) AS count_items"
			. "\n FROM #__content"
			. "\n WHERE active = 1 AND state = '1'"
			;
			$database->setQuery( $query );
			$items = $database->loadResult();

			$query = "SELECT COUNT( id ) AS count_links"
			. "\n FROM #__weblinks"
			. "\n WHERE published = '1'"
			;
			$database->setQuery( $query );
			$links = $database->loadResult();

			if ( $members ) {
				$rows[$i]->title 	= $_LANG->_( 'Members' );
				$rows[$i]->data 	= $members;
				$i++;
			}

			if ( $items ) {
				$rows[$i]->title 	= $_LANG->_( 'Content' );
				$rows[$i]->data 	= $items;
				$i++;
			}

			if ( $links ) {
				$rows[$i]->title 	= $_LANG->_( 'Web Links' );
				$rows[$i]->data 	= $links;
				$i++;
			}
		}

		if ( $mosConfig_enable_stats && $counter ) {
			$query = "SELECT SUM( hits ) AS count"
			. "\n FROM #__stats_agents"
			. "\n WHERE type = '1'";
			$database->setQuery( $query );
			$hits = $database->loadResult();

			$hits = $hits + $increase;
			if ( $hits == NULL ) {
				$hits = 0;
			}

			$rows[$i]->title 	= $_LANG->_( 'Visitors' );
			$rows[$i]->data 	= $hits;
		}

		return $rows;
	}
}

class modStats {

	function show ( &$params ) {
		global $my;

		$cache  = mosFactory::getCache( "mod_stats" );

		$cache->setCaching($params->get('cache', 1));
		$cache->setLifeTime($params->get('cache_time', 900));
		$cache->setCacheValidation(true);

		$cache->callId( "modStats::_display", array( $params ), "mod_stats".$my->gid );
	}


	function _display( &$params ) {

		$rows = modStatsData::getRows( $params );

		$tmpl =& moduleScreens::createTemplate( 'mod_stats.html' );

		$tmpl->addVar( 'mod_stats', 'class', 	$params->get( 'moduleclass_sfx' ) );
		$tmpl->addObject( 'mod_stats-items', $rows, 'row_' );

		$tmpl->displayParsedTemplate( 'mod_stats' );
	}
}

modStats::show( $params );
?>