<?php
/**
* @version $Id: toolbar.admin.html.php 178 2005-09-13 12:19:24Z stingrey $
* @package Joomla
* @subpackage Admin
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software and parts of it may contain or be derived from the
* GNU General Public License or other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* @package Joomla
* @subpackage Admin
*/
class TOOLBAR_admin {
	function _SYSINFO() {
		mosMenuBar::startTable();
		mosMenuBar::help( 'screen.system.info' );
		mosMenuBar::endTable();
	}
	/**
	* Draws the menu for a New category
	*/
	function _CPANEL() {
		mosMenuBar::startTable();
		mosMenuBar::help( 'screen.cpanel' );
		mosMenuBar::endTable();
	}
	/**
	* Draws the menu for a New category
	*/
	function _DEFAULT() {
		mosMenuBar::startTable();
		//mosMenuBar::help( 'screen.cpanel' );
		mosMenuBar::endTable();
	}
}
?>