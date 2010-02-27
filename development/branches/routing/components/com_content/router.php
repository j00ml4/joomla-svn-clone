<?php
/**
 * @version		$Id$
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Build the route for the com_content component
 *
 * @param	array	An array of URL arguments
 *
 * @return	array	The URL arguments to use to assemble the subsequent URL.
 */
function ContentBuildRoute(&$query)
{
	$segments = array();

	// get a menu item based on Itemid or currently active
	$menu = &JSite::getMenu();

	if (empty($query['Itemid'])) {
		$menuItem = &$menu->getActive();
	}
	else {
		$menuItem = &$menu->getItem($query['Itemid']);
	}
	$mView	= (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
	$mCatid	= (empty($menuItem->query['catid'])) ? null : $menuItem->query['catid'];
	$mId	= (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];

	if (isset($query['view']))
	{
		$view = $query['view'];
		if (empty($query['Itemid'])) {
			$segments[] = $query['view'];
		}
		unset($query['view']);
	};

	// are we dealing with an article that is attached to a menu item?
	if (($mView == 'article') and (isset($query['id'])) and ($mId == intval($query['id']))) {
		unset($query['view']);
		unset($query['catid']);
		unset($query['id']);
	}

	if (isset($view) and $view == 'category') {
		if ($mId != intval($query['id']) || $mView != $view) {
			$categories = JCategories::getInstance('com_content');
			$category = $categories->get($query['id']);
			$path = $category->getPath();
			$path[] = $category->id.':'.$category->alias;
			$path = array_reverse($path);
			
			$array = array();
			foreach($path as $id)
			{
				if((int) $id == (int)$mId)
				{
					break;
				}
				$array[] = $id;
			}
			$segments = array_merge($segments, array_reverse($array));
			unset($query['id']);
		}
	}

	if (isset($query['catid'])) {
		// if we are routing an article or category where the category id matches the menu catid, don't include the category segment
		if ((isset($view) && ($view == 'article') and ($mView != 'category') and ($mView != 'article') and ($mCatid != intval($query['catid'])))) {
			$segments[] = $query['catid'];
		}
		unset($query['catid']);
	};

	if (isset($query['id']))
	{
		if (empty($query['Itemid'])) {
			$segments[] = $query['id'];
		}
		else
		{
			if (isset($menuItem->query['id']))
			{
				if ($query['id'] != $mId) {
					$segments[] = $query['id'];
				}
			}
			else {
				$segments[] = $query['id'];
			}
		}
		unset($query['id']);
	};

	if (isset($query['year']))
	{
		if (!empty($query['Itemid'])) {
			$segments[] = $query['year'];
			unset($query['year']);
		}
	};

	if (isset($query['month']))
	{
		if (!empty($query['Itemid'])) {
			$segments[] = $query['month'];
			unset($query['month']);
		}
	};

	if (isset($query['layout']))
	{
		if (!empty($query['Itemid']) && isset($menuItem->query['layout']))
		{
			if ($query['layout'] == $menuItem->query['layout']) {

				unset($query['layout']);
			}
		}
		else
		{
			if ($query['layout'] == 'default') {
				unset($query['layout']);
			}
		}
	};

	return $segments;
}

/**
 * Parse the segments of a URL.
 *
 * @param	array	The segments of the URL to parse.
 *
 * @return	array	The URL attributes to be used by the application.
 */
function ContentParseRoute($segments)
{
	$vars = array();

	//Get the active menu item.
	$menu = &JSite::getMenu();
	$item = &$menu->getActive();

	// Count route segments
	$count = count($segments);

	// Standard routing for articles.
	if (!isset($item))
	{
		$vars['view']	= $segments[0];
		$vars['id']		= $segments[$count - 1];
		return $vars;
	}

	// Handle View and Identifier.
	switch ($item->query['view'])
	{
		case 'categories':
			// From the categories view, we can only jump to a category.

			if ($count > 1)
			{
				if (intval($segments[0]) && intval($segments[$count-1]))
				{
					// 123-path/to/category/456-article
					$vars['id']		= $segments[$count-1];
					$vars['view']	= 'article';
				}
				else
				{
					// 123-path/to/category
					$vars['id']		= $segments[0];
					$vars['view']	= 'category';
				}
			}
			else
			{
				// 123-category
				$vars['id']		= $segments[0];
				$vars['view']	= 'category';
			}
			break;

		case 'category':
			$vars['id']		= $segments[$count-1];
			$vars['view']	= 'article';
			break;

		case 'frontpage':
			$vars['id']		= $segments[$count-1];
			$vars['view']	= 'article';
			break;

		case 'article':
			$vars['id']		= $segments[$count-1];
			$vars['view']	= 'article';
			break;

		case 'archive':
			if ($count != 1)
			{
				$vars['year']	= $count >= 2 ? $segments[$count-2] : null;
				$vars['month'] = $segments[$count-1];
				$vars['view']	= 'archive';
			}
			else
			{
				$vars['id']		= $segments[$count-1];
				$vars['view'] = 'article';
			}
			break;
	}

	return $vars;
}
