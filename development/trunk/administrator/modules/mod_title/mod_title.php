<?php
/**
* @version $Id: mod_toolbar.php 1879 2006-01-17 20:35:15Z webImagery $
* @package Joomla
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/*
 * Get the component title div
 */
$title = $mainframe->get('JComponentTitle');

/*
 * Echo title if it exists
 */
if (!empty($title))
{
	echo $title;
}
?>