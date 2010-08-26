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
		$options['countItems']	= true;
		$options['extension'] = 'com_projects';
		parent::__construct($options);
	}
	
	public function get($id = 'root', $forceload = false)
	{
		$item =& parent::get($id, $forceload);
		if(empty($item)){
			return false;
		}
		
		$item->numcategories = $this->getNumCategories($id);
		return $item; 
	}
	/*
	 * Method to get number of portfolios linked to a certain portfolio
	 * 
	 * @param $id ID of a portfolio
	 * @param $params Parameters
	 * @return Number of portfolios linked to the portfolio
	 */
	protected function getNumCategories($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		// Select required fields from the categories.
		$query->select('COUNT(nc.id)');
		$query->from('`#__categories` AS nc');
		$query->where('a.parent_id = '.(int) $id);
			
		// Filter by state
		if($this->_options['access'])
		{
			$user	= JFactory::getUser();
			$query->where('c.access IN ('.implode(',', $user->authorisedLevels()).')');
		}
		if($this->_options['published'] == 1)
		{
			$query->where('c.published = 1');
		}
		
		$db->setQuery($query);	
		return $db->loadResult();
	}
}