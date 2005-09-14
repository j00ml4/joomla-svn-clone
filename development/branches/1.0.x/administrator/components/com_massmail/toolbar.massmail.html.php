<?php
/**
* @version $Id: toolbar.massmail.html.php 55 2005-09-09 22:01:38Z eddieajau $
* @package Joomla
* @subpackage Massmail
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
* @subpackage Massmail
*/
class TOOLBAR_massmail {
	/**
	* Draws the menu for a New Contact
	*/
	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::custom('send','publish.png','publish_f2.png','Send Mail',false);
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( 'screen.users.massmail' );
		mosMenuBar::endTable();
	}
}
?>