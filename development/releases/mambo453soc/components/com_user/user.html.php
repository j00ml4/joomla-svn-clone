<?php
/**
* @version $Id: user.html.php,v 1.1 2005/08/25 14:18:15 johanjanssens Exp $
* @package Mambo
* @subpackage Users
* @copyright (C) 2000 - 2005 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * @package Mambo
 * @subpackage User
 */
class userScreens_front {
	/**
	 * @param string The main template file to include for output
	 * @param array An array of other standard files to include
	 * @return patTemplate A template object
	 */
	function &createTemplate( $bodyHtml='', $files=null ) {
		$tmpl =& mosFactory::getPatTemplate( $files );

		$directory = mosComponentDirectory( $bodyHtml, dirname( __FILE__ ) );
		$tmpl->setRoot( $directory );

		$tmpl->setAttribute( 'body', 'src', $bodyHtml );

		return $tmpl;
	}

	function welcome() {
		global $mainframe;

		$tmpl =& userScreens_front::createTemplate( 'welcome.html' );

		$params =& new mosParameters( '' );
		$params->def( 'back_button', $mainframe->getCfg( 'back_button' ) );

		$tmpl->addObject( 'body', $params->toObject(), 'p_' );

		$tmpl->displayParsedTemplate( 'body' );
	}

	function edit( &$row, &$params, &$list ) {
		global $mosConfig_username_length, $mosConfig_username_change, $mosConfig_name_change, $mosConfig_password_length;
		global $_LANG;

		$Ndisabled 			= '';
		$UNdisabled 		= '';
		$txt 				= sprintf( $_LANG->_( 'VALID_AZ09' ), $_LANG->_( 'PROMPT_UNAME' ), $mosConfig_username_length - 1 );
		$Ndisabled_tip 		= '';
		$UNdisabled_tip 	= $txt;
		if ( !$mosConfig_username_change ) {
			$UNdisabled 	= 'disabled="disabled"';
			$UNdisabled_tip = $_LANG->_( 'REGTIP_CHANGE_USERNAME' );
		}

		if ( !$mosConfig_name_change ) {
			$Ndisabled 		= 'disabled="disabled"';
			$Ndisabled_tip 	= $_LANG->_( 'REGTIP_CHANGE_NAME' );
		}

		$txt 		= sprintf( $_LANG->_( 'VALID_AZ09' ), $_LANG->_( 'REGISTER_PASS' ), $mosConfig_password_length - 1 );
		$Pass_tip 	= $txt;

		$toolbar = mosToolBar_return::startTable();
		$toolbar .= mosToolBar_return::save( 'saveUserEdit' );
		$toolbar .= mosToolBar_return::cancel();
		$toolbar .= mosToolBar_return::endTable();

		$tmpl =& userScreens_front::createTemplate( 'edit.html' );

		$tmpl->addVar( 'body', 'params',		$params->render( 'params', 0 ) );

		$tmpl->addVar( 'body', 'length_name',	$list->length_name );
		$tmpl->addVar( 'body', 'length_pass',	$list->length_pass );
		$tmpl->addVar( 'body', 'length_nameX',	$list->length_name - 1 );
		$tmpl->addVar( 'body', 'length_passX',	$list->length_pass - 1  );
		$tmpl->addVar( 'body', 'show_params',	$list->show_params );

		$tmpl->addVar( 'body', 'toolbar',		$toolbar );
		$tmpl->addVar( 'body', 'tip_user',		$UNdisabled_tip );
		$tmpl->addVar( 'body', 'dis_user',		$UNdisabled );
		$tmpl->addVar( 'body', 'tip_name',		$Ndisabled_tip );
		$tmpl->addVar( 'body', 'dis_name',		$Ndisabled );
		$tmpl->addVar( 'body', 'tip_pass',		$Pass_tip );

		$tmpl->addObject( 'body', $row, 'row_' );

		$tmpl->addObject( 'body', $params->toObject(), 'p_' );

		$tmpl->displayParsedTemplate( 'body' );
	}

	function checkin( &$rows ) {
		global $mainframe;

		$params =& new mosParameters( '' );
		$params->def( 'back_button', $mainframe->getCfg( 'back_button' ) );

		$tmpl =& userScreens_front::createTemplate( 'checkin.html' );

		$tmpl->addObject( 'body', $params->toObject(), 'p_' );

		$tmpl->addObject( 'rows', $rows, 'row_' );

		$tmpl->displayParsedTemplate( 'body' );
	}
}
?>