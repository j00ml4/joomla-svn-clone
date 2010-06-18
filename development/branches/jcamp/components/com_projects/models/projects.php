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
	 * Category items data
	 *
	 * @var array
	 */
	protected $_item = null;

	protected $_articles = null;

	protected $_siblings = null;

	protected $_children = null;

	protected $_parent = null;

	/**
	 * The category that applies.
	 *
	 * @access	protected
	 * @var		object
	 */
	protected $_category = null;

	/**
	 * The list of other newfeed categories.
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $_categories = null;
	
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
				
		// Initialise variables.
		$app	= &JFactory::getApplication();
		
		// Category		
		$id = JRequest::getInt('id', 0);
		$this->setState('portfolio.id', $id);
		$app->setUserState('portfolio.id', $id);
		
		//$this->setState('filter.published',	1);
		//$this->setState('filter.language',$app->getLanguageFilter());
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
	 * Method to get category data for the current category
	 *
	 * @param	int		An optional ID
	 *
	 * @return	object
	 * @since	1.5
	 */
	public function getCategory()
	{
		if(!is_object($this->_item))
		{
			$app = JFactory::getApplication();
			$menu = $app->getMenu();
			$active = $menu->getActive();
			$params = new JRegistry();
			$params->loadJSON($active->params);
			$options = array();
			//$options['countItems'] = $params->get('show_ntacts', 0);
			$categories = JCategories::getInstance('Projects', $options);
			$this->_item = $categories->get($this->getState('portfolio.id', 'root'));
			if(is_object($this->_item))
			{
				$this->_children = $this->_item->getChildren();
				$this->_parent = false;
				if($this->_item->getParent())
				{
					$this->_parent = $this->_item->getParent();
				}
				$this->_rightsibling = $this->_item->getSibling();
				$this->_leftsibling = $this->_item->getSibling(false);
			} else {
				$this->_children = false;
				$this->_parent = false;
			}
		}

		return $this->_item;
	}

	/**
	 * Get the parent categorie.
	 *
	 * @param	int		An optional category id. If not supplied, the model state 'portfolio.id' will be used.
	 *
	 * @return	mixed	An array of categories or false if an error occurs.
	 */
	public function getParent()
	{
		if(!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_parent;
	}

	/**
	 * Get the sibling (adjacent) categories.
	 *
	 * @return	mixed	An array of categories or false if an error occurs.
	 */
	function &getLeftSibling()
	{
		if(!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_leftsibling;
	}

	function &getRightSibling()
	{
		if(!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_rightsibling;
	}

	/**
	 * Get the child categories.
	 *
	 * @param	int		An optional category id. If not supplied, the model state 'portfolio.id' will be used.
	 *
	 * @return	mixed	An array of categories or false if an error occurs.
	 */
	function &getChildren()
	{
		if(!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_children;
	}
}
?>