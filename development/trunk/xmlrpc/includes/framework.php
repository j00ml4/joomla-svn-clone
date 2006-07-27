<?php
/**
* @version $Id$
* @package Joomla
* @subpackage Installation
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

error_reporting( E_ALL );
@set_magic_quotes_runtime( 0 );

if (!file_exists( JPATH_CONFIGURATION . DS . 'configuration.php')) {
	// TODO: Throw 500 error
	header( 'Location: ../installation/index.php' );
	exit();
}

//Globals
$GLOBALS['mosConfig_absolute_path'] = JPATH_SITE.DIRECTORY_SEPARATOR;
$GLOBALS['mosConfig_sitename']      = 'Joomla! - XML-RPC';

require_once( JPATH_LIBRARIES.DS.'loader.php');

if (in_array( 'globals', array_keys( array_change_key_case( $_REQUEST, CASE_LOWER ) ) ) ) {
	die( 'Fatal error.  Global variable hack attempted.' );
}
if (in_array( '_post', array_keys( array_change_key_case( $_REQUEST, CASE_LOWER ) ) ) ) {
	die( 'Fatal error.  Post variable hack attempted.' );
}

//File includes
define( 'JPATH_INCLUDES', dirname(__FILE__) );

//Library imports
jimport( 'joomla.common.compat.compat' );
jimport( 'joomla.common.abstract.object' );

jimport( 'joomla.utilities.error');
jimport( 'joomla.factory' );
jimport( 'joomla.filesystem.*' );
jimport( 'joomla.i18n.language' );
jimport( 'joomla.i18n.string' );
jimport( 'joomla.database.table' );
jimport( 'joomla.environment.request' );
jimport( 'joomla.environment.session' );
jimport( 'joomla.application.application');
jimport( 'joomla.application.event');
jimport( 'joomla.application.extension.plugin');
jimport( 'joomla.application.user.user' );
?>