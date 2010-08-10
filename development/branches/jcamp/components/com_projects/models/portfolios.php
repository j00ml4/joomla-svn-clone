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
 * @author elf
 *
 */
class ProjectsModelPortfolios extends JModelList
{
	protected $_parent = null;
	protected $_items = null;
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		parent::populateState('a.lft');
				
		// Initialise variables.
		$app	= &JFactory::getApplication();
		
		// portfolio	
		$id = JRequest::getInt('id', 0);
		$this->setState('portfolio.id', $id);
		$app->setUserState('portfolio.id', $id);
		
		$this->setState('filter.access',	1);
		$this->setState('filter.published',	1);
		$this->setState('filter.language',$app->getLanguageFilter());
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
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		
		
		// Select required fields from the categories.
		$query->select($this->getState('list.select', 'a.*'));
		$query->from('`#__categories` AS a');
		$query->where('a.extension = '.$db->Quote('com_projects'));
		
		// Filter by parent
		$parent_id = $this->getState('portfolio.id');
		if(!$parent_id){
			$parent_id = 1;
			$query->where('a.level = 1');
		} 
		$query->where('a.parent_id = '.(int) $parent_id);
			
		// Filter by state
		$state = $this->getState('filter.state');
		if (is_numeric($state)) {
			$query->where('a.state = '.(int)$state);
		}
		
		// Filter by start and end dates.
		$nullDate = $db->Quote($db->getNullDate());
		$nowDate = $db->Quote(JFactory::getDate()->toMySQL());

		// Filter by language
		if ($this->getState('filter.language')) {
			$query->where('a.`language` IN (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'a.`lft`')).
			' '.$db->getEscaped($this->getState('list.direction', 'ASC')));

		return $query;
	}
	
	/**
	 * Get Parrent
	 * Enter description here ...
	 */
	public function getParent()
	{	
		$parent_id = $this->getState('portfolio.id');
		if(!$parent_id){
			return null;
		}
		$categories = JCategories::getInstance('Projects');
		return $categories->get($parent_id);
	}
}
