<?php
/**
 * @version		$Id$
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

 /* Weblinks Component Route Helper
 *
 * @package		Joomla.Site
 * @subpackage	com_weblinks
 * @since 1.6
 */

defined('_JEXEC') or die;

jimport('joomla.application.categories');

/**
 * Build the route for the com_weblinks component
 *
 * @param	array	An array of URL arguments
 *
 * @return	array	The URL arguments to use to assemble the subsequent URL.
 */
function WeblinksBuildRoute(&$query)
{
	$segments = array();

	// get a menu item based on Itemid or currently active
	$menu = &JSite::getMenu();
	$params = JComponentHelper::getParams('com_weblinks');
	$advanced = $params->get('sef_advanced_link', 0);
	
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
		unset($query['view']);
		if (empty($query['Itemid'])) {
			$segments[] = $query['view'];
			if(isset($query['id']))
			{
				$segments[] = $query['id'];
				unset($query['id']);
			}
			if (isset($query['layout']) && $query['layout'] == 'default') {
				unset($query['layout']);
			}
			return $segments;
		}
	}

	// are we dealing with an weblink that is attached to a menu item?
	if (($mView == 'category' || $mView == 'weblink') and (isset($query['id'])) and ($mId == intval($query['id']))) {
		unset($query['view']);
		unset($query['catid']);
		unset($query['id']);
		return $segments;
	}

	if (isset($view) and ($view == 'category' or $view == 'weblink')) {
		if($view == 'weblink' && isset($query['catid']))
		{
			$catid = $query['catid'];
			$menuCatid = $mCatid;
			$wid = $query['id'];
		} elseif(isset($query['id'])) {
			$catid = $query['id'];
			$menuCatid = $mId;
			$wid = false;
		}
		$categories = JCategories::getInstance('com_weblinks');
		$category = $categories->get($catid);
		$path = $category->getPath();
		$path[] = $category->id.':'.$category->alias;
		$path = array_reverse($path);
		
		$array = array();
		foreach($path as $id)
		{
			if((int) $id == (int)$menuCatid)
			{
				break;
			}
			if($advanced)
			{
				list($tmp, $id) = explode(':', $id, 2);
			}
			$array[] = $id;
		}
		$segments = array_merge($segments, array_reverse($array));
		if($view == 'weblink')
		{
			if($advanced)
			{
				list($tmp, $wid) = explode(':', $wid, 2);
			}
			$segments[] = $wid;
		}
		unset($query['id']);
		unset($query['catid']);
	}

	if (isset($query['layout']) 
	&& !empty($query['Itemid']) 
	&& isset($menuItem->query['layout']) 
	&& $query['layout'] == $menuItem->query['layout'])
	{
		unset($query['layout']);
	}

	return $segments;
}
/**
 * Parse the segments of a URL.
 *
 * @param	array	The segments of the URL to parse.
 *
 * @return	array	The URL attributes to be used by the application.
 */
function WeblinksParseRoute($segments)
{
	$vars = array();

	//Get the active menu item.
	$menu = &JSite::getMenu();
	$item = &$menu->getActive();
	$params = JComponentHelper::getParams('com_weblinks');
	$advanced = $params->get('sef_advanced_link', 0);

	// Count route segments
	$count = count($segments);

	// Standard routing for weblinks.
	if (!isset($item))
	{
		$vars['view']	= $segments[0];
		$vars['id']		= $segments[$count - 1];
		return $vars;
	}

	// From the categories view, we can only jump to a category.
	$id = (isset($item->query['id']) && $item->query['id'] > 1) ? $item->query['id'] : 'root';
	$category = JCategories::getInstance('com_weblinks')->get($id);
			
	$categories = $category->getChildren();
	$found = 0;
	foreach($segments as $segment)
	{
		foreach($categories as $category)
		{
			if (($category->slug == $segment) || ($advanced && $category->alias == str_replace(':', '-',$segment)))
			{
				$vars['id'] = $category->id;
				$vars['view'] = 'category';
				$categories = $category->getChildren();
				$found = 1;
				break;
			}
		}
		if ($found == 0)
		{
			if($advanced)
			{
				$db = JFactory::getDBO();
				$query = 'SELECT id FROM #__weblinks WHERE catid = '.$vars['id'].' AND alias = '.$db->Quote(str_replace(':', '-',$segment));
				$db->setQuery($query);
				$id = $db->loadResult();
			} else {
				$id = $segment;
			}
			$vars['id'] = $id;
			$vars['view'] = 'weblink';
			break;
		}
		$found = 0;
	}

	return $vars;
}
