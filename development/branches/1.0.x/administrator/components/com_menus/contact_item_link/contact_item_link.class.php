<?php
/**
* @version $Id$
* @package Joomla
* @subpackage Menus
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software and parts of it may contain or be derived from works
* licensed under the GNU General Public License or other free or open source
* software licenses. See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* Contact item link class
* @package Joomla
* @subpackage Menus
*/
class contact_item_link_menu {

	function edit( &$uid, $menutype, $option ) {
		global $database, $my, $mainframe;

		$menu = new mosMenu( $database );
		$menu->load( $uid );

		// fail if checked out not by 'me'
		if ($menu->checked_out && $menu->checked_out <> $my->id) {
			echo "<script>alert('The module $menu->title is currently being edited by another administrator'); document.location.href='index2.php?option=$option'</script>\n";
			exit(0);
		}

		if ( $uid ) {
			$menu->checkout( $my->id );
		} else {
			// load values for new entry
			$menu->type 		= 'contact_item_link';
			$menu->menutype 	= $menutype;
			$menu->browserNav 	= 0;
			$menu->ordering 	= 9999;
			$menu->parent 		= intval( mosGetParam( $_POST, 'parent', 0 ) );
			$menu->published 	= 1;
		}

		if ( $uid ) {
			$temp = explode( 'contact_id=', $menu->link );
			$query = "SELECT *"
			. "\n FROM #__contact_details AS a"
			. "\n WHERE a.id = $temp[1]"
			;
			$database->setQuery( $query );
			$contact = $database->loadObjectlist();
			// outputs item name, category & section instead of the select list
			$lists['contact'] = '
			<table width="100%">
			<tr>
				<td width="10%">
				Name:
				</td>
				<td>
				'. $contact[0]->name .'
				</td>
			</tr>
			<tr>
				<td width="10%">
				Position:
				</td>
				<td>
				'. $contact[0]->con_position .'
				</td>
			</tr>
			</table>';
			$lists['contact'] .= '<input type="hidden" name="contact_item_link" value="'. $temp[1] .'" />';
			$contacts = '';
		} else {
			$query = "SELECT a.id AS value, CONCAT( a.name, ' - ',a.con_position ) AS text, a.catid "
			. "\n FROM #__contact_details AS a"
			. "\n INNER JOIN #__categories AS c ON a.catid = c.id"
			. "\n WHERE a.published = 1"
			. "\n ORDER BY a.catid, a.name"
			;
			$database->setQuery( $query );
			$contacts = $database->loadObjectList( );

			//	Create a list of links
			$lists['contact'] = mosHTML::selectList( $contacts, 'contact_item_link', 'class="inputbox" size="10"', 'value', 'text', '' );
		}

		// build html select list for target window
		$lists['target'] 		= mosAdminMenus::Target( $menu );

		// build the html select list for ordering
		$lists['ordering'] 		= mosAdminMenus::Ordering( $menu, $uid );
		// build the html select list for the group access
		$lists['access'] 		= mosAdminMenus::Access( $menu );
		// build the html select list for paraent item
		$lists['parent'] 		= mosAdminMenus::Parent( $menu );
		// build published button option
		$lists['published'] 	= mosAdminMenus::Published( $menu );
		// build the url link output
		$lists['link'] 		= mosAdminMenus::Link( $menu, $uid );

		// get params definitions
		$params = new mosParameters( $menu->params, $mainframe->getPath( 'menu_xml', $menu->type ), 'menu' );

		contact_item_link_menu_html::edit( $menu, $lists, $params, $option, $contacts );
	}
}
?>