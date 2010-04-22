<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Social model for the Social package.
 *
 * @package		Joomla.Site
 * @subpackage	com_social
 * @since		1.6
 */
class JComments extends JModelList
{
	protected $option = '-';
	protected $name = '-';

	/**
	 * Method to get a JQuery object for retrieving the data set from a database.
	 *
	 * @return	object	A JQuery object to retrieve the data set.
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select all fields from the table.
		$query->select('a.*');
		$query->from('`#__social_comments` AS a');

		// If the model is set to check item state, add to the query.
		if ($this->getState('check.state', true)) {
			$query->where('a.published = ' . (int)$this->getState('filter.state'));
		}

		// If the model is set to check item trackback state, add to the query.
		if ($this->getState('check.trackback', false)) {
			$query->where('a.trackback = ' . (int)$this->getState('filter.trackback'));
		}

		// Filter the items over the thread id if set.
		$thread_id = $this->getState('filter.thread_id');
		if ($thread_id !== null && $thread_id > 0) {
			$query->where('a.thread_id = '.(int)$thread_id);
		}

		// Filter the items over the context if set.
		$context = $this->getState('filter.context');
		if (!empty($context)) {
			$query->where('b.context = '.$db->Quote($context));
		}

		// Filter the items over the context id if set.
		$context_id = $this->getState('filter.context_id');
		if (!empty($context_id)) {
			$query->where('b.context_id = '.$db->Quote($context_id));
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering')));

		// Resolve foriegn keys.
		$query->select('b.page_route, b.page_url, b.page_title');
		$query->join('LEFT', '`#__social_threads` AS b ON b.id=a.thread_id');

		// Get logged in member information if it exists.
		$query->select('c.name AS user_name, c.username AS user_login_name');
		$query->join('LEFT', '`#__users` AS c ON c.id=a.user_id');

		//echo nl2br(str_replace('#__','jos_',$query)).'<hr/>';
		return $query;
	}

	/**
	 * Method to get a JPagination object for the data set.
	 *
	 * @param	boolean	Force create a new object.
	 * @return	object	A JPagination object for the data set.
	 * @since	1.6
	 */
	public function getPagination($new = false)
	{
		// Get a storage key.
		$store = $this->getStoreId('getPagination');

		// Try to load the data from internal storage.
		if (!empty($this->_cache[$store]) && !$new) {
			return $this->_cache[$store];
		}

		// Create the pagination object.
		jimport('joomla.html.pagination');
		$page = new JPagination($this->getTotal(), $this->getState('list.start'), $this->getState('list.limit'));
		//$page->setRequestVariable('comments_page', 1);

		// Add the object to the internal cache.
		$this->_cache[$store] = $page;

		return $this->_cache[$store];
	}

	/**
	 * Method to get a store id based on model the configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string	An identifier string to generate the store id.
	 * @return	string	A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Add the filter state.
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.context');
		$id	.= ':'.$this->getState('filter.thread_id');
		$id	.= ':'.$this->getState('filter.trackback');

		// Add the check state.
		$id	.= ':'.$this->getState('check.state');
		$id	.= ':'.$this->getState('check.trackback');

		// The parent class adds the list state.
		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		// Initialize variables.
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$config		= JFactory::getConfig();
		$params		= $app->getParams('com_social');

		// Load the filter state.
		$this->setState('filter.context', JRequest::getWord('context', $params->get('context', null)));
		$this->setState('filter.context_id', JRequest::getInt('context_id', $params->get('context_id', null)));
		$this->setState('filter.thread_id',	JRequest::getInt('thread_id', $params->get('thread_id', null)));
		$this->setState('filter.state', 1);
		$this->setState('filter.trackback', 0);

		// If the limit is set to -1, use the global config list_limit value.
		$limit	= JRequest::getInt('limit', $params->get('list_limit', 0));
		$limit	= ($limit === -1) ? $app->getCfg('list_limit', 20) : $limit;

		// Compute the list start offset.
		$page	= $app->getUserStateFromRequest($this->context.'list.page', 'comments_page', 0, 'int');
		$start	= intval(($page) ? (($page - 1) * $params->get('pagination', 0)) : 0);

		// Load the list state.
		$this->setState('list.start', $start);
		$this->setState('list.limit', $limit);

		// Load the list ordering.
		switch (strtolower($params->get('list_order'))) {
			case 'asc':
				$this->setState('list.ordering', 'a.created_time ASC');
				break;
			case 'desc':
				$this->setState('list.ordering', 'a.created_time DESC');
				break;
			case 'random':
				$this->setState('list.ordering', 'RAND()');
				break;
			default:
				$this->setState('list.ordering', 'a.created_time DESC');
				break;
		}

		// Load the user parameters.
		$this->setState('user',	$user);
		$this->setState('user.id', (int)$user->id);
		$this->setState('user.aid', (int)$user->get('aid'));

		// Load the check parameters.
		$this->setState('check.state', true);
		$this->setState('check.trackback', false);

		// Load the parameters.
		$this->setState('params', $params);
	}
}