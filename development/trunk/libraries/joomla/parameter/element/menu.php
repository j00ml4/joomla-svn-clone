<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * Renders a menu element
 *
 * @author 		Johan Janssens <johan@joomla.be>
 * @package 	Joomla.Framework
 * @subpackage 	Parameter
 * @since		1.1
 */

class JElement_Menu extends JElement
{
   /**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Menu';
	
	function fetchElement($name, $value, &$node, $control_name)	{
		global $database;

		$menuTypes = mosAdminMenus::menutypes();

		foreach ($menuTypes as $menutype) {
			$options[] = mosHTML::makeOption($menutype, $menutype);
		}
		array_unshift($options, mosHTML::makeOption('', '- '.JText::_('Select Menu').' -'));

		return mosHTML::selectList($options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value, $control_name.$name);
	}
}
?>