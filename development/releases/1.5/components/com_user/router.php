<?php
/**
* @version		$Id$
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

function UserBuildRoute(&$query)
{
	$segments = array();

	if(isset($query['view']))
	{
		if(empty($query['Itemid'])) {
			$segments[] = $query['view'];
		} else {
			$menu = &JSite::getMenu();
			$menuItem = &$menu->getItem( $query['Itemid'] );
			if (isset($menuItem->query['view'])) {
				if($query['view'] != $menuItem->query['view']) {
					$segments[] = $query['view'];	
				}
			} else {
				$segments[] = $query['view'];
			}
		}

		unset($query['view']);
	};

	return $segments;
}

function UserParseRoute($segments)
{
	$vars = array();

	//Get the active menu item
	$menu =& JSite::getMenu();
	$item =& $menu->getActive();

	// Count route segments
	$count = count($segments);

	if (isset( $segments[0] )) {
		$vars['view'] = $segments[0];
	}

	//Standard routing for articles
	if(!isset($item))
	{
		$vars['id']    = $segments[$count - 1];
		return $vars;
	}

	return $vars;
}
?>