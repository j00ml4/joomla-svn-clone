<?php
/**
 * @version		$Id: category.php 14276 2010-01-18 14:20:28Z louis $
 * @package		Joomla
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Component Helper
jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * Projects Component Category Tree
 *
 * @static
 * @package		Joomla
 * @subpackage	com_projects
 * @since 1.6
 */
class ProjectsCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__projects';
		$options['extension'] = 'com_projects';
		parent::__construct($options);
	}
	
	/*
	 * Method to get number of portfolios linked to a certain portfolio
	 * 
	 * @param $id ID of a portfolio
	 * @param $params Parameters
	 * @return Number of portfolios linked to the portfolio
	 */
	public function calcNumCategories($id, $params) {
		$user	= JFactory::getUser();
		$db		= JFactory::getDbo();
		$q		= $db->getQuery(true);
		
		
		// Select required fields from the categories.
		$q->select('a.*');
		$q->from('`#__categories` AS a');
		$q->where('a.extension = '.$db->Quote('com_projects'));
		
		$q->where('a.parent_id = '.(int) $id);
			
		// Filter by state
		$state = $params['filter.state'];
		if (is_numeric($state)) {
			$q->where('a.state = '.(int)$state);
		}
		
		// Filter by start and end dates.
		$nullDate = $db->Quote($db->getNullDate());
		$nowDate = $db->Quote(JFactory::getDate()->toMySQL());

		// Filter by language
		if ($params['filter.language']) {
			$q->where('a.`language` IN (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
		}
		
		$db->setQuery($q);
		$db->query();
		
		return $db->getNumRows();
	}
}