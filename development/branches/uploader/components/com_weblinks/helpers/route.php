<?php
/**
 * @version		$Id: route.php 12182 2009-06-19 23:59:03Z eddieajau $
 * @package		Joomla.Site
 * @subpackage	Weblinks
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Component Helper
jimport('joomla.application.component.helper');

/**
 * Weblinks Component Route Helper
 *
 * @static
 * @package		Joomla.Site
 * @subpackage	Weblinks
 * @since 1.5
 */
class WeblinksHelperRoute
{
	function getWeblinkRoute($id, $catid) {
		$needles = array(
			'category' => (int) $catid,
			'categories' => null
		);

		//Find the itemid
		$itemid = WeblinksHelperRoute::_findItem($needles);
		$itemid = $itemid ? '&Itemid='.$itemid : '';

		//Create the link
		$link = 'index.php?option=com_weblinks&view=weblink&id='. $id . '&catid='.$catid . $itemid;

		return $link;
	}

	function _findItem($needles)
	{
		static $items;

		if (!$items)
		{
			$component = &JComponentHelper::getComponent('com_weblinks');
			$menu = &JSite::getMenu();
			$items = $menu->getItems('component_id', $component->id);
		}

		if (!is_array($items)) {
			return null;
		}

		$match = null;
		foreach($needles as $needle => $id)
		{
			foreach($items as $item)
			{
				if ((@$item->query['view'] == $needle) && (@$item->query['id'] == $id)) {
					$match = $item->id;
					break;
				}
			}

			if (isset($match)) {
				break;
			}
		}

		return $match;
	}
}
?>
