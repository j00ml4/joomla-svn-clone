<?php
/**
* @version $Id$
* @package Joomla
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

//Global definitions
define('DS', DIRECTORY_SEPARATOR);

//Joomla framework path definitions
$parts = explode( DS, JPATH_BASE );
array_pop( $parts );

//Defines
define( 'JPATH_ROOT'         , implode( DS, $parts ) );

define( 'JPATH_SITE'         , JPATH_ROOT );
define( 'JPATH_CONFIGURATION', JPATH_ROOT );
define( 'JPATH_ADMINISTRATOR', JPATH_ROOT . DS . 'administrator' );
define( 'JPATH_LIBRARIES'    , JPATH_ROOT . DS . 'libraries' );
define( 'JPATH_INSTALLATION' , JPATH_ROOT . DS . 'installation' );
set_include_path( JPATH_LIBRARIES . DS . 'pear');
?>