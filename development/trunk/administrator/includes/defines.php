<?php
/**
* @version $Id$
* @package Joomla
* @subpackage Installation
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

//Global definitions
define('DS', DIRECTORY_SEPARATOR);

//Joomla framework path definitions
$path = str_replace( '\\', '/', JPATH_BASE );
$parts = explode( '/', $path );
array_pop( $parts );

//Defines
define( 'JPATH_ROOT'         , implode( '/', $parts ) );

define( 'JPATH_SITE'         , JPATH_ROOT );
define( 'JPATH_ADMINISTRATOR', JPATH_ROOT . DS . 'administrator' );
define( 'JPATH_LIBRARIES'    , JPATH_ROOT . DS . 'libraries' );
define( 'JPATH_INSTALLATION' , JPATH_ROOT . DS . 'installation' );

if (!defined( 'JPATH_CONFIG' )) {
	define( 'JPATH_CONFIG' , JPATH_ROOT );
}
?>