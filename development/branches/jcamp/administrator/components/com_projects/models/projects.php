<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_project
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
jimport('joomla.application.categories');

/**
 * Portifolio gallery view to display all projects within a portifolio
 * @author eden & elf
 *
 */
class ProjectsModelProjects extends JModelList
{
	/**
	 * Category item data
	 *
	 * @var JCategory
	 */
	protected $portfolio = null;
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app	= &JFactory::getApplication();
		
		if($app->getName() == 'administrator'){
			// Load the parameters.
			$params = JComponentHelper::getParams('com_projects');
			$this->setState('params', $params);
			
			$accessId = $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', null, 'int');
			$this->setState('filter.access', $accessId);	
		}else{
			$params = $app->getParams();
        	$this->setState('params', $params);
        
			$this->setState('filter.access', !$params->get('show_noauth'));
		}
		
		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		$language = $app->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);
		
		// portfolio	
		$this->setState('portfolio.id', 
			$app->getUserStateFromRequest('portfolio.id', 'id'));
	
		parent::populateState('a.ordering');
	}
	
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		$user	= &JFactory::getUser();

		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select required fields from the categories.
		$query->select($this->getState('list.select', 'a.*'));
		$query->from('`#__projects` AS a');
		
		// Progress
		$query->select(' FLOOR((COUNT(DISTINCT ntf.id) / COUNT(DISTINCT nt.id)) * 100) AS progress');
		$query->join('LEFT', '#__project_tasks AS nt ON nt.project_id=a.id AND nt.state != 0');
		$query->join('LEFT', '#__project_tasks AS ntf ON ntf.project_id=a.id AND ntf.state=2');
		$query->group('a.id');

		// Filter by category.
		$value = $this->getState('portfolio.id');
		if ($value) {
			$query->where('a.catid = '.(int) $value);
		}

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		
		// Join over the categories.
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');
		
		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		
		// Filter by access level.
		$value = $this->getState('filter.access');
		if ($value) {
			$value = $user->authorisedLevels();
			$query->where('a.access IN ('. implode(',', $value) .')');
		}		
		
		// Filter by state
		$state = $this->getState('filter.state');
		if (is_numeric($state)) {
			$query->where('a.state = '.(int) $state);
		}
		// Filter by start and end dates.
		$nullDate = $db->Quote($db->getNullDate());
		$nowDate = $db->Quote(JFactory::getDate()->toMySQL());

		// Filter by language
		if ($this->getState('filter.language')) {
			$query->where('a.language IN (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'a.ordering')).
			' '.
			$db->getEscaped($this->getState('list.direction', 'ASC')));
		//die($query->__toString());
		return $query;
	}
	
	/**
	 * Method to get category data for the current category
	 *
	 * @param	int		An optional ID
	 *
	 * @return	object
	 * @since	1.5
	 */
	public function getPortfolio()
	{
		if(!is_object($this->portfolio))
		{
			$options = array();
			$options['countItems']	= true;
			$options['published']	= 1;
			$options['access']		= true;
			$categories = JCategories::getInstance('Projects', $options);
			$this->portfolio = $categories->get($this->getState('portfolio.id', 'root'));
		}

		return $this->portfolio;
	}
}
?>