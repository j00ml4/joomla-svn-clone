<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');

/**
 * Function to build a Social URL route.
 *
 * @param	array	The array of query string values for which to build a route.
 * @return	array	The URL route with segments represented as an array.
 * @since	1.6
 */
function SocialBuildRoute(&$query)
{
	$segments = array();

	static $item;

	// Get the relevant menu items if not loaded.
	if (empty($item))
	{
		$menu	= &JSite::getMenu();
		$items	= $menu->getItems('component', 'com_social');

		if (!empty($items)) {
			$item = $items[0];
		} else {
			$item = false;
		}
	}

	// Only allow Itemid if there is a com_social item set in the menu.
	if ($item) {
		$query['Itemid'] = $item->id;
	} else {
		unset($query['Itemid']);
	}

	// We have no need for a view.
	unset($query['view']);

	// Handle an unmatched item.
	if (isset($query['thread_id']))
	{
		// Add the view to the segments.
		if (isset($query['thread_id'])) {
			$segments[] = $query['thread_id'];
			unset($query['thread_id']);
		}
	}
	unset($query['view']);

	return $segments;
}

/**
 * Function to parse a Social URL route.
 *
 * @param	array	The URL route with segments represented as an array.
 * @return	array	The array of variables to set in the request.
 * @since	1.6
 */
function SocialParseRoute($segments)
{
	$vars = array();

	// There is only one view.
	$vars['view'] = 'comments';

	if (count($segments) > 1) {
		if (!empty($segments[-1])) {
			$vars['thread_id'] = $segments[-1];
		}

	} else {
		if (!empty($segments[0])) {
			$vars['thread_id'] = $segments[0];
		}
	}

	return $vars;
}