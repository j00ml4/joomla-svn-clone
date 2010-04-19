<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_articles_archive
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class modArchiveHelper
{
	function getList(&$params)
	{
		//get database
		$db		= JFactory::getDbo();
		
		// get language
		$language = JSite::getLanguage();
		$query	= $db->getQuery(true);
		$query->select('MONTH(a.created) AS created_month, a.created, a.id, a.title, YEAR(a.created) AS created_year, a.language');
		$query->from('#__content as a');
		$query->where('a.state = -1 AND a.checked_out = 0');
		$query->group('created_year DESC, created_month DESC');

		// Filter by language
		$query->where('(a.language='.$db->Quote($language).' OR a.language='.$db->Quote('').')');

		// Join over the categories.
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Filter by inherited language
		$query->join('LEFT','#__categories as p on p.lft <= c.lft AND p.rgt >=c.rgt AND p.language!=\'\'');
		$query->select('MIN(CONCAT(LPAD(p.rgt,30," "),p.language)) as inherited_language');
		$query->group('a.id');
		$query->having('(a.language='.$db->Quote($language) . (JFactory::getApplication()->getCfg('show_untagged_content') ? ' OR inherited_language IS NULL':'') . ' OR substr(inherited_language,31)='.$db->Quote($language).')');

		$db->setQuery($query, 0, intval($params->get('count')));
		$rows = $db->loadObjectList();

		$menu = &JSite::getMenu();
		$item = $menu->getItems('link', 'index.php?option=com_content&view=archive', true);
		$itemid = isset($item) ? '&Itemid='.$item->id : '';

		$i		= 0;
		$lists	= array();
		foreach ($rows as $row) {
			$date = &JFactory::getDate($row->created);

			$created_month	= $date->toFormat("%m");
			$month_name		= $date->toFormat("%B");
			$created_year	= $date->toFormat("%Y");

			$lists[$i]->link	= JRoute::_('index.php?option=com_content&view=archive&year='.$created_year.'&month='.$created_month.$itemid);
			$lists[$i]->text	= $month_name.', '.$created_year;
			$i++;
		}
		return $lists;
	}
}
