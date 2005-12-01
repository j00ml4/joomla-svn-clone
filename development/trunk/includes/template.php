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

defined( '_VALID_MOS' ) or die( 'Restricted access' );
/**
* Displays the capture output of the main element
*/
function mosMainBody() {
	global $mainframe;
	
	// message passed via the url
	$mosmsg = mosGetParam( $_REQUEST, 'mosmsg', '' );
	
	if($mainframe->getCfg('offline')) {
		echo "\n<div class=\"offline\">".JText::_('Site is offline')."</div>";
	}

	if ($mosmsg) {
		echo "\n<div class=\"message\">$mosmsg</div>";
	}

	echo $GLOBALS['_MOS_OPTION']['buffer'];
}
/**
* Utility functions and classes
*/
function mosLoadComponent( $name ) {
	// set up some global variables for use by the frontend component
	global $mainframe, $database;
	include( $mainframe->getCfg( 'absolute_path' )."/components/com_$name/$name.php" );
}
/**
* Cache some modules information
* @return array
*/
function &initModules() {
	global $database, $my, $Itemid;

	if (!isset( $GLOBALS['_MOS_MODULES'] )) {
		$query = "SELECT id, title, module, position, content, showtitle, params"
		. "\n FROM #__modules AS m, #__modules_menu AS mm"
		. "\n WHERE m.published = 1"
		. "\n AND m.access <= '". $my->gid ."'"
		. "\n AND m.client_id != 1"
		. "\n AND mm.moduleid = m.id"
		. "\n AND ( mm.menuid = '". $Itemid ."' OR mm.menuid = 0 )"
		. "\n ORDER BY ordering";

		$database->setQuery( $query );
		$modules = $database->loadObjectList();
		foreach ($modules as $module) {
			$GLOBALS['_MOS_MODULES'][$module->position][] = $module;
		}
	}
	return $GLOBALS['_MOS_MODULES'];
}
/**
* @param string THe template position
*/
function mosCountModules( $position='left' ) {

	$tp = mosGetParam( $_GET, 'tp', 0 );
	if ($tp) {
		return 1;
	}

	$modules =& initModules();
	if (isset( $GLOBALS['_MOS_MODULES'][$position] )) {
		return count( $GLOBALS['_MOS_MODULES'][$position] );
	} else {
		return 0;
	}
}
/**
* @param string The position
* @param int The style.  0=normal, 1=horiz, -1=no wrapper
*/
function mosLoadModules( $position='left', $style=0 ) {
	global $mosConfig_lang, $mosConfig_caching, $Itemid;
	
	$tp = mosGetParam( $_GET, 'tp', 0 );
	if ($tp) {
		echo '<div style="height:50px;background-color:#eee;margin:2px;padding:10px;border:1px solid #f00;color:#700;">';
		echo $position;
		echo '</div>';
		return;
	}
	$style = intval( $style );
	$cache =& JFactory::getCache( 'com_content' );

	require_once( JPATH_SITE . '/includes/template.html.php' );

	$allModules =& initModules();
	if (isset( $GLOBALS['_MOS_MODULES'][$position] )) {
		$modules = $GLOBALS['_MOS_MODULES'][$position];
	} else {
		$modules = array();
	}

	foreach ($modules as $module) {

		global $mainframe;

		$lang =& $mainframe->getLanguage();
		$lang->load($module->module);

		// check for custom language file
		//TODO: out-commented for beta phase, to be removed if no problems are reported
		/*$path = JPATH_SITE . '/modules/' . $module->module . $mosConfig_lang .'.php';
		if (file_exists( $path )) {
			//include( $path );
		} else {
			$path = JPATH_SITE .'/modules/'. $module->module .'.eng.php';
			if (file_exists( $path )) {
				include( $path );
			}
		}*/

		$params = new mosParameters( $module->params );

		if(substr( $module->module, 0, 4 )  == 'mod_') {
			ob_start();
			mosLoadModule(substr( $module->module, 4 ), $params);
			$module->content = ob_get_contents();
			ob_end_clean();
		}

		if ($params->get('cache') == 1 && $mosConfig_caching == 1) {
			$cache->call('modules_html::module', $module, $params, $style );
		} else {
			modules_html::module( $module, $params, $style );
		}
	}
}

/**
* Loads an admin module
*/
function mosLoadModule( $name, &$params ) {
	global $mosConfig_sitename, $mosConfig_lang;
	global $mainframe, $database, $my, $Itemid, $acl;
	
	$lang =& $mainframe->getLanguage();
	$lang->load('mod_'.$name);

	$task = mosGetParam( $_REQUEST, 'task', '' );

	$name = str_replace( '/', '', $name );
	$name = str_replace( '\\', '', $name );

	$path = JPATH_SITE . "/modules/mod_$name.php";

	if (file_exists( $path )) {
		require $path;
	}
}

/**
* Assembles head tags
*/
function mosShowHead() {
	global $database, $my, $mainframe, $_VERSION;
	global $mosConfig_favicon;

	$page =& $mainframe->getPage();

	$page->setMetaContentType();
	$page->setMetaData( 'description', $mainframe->getCfg('MetaDesc' ));
	$page->setMetaData( 'keywords', $mainframe->getCfg('MetaKeys' ));

	$page->setMetaData( 'Generator', $_VERSION->PRODUCT . " - " . $_VERSION->COPYRIGHT);
	$page->setMetaData( 'robots', 'index, follow' );
	
	if ( $mainframe->getCfg('sef') ) {
		$page->addCustomTag( '<base href="'. JURL_SITE. '" />' );
	}
	
	if ( $my->id ) {
		$page->addScript( JURL_SITE.'/includes/js/joomla.javascript.js');
	}

	// support for Firefox Live Bookmarks ability for site syndication
	$query = "SELECT a.id"
	. "\n FROM #__components AS a"
	. "\n WHERE a.name = 'Syndicate'"
	;
	$database->setQuery( $query );
	$id = $database->loadResult();

	// load the row from the db table
	$row = new mosComponent( $database );
	$row->load( $id );

	// get params definitions
	$params = new mosParameters( $row->params, $mainframe->getPath( 'com_xml', $row->option ), 'component' );

	$live_bookmark = $params->get( 'live_bookmark', 0 );

	// support for Live Bookmarks ability for site syndication
	if ($live_bookmark) {
		$show = 1;

		$link_file 	= JURL_SITE . '/index2.php?option=com_rss&feed='. $live_bookmark .'&no_html=1';

		// xhtml check
		$link_file = ampReplace( $link_file );

		// outputs link tag for page
		if ($show) {
			$page->addHeadLink( $link_file, 'alternate', array('type' => 'application/rss+xml'));
		}
	}

	// favourites icon
	if ( !$mosConfig_favicon ) {
		$mosConfig_favicon = 'favicon.ico';
	}
	$icon = JPATH_SITE .'/images/'. $mosConfig_favicon;
	// checks to see if file exists
	if ( !file_exists( $icon ) ) {
		$icon = JURL_SITE .'/images/favicon.ico';
	} else {
		$icon = JURL_SITE .'/images/' .$mosConfig_favicon;
	}

	$page->addFavicon($icon);
	
	echo $page->renderHead();

	//load editor
	initEditor();
}
?>