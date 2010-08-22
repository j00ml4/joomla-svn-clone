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
	protected $_portfolio = null;
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		parent::populateState();
		$app	= &JFactory::getApplication();
		
		// portfolio	
		$this->setState('portfolio.id', 
			$app->getUserStateFromRequest('portfolio.id', 'id'));
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
		// (using 'a.*' is slower than fetching only columns we need (and this is clearer for us to know what date we fetch from db))
		$query->select($this->getState('list.select', 'p.*'));
		$query->from('`#__projects` AS p');

		// Filter by category.
		if ($categoryId = $this->getState('portfolio.id')) {
			$query->where('p.catid = '.(int) $categoryId);
			$query->join('LEFT', '#__categories AS c ON c.id = p.catid');
		}

		// Filter by state
		$state = $this->getState('filter.state');
		if (is_numeric($state)) {
			$query->where('p.state = '.(int) $state);
		}
		// Filter by start and end dates.
		$nullDate = $db->Quote($db->getNullDate());
		$nowDate = $db->Quote(JFactory::getDate()->toMySQL());

		// Filter by language
		if ($this->getState('filter.language')) {
			$query->where('p.`language` IN (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'p.`ordering`')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));

		return $query;
	}
	
	/**
	 * Method to get portfolio data for the current projects
	 *
	 * @param	int		An optional ID
	 *
	 * @return	object
	 * @since	1.5
	 */
	public function getPortfolio()
	{
		if(!is_object($this->_portfolio))
		{
			$options = array();
			$options['countItems']	= true;
			$options['published']	= 1;
			$options['access']		= true;
			$categories = JCategories::getInstance('Projects', $options);
			$this->_portfolio = $categories->get($this->getState('portfolio.id', 'root'));
			$this->_portfolio->numprojects = $this->getTotal();
		}

		return $this->_portfolio;
	}
}
?>