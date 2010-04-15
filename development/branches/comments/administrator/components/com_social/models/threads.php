<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Social model for the Social package.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_social
 * @since		1.6
 */
class SocialModelThreads extends JModelList
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string		An SQL query
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('`#__social_threads` AS a');

		// Filter the items over the context if set.
		if ($context = $this->getState('filter.context')) {
			$query->where('a.context = '.$db->Quote($context));
		}

		// Filter by search string.
		if ($search = $this->getState('filter.search')) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('a.page_title LIKE '.$search);
			}
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'a.id').' '.$this->getState('list.direction', 'asc')));

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
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
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.context');
		$id	.= ':'.$this->getState('filter.thread_id');

		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 */
	protected function populateState()
	{
		$app		= JFactory::getApplication('administrator');
		$context	= 'com_social.threads';

		// Load the filter state.
		$search = $app->getUserStateFromRequest($context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$context = $app->getUserStateFromRequest($context.'.filter.context', 'filter_context', '', 'word');
		$this->setState('filter.context', $context);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_social');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'asc');
	}


	/**
	 * Get a list of comments.
	 *
	 * @return	mixed	An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		// Get the list of comments.
		$items = parent::getItems();

		// Check for an error.
		if ($items === false) {
			return false;
		}

		// Check for empty array.
		if (empty($items)) {
			return true;
		}

		// Add count information only to the list items returned to save on performance.
		$ids	= JArrayHelper::getColumn($items, 'id');
		JArrayHelper::toInteger($ids);
		$ids	= '('.implode(',', $ids).')';
		$db		= $this->getDbo();

		// Join on the comments table.
		$query	= $db->getQuery(true);
		$query->select('thread_id, COUNT(id) AS comment_count');
		$query->from('#__social_comments');
		$query->where('thread_id IN '.$ids);
		$query->group('thread_id');
		$db->setQuery($query);
		$comments = $db->loadAssocList('thread_id', 'comment_count');

		// Join on the ratings table.
		$query->clear('select')->select('thread_id, pscore_count');
		$query->clear('from')->from('#__social_ratings');
		$db->setQuery($query);
		$ratings = $db->loadAssocList('thread_id', 'pscore_count');

		// Graft the data back into the list.
		foreach ($items as $item) {
			$item->comment_count	= isset($comments[$item->id]) ? $comments[$item->id] : 0;
			$item->pscore_count		= isset($ratings[$item->id]) ? $ratings[$item->id] : 0;
		}

		return $items;
	}
}
