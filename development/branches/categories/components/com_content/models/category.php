<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * This models supports retrieving a category, the articles associated with the category,
 * sibling, child and parent categories.
 *
 * @package		Joomla.Site
 * @subpackage	com_content
 * @since		1.5
 */
class ContentModelCategory extends JModelList
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

	protected $_parents = null;

	protected $_pagination = null;

	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context = 'com_content.article';

	/**
	 * Method to auto-populate the model state.
	 *
	 * @return	void
	 */
	protected function _populateState()
	{
		// Initialise variables.
		$app	= &JFactory::getApplication();

		$params	= $app->getParams();
		$this->setState('params', $params);

		// Load state from the request.
		$pk = JRequest::getInt('id');
		$this->setState('category.id', $pk);

		// limit to published
		$this->setState('filter.published', 1);

		// process show_noauth parameter
		if (!$params->get('show_noauth'))
		{
			$this->setState('filter.access', true);
		}
		else
		{
			$this->setState('filter.access', false);
		}

		// Optional filter text
		$this->setState('list.filter', JRequest::getString('filter-search'));

		// filter.order
		$itemid = JRequest::getInt('id', 0) . ':' . JRequest::getInt('Itemid', 0);
		$this->setState('list.ordering', $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.filter_order', 'filter_order', 'ordering',
			'string'));
		$this->setState('list.direction', $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.filter_order_Dir',
			'filter_order_Dir', 'ASC', 'cmd'));

		$this->setState('list.start', JRequest::getVar('limitstart', 0, '', 'int'));

		// set limit for query. If list, use parameter. If blog, add blog parameters for limit.
		if (JRequest::getString('layout') == 'blog')
		{
			$limit = $params->get('num_leading_articles') + $params->get('num_intro_articles') + $params->get('num_links');
			$this->setState('list.links', $params->get('num_links'));	
		}
		else
		{
			$limit = $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.limit', 'limit', $params->get('display_num'));
		}
		$this->setState('list.limit', $limit);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$context	A prefix for the store id.
	 * @return	string		A store id.
	 */
	protected function _getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.extension');
		$id	.= ':'.$this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.access');
		$id .= ':'.$this->getState('category.id');
		$id .= ':'.$this->getState('list.filter');
		$id .= ':'.$this->getState('list.ordering');
		$id .= ':'.$this->getState('list.direction');
		$id .= ':'.$this->getState('list.start');
		$id .= ':'.$this->getState('list.links');	
		$id .= ':'.$this->getState('list.limit');

		return parent::_getStoreId($id);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 * @since	1.6
	 */
	protected function _getListQuery()
	{
		$user	= &JFactory::getUser();
		$groups	= implode(',', $user->authorisedLevels());

		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select required fields from the categories.
		$query->select($this->getState('list.select', 'a.*'));
		$query->from('`#__content` AS a');
		$query->where('a.access IN ('.$groups.')');

		// Filter by category.
		if ($categoryId = $this->getState('category.id')) {
			$query->where('a.catid = '.(int) $categoryId);
			$query->join('LEFT', '#__categories AS c ON c.id = a.catid');
			$query->where('c.access IN ('.$groups.')');

			//Filter by published category
			$cpublished = $this->getState('filter.c.published');
			if (is_numeric($cpublished)) {
				$query->where('c.published = '.(int) $cpublished);
			}
		}

		// Filter by state
		$state = $this->getState('filter.state');
		if (is_numeric($state)) {
			$query->where('a.state = '.(int) $state);
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'a.ordering')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));
		return $query;
	}
	
	/**
	 * Method to get article data.
	 *
	 * @param	integer	The id of the category.
	 *
	 * @return	mixed	Menu item data object on success, false on failure.
	 */
	public function &getItems($pk = null)
	{
		// Invoke the parent getItems method to get the main list
		$items = &parent::getItems();

		// Convert the params field into an object, saving original in _params
		for ($i = 0, $n = count($items); $i < $n; $i++)
		{
			$item = &$items[$i];
			if (!isset($this->_params))
			{
				$item->_params	= $item->attribs;
				$item->params	= new JParameter($item->_params);
			}
		}

		return $items;
	}
	
	/**
	 * Method to get category data for the current category
	 *
	 * @param	int		An optional ID
	 *
	 * @return	object
	 * @since	1.5
	 */
	function getCategory($id = 0)
	{
		$id = (!empty($id)) ? $id : $this->getState('category.id');
		$options = array(
				'published' => $this->getState('filter.published', 1),
				'access' => $this->getState('filter.access', true)
				);
		$categories = JCategories::getInstance($this->getState('extension', 'com_content'), $options);
		$this->_category = $categories->get($id);
		return $this->_category;
	}

	/**
	 * Get the child categories.
	 *
	 * @param	int		An optional category id. If not supplied, the model state 'category.id' will be used.
	 *
	 * @return	mixed	An array of categories or false if an error occurs.
	 */
	function getChildren($categoryId = 0)
	{
		// Initialise variables.
		$categoryId = (!empty($categoryId)) ? $categoryId : $this->getState('category.id');

		$categories = JCategories::getInstance($this->getState('extension', 'com_content'));
		$this->_children = $categories->get($categoryId)->getChildren();

		return $this->_children;
	}

	/**
	 * Get the child categories.
	 *
	 * @param	int		An optional category id. If not supplied, the model state 'category.id' will be used.
	 *
	 * @return	mixed	An array of categories or false if an error occurs.
	 */
	function getParent($categoryId = 0)
	{
		// Initialise variables.
		$categoryId = (!empty($categoryId)) ? $categoryId : $this->getState('category.id');

		$categories = JCategories::getInstance($this->getState('extension', 'com_content'));
		$this->_parent = $categories->get($categoryId)->getParent();
		return $this->_parent;
	}
}
