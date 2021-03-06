<?php
/**
 * @version		$Id:
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * This models supports retrieving lists of newsfeed categories.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_newsfeeds
 * @since		1.6
 */
class NewsfeedsModelCategories extends JModelList
{
	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	public $_context = 'com_newsfeeds.categories';

	/**
	 * The category context (allows other extensions to derived from this model).
	 *
	 * @var		string
	 */
	protected $_extension = 'com_newsfeeds';

	/**
	 * Method to auto-populate the model state.
	 *
	 * @since	1.6
	 */

	protected function _populateState()
	{
		$app = &JFactory::getApplication();
		$this->setState('filter.extension', $this->_extension);

		// Get the parent id if defined.
		$parentId = JRequest::getInt('id');
		$this->setState('filter.parentId', $parentId);

		// List state information
		//$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
		$limit = JRequest::getInt('limit', $app->getCfg('list_limit', 0));
		$this->setState('list.limit', $limit);

		//$limitstart = $app->getUserStateFromRequest($this->_context.'.limitstart', 'limitstart', 0);
		$limitstart = JRequest::getInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		//$orderCol = $app->getUserStateFromRequest($this->_context.'.ordercol', 'filter_order', 'a.lft');
		$orderCol = JRequest::getCmd('filter_order', 'a.lft');
		$this->setState('list.ordering', $orderCol);

		//$orderDirn = $app->getUserStateFromRequest($this->_context.'.orderdirn', 'filter_order_Dir', 'asc');
		$orderDirn = JRequest::getWord('filter_order_Dir', 'asc');
		$this->setState('list.direction', $orderDirn);

		$params = $app->getParams();
		$this->setState('params', $params);

		$this->setState('filter.published',	1);
		$this->setState('filter.access',	true);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 */
	protected function _getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.extension');
		$id	.= ':'.$this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.access');

		return parent::_getStoreId($id);
	}

	/**
	 * @param	boolean	True to join selected foreign information
	 *
	 * @return	string
	 */
	function _getListQuery($resolveFKs = true)
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.title, a.alias, a.access, a.published' .
				', a.path AS route, a.parent_id, a.level, a.lft, a.rgt' .
				', a.description'
			)
		);
		$query->from('#__categories AS a');

		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$user	= &JFactory::getUser();
			$groups	= implode(',', $user->authorisedLevels());
			$query->where('a.access IN ('.$groups.')');
		}

		// Filter by published state.
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = ' . (int) $published);
		} else if (is_array($published)) {
			JArrayHelper::toInteger($published);
			$published = implode(',', $published);
			$query->where('a.published IN ('.$published.')');
		}

		// Filter by extension.
		$query->where('a.extension = '.$db->quote($this->_extension));

		// Retrieve a sub tree or lineage.
		if ($parentId = $this->getState('filter.parent_id')) {
			if ($levels = $this->getState('filter.get_children')) {
				// Optionally get all the child categories for given parent.
				$query->leftJoin('#__categories AS p ON p.id = '.(int) $parentId);
				$query->where('a.lft > p.lft AND a.rgt < p.rgt');
				if ((int) $levels > 0) {
					// Only go to a certain depth.
					$query->where('a.level <= p.level + '.(int) $levels);
				}
			} else if ($this->getState('filter.get_parents')) {
				// Optionally get all the parents to the category.
				$query->leftJoin('#__categories AS p ON p.id = '.(int) $parentId);
				$query->where('a.lft < p.lft AND a.rgt > p.rgt');
			} else {
				// Only looking for categories with this parent.
				$query->where('a.parent_id = '.(int) $parentId);
			}
		}

		// Inclusive/exclusive filters (-ve id's are to be excluded).
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId)) {
			if ($categoryId > 0) {
				$query->where('a.id = ' . (int) $categoryId);
			} else {
				$query->where('a.id <> ' . -(int) $categoryId);
			}
		} else if (is_array($categoryId)) {
			JArrayHelper::toInteger($categoryId);
			// Find the include/excludes
			$include = array();
			$exclude = array();
			foreach ($categoryId as $id) {
				if ($id > 0) {
					$include[] = $id;
				} else {
					$exclude[] = $id;
				}
			}

			if (!empty($include)) {
				$include = implode(',', $include);
				$query->where('a.id IN ('.$include.')');
			} else {
				$include = implode(',', $include);
				$query->where('a.id NOT IN ('.$include.')');
			}
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'a.lft')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}
	
	/**
	 * redefine the function an add some properties to make the styling more easy
	 *
	 * @return mixed An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		if (!empty($items))
		{
			$itemcount = count($items);
			for ($i=0;$i<$itemcount;$i++)
			{
				$item = &$items[$i];
				$item->sclass		= ($i == 0) ? 'first' : '';
				$item->sclass		= ($i == ($itemcount - 1)) ? 'last' : $item->sclass;
 				$item->deeper		= (isset($items[$i+1]) && ($item->level < $items[$i+1]->level));
				$item->shallower	= (isset($items[$i+1]) && ($item->level > $items[$i+1]->level));
				$item->level_diff	= (isset($items[$i+1])) ? ($item->level - $items[$i+1]->level) : 0;
			}
		}
		return $items;
	}
	
}
