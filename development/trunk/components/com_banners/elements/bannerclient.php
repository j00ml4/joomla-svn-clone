<?php
/**
* @version $Id: category.php 3222 2006-04-24 01:49:01Z webImagery $
* @package Joomla
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * Renders a category element
 *
 * @author 		Andrew Eddie
 * @package 	Joomla
 * @subpackage 	Banners
 * @since		1.5
 */

class JElement_BannerClient extends JElement
{
   /**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'BannerClient';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = &JFactory::getDBO();

		// This might get a conflict with the dynamic translation - TODO: search for better solution
		$query = 'SELECT cid, name' .
				' FROM #__bannerclient' .
				' ORDER BY name';
		$db->setQuery($query);
		$options = $db->loadObjectList();
		array_unshift($options, mosHTML::makeOption('0', '- '.JText::_('Select Client').' -', 'cid', 'name'));

		return mosHTML::selectList($options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'cid', 'name', $value, $control_name.$name );
	}
}
?>