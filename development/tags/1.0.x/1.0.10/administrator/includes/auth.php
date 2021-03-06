<?php
/**
* @version $Id: auth.php 3495 2006-05-15 01:44:00Z stingrey $
* @package Joomla
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

$basePath 	= dirname( __FILE__ );
$path 		= $basePath . '/../../configuration.php';
require( $path );

if (!defined( '_MOS_MAMBO_INCLUDED' )) {
	$path = $basePath . '/../../includes/joomla.php';
	require( $path );
}

session_name( md5( $mosConfig_live_site ) );
session_start();
// restore some session variables
if (!isset( $my )) {
	$my = new mosUser( $database );
}

$my->id 		= intval( mosGetParam( $_SESSION, 'session_user_id', '' ) );
$my->username 	= strval( mosGetParam( $_SESSION, 'session_username', '' ) );
$my->usertype 	= strval( mosGetParam( $_SESSION, 'session_usertype', '' ) );
$my->gid 		= intval( mosGetParam( $_SESSION, 'session_gid', '' ) );
$session_id 	= strval( mosGetParam( $_SESSION, 'session_id', '' ) );
$logintime 		= strval( mosGetParam( $_SESSION, 'session_logintime', '' ) );

if ( $session_id != md5( $my->id.$my->username.$my->usertype.$logintime ) ) {
	mosRedirect( "index.php" );
	die;
}
?>