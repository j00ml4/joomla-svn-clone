<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 */
class modMenuHelper
{
	/**
	 * Get a list of the menu items.
	 */
	static function getList(&$params)
	{
		// Initialise variables.
		$list		= array();
		$db			= JFactory::getDbo();
		$user		= JFactory::getUser();
		$menu		= JSite::getMenu();
		$default	= $menu->getDefault();

		// If no active menu, use default
		$active = ($menu->getActive()) ? $menu->getActive() : $menu->getDefault();

		$path		= $active->tree;
		$rlu		= array();
		$start		= (int) $params->get('startLevel');
		$end		= (int) $params->get('endLevel');
		$showAll	= $params->get('showAllChildren');
		$maxdepth	= $params->get('maxdepth');
		$items 		= $menu->getItems('menutype', $params->get('menutype'));
		$lastitem	= 0;
		foreach($items as $i => $item)
		{
			$rlu[$item->id]		= $i;
			if(($start && $start > $item->level) 
			|| ($end && $item->level > $end) 
			|| (!$showAll && $item->level > 1 && !in_array($item->parent_id, $path))
			|| ($maxdepth && $item->level > $maxdepth))
			{
				unset($items[$i]);
				continue;
			}
			$item->deeper = false;
			$item->shallower = false;
			$item->level_diff = 0;
			
			if(isset($items[$lastitem]) && count($items[$lastitem]))
			{
				$items[$lastitem]->deeper		= ($item->level > $items[$lastitem]->level);
				$items[$lastitem]->shallower	= ($item->level < $items[$lastitem]->level);
				$items[$lastitem]->level_diff	= ($items[$lastitem]->level - $item->level);
			}
			$item->active		= false;
			$item->params		= new JObject(json_decode($item->params));
			$lastitem			= $i;
			switch ($item->type)
			{
				case 'separator':
					// No further action needed.
					continue;

				case 'url':
					if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false)) {
						// If this is an internal Joomla link, ensure the Itemid is set.
						$item->link = $tmp->link.'&amp;Itemid='.$item->id;
					}
					break;

				case 'alias':
					// If this is an alias use the item id stored in the parameters to make the link.
					$item->link = 'index.php?Itemid='.$item->params->get('aliasoptions');
					break;

				default:
					$router = JSite::getRouter();
					if ($router->getMode() == JROUTER_MODE_SEF) {
						$item->link = 'index.php?Itemid='.$item->id;
					} else {
						$item->link .= '&Itemid='.$item->id;
					}
					break;
			}
			if ($item->home == 1)
			{
				// Correct the URL for the home page.
				$item->link = JURI::base();
			} elseif (strcasecmp(substr($item->link, 0, 4), 'http') && (strpos($item->link, 'index.php?') !== false)) {
				// This is an internal Joomla web site link.
				$item->link = JRoute::_($item->link, true, $item->params->get('secure'));
			} else {
				// Correct the & in the link.
				$item->link = str_replace('&', '&amp;', $item->link);
			}
			
		}
		return $items;
	}
}
