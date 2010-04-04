<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	com_comments
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');

/**
 * Function to build a JXtended Comments URL route.
 *
 * @param	array	The array of query string values for which to build a route.
 * @return	array	The URL route with segments represented as an array.
 * @since	1.2
 */
function CommentsBuildRoute(&$query)
{
	$segments = array();

	static $item;

	// Get the relevant menu items if not loaded.
	if (empty($item))
	{
		$menu	= &JSite::getMenu();
		$items	= $menu->getItems('component', 'com_comments');

		if (!empty($items)) {
			$item = $items[0];
		} else {
			$item = false;
		}
	}

	// Only allow Itemid if there is a com_comments item set in the menu.
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
 * Function to parse a JXtended Comments URL route.
 *
 * @param	array	The URL route with segments represented as an array.
 * @return	array	The array of variables to set in the request.
 * @since	1.2
 */
function CommentsParseRoute($segments)
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