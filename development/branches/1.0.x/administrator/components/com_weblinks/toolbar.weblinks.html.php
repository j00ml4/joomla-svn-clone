<?php
/**
* @version $Id: toolbar.weblinks.html.php 55 2005-09-09 22:01:38Z eddieajau $
* @package Joomla
* @subpackage Weblinks
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software and parts of it may contain or be derived from works
* licensed under the GNU General Public License or other free or open source
* software licenses. See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* @package Joomla
* @subpackage Weblinks
*/
class TOOLBAR_weblinks {
	function _EDIT() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::spacer();
		if ( $id ) {
			// for existing content items the button is renamed `close`
			mosMenuBar::cancel( 'cancel', 'Close' );
		} else {
			mosMenuBar::cancel();
		}
		mosMenuBar::spacer();
		mosMenuBar::help( 'screen.weblink.edit' );
		mosMenuBar::endTable();
	}
	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::publishList();
		mosMenuBar::spacer();
		mosMenuBar::unpublishList();
		mosMenuBar::spacer();
		mosMenuBar::addNewX();
		mosMenuBar::spacer();
		mosMenuBar::editListX();
		mosMenuBar::spacer();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::help( 'screen.weblink' );
		mosMenuBar::endTable();
	}
}
?>