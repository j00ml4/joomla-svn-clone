<?php
/**
 * @version		$Id$
  * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
 defined('_JEXEC') or die;

jimport('joomla.application.categories');
 
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
	} else {
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
	if (isset($view) && ($mView == $view) and (isset($query['id'])) and ($mId == intval($query['id']))) {
		unset($query['view']);
		unset($query['catid']);
		unset($query['id']);
	}

	if (isset($view) and (($view == 'category' && isset($query['id'])) || ($view == 'article' && isset($query['catid'])))) {
		if($view == 'article')
		{
			$catid = $query['catid'];
			unset($query['catid']);
		} else {
			$catid = $query['id'];
			unset($query['id']);
		}
		$categories = JCategories::getInstance('com_content');
		$category = $categories->get($catid);
		if(!$category)
		{
			die('The category is not published or does not exist');
			//TODO Throw error that the category either not exists or is unpublished	
		}
		$path = array_reverse($category->getPath());
			
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
	}
	
	if(isset($view) && $view == 'article' && isset($query['id']))
	{
		$segments[] = $query['id'];
		unset($query['id']);
	}

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
	$params = JComponentHelper::getParams('com_content');
	$advanced = $params->get('sef_advanced_link', 0);

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
		case 'category':
		case 'featured':
			// From the categories view, we can only jump to a category.
			$id = (isset($item->query['id']) && $item->query['id'] > 1) ? $item->query['id'] : 'root';
			$category = JCategories::getInstance('com_content')->get($id);
			if(!$category)
			{
				die('The category is not published or does not exist');
				//TODO Throw error that the category either not exists or is unpublished	
			}
			$categories = $category->getChildren();
			$vars['catid'] = $id;
			$vars['id'] = $id;		
			$found = 0;
			foreach($segments as $segment)
			{
				$segment = $advanced ? str_replace(':', '-',$segment) : $segment;
				foreach($categories as $category)
				{
					if ($category->slug == $segment || $category->alias == $segment)
					{
						$vars['id'] = $category->id;
						$vars['catid'] = $category->id;
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
						$query = 'SELECT id FROM #__content WHERE catid = '.$vars['catid'].' AND alias = '.$db->Quote($segment);
						$db->setQuery($query);
						$cid = $db->loadResult();
					} else {
						$cid = $segment;
					}
					$vars['id'] = $cid;
					$vars['view'] = 'article';
				}
				$found = 0;
			}
			break;

		case 'featured':
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
