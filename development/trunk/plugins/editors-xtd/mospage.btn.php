<?php
/**
* @version $Id: mospage.btn.php 1402 2005-12-09 17:16:01Z Jinx $
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
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent( 'onCustomEditorButton', 'botMosPageButton' );

/**
* mospage button
* @return array A two element array of ( imageName, textToInsert )
*/
function botMosPageButton() {
	global $option;

	// button is not active in specific content components
	switch ( $option ) {
		case 'com_sections':
		case 'com_categories':
		case 'com_modules':
			$button = array( '', '' );
			break;

		default:
			$button = array( 'mospage.gif', '{mospagebreak}' );
			break;
	}

	return $button;
}
?>