<?php
/**
* @version $Id$
* @package Joomla
* @subpackage Syndicate
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @package Joomla
* @subpackage Syndicate
*/
class TOOLBAR_syndicate {

	function _DEFAULT() {
		JMenuBar::startTable();
		JMenuBar::title(   JText::_( 'Syndication Settings' ) );
		JMenuBar::save();
		JMenuBar::spacer();
		JMenuBar::cancel();
		JMenuBar::spacer();
		JMenuBar::help( 'screen.syndicate' );
		JMenuBar::endTable();
	}
}
?>