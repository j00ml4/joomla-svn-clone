<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Threads model for the Social package.
 *
 * @package		Joomla.Site
 * @subpackage	com_social
 * @since		1.2
 */
class SocialModelThreads extends JModelList
{
	/**
	 * Context string for the model type.  This is used to handle uniqueness
	 * when dealing with the getStoreId() method and caching data structures.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $_context = 'com_social.threads';

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
		$query->from('`#__social_threads` AS a');

		// If the model is set to check item state, add to the query.
		if ($this->getState('check.state', true)) {
			$query->where('a.status = ' . (int)$this->getState('filter.state'));
		}

		// Filter the threads over the context if set.
		$context = $this->getState('filter.context');
		if (!empty($context)) {
			$query->where('a.context = '.$db->Quote($context));
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering')));

		// Resolve foriegn keys.
		$query->select('COUNT(b.id) AS comment_count');
		$query->join('LEFT', '`#__social_comments` AS b ON b.thread_id=a.id AND b.published=1');
		$query->group('a.id');

		$query->select('c.pscore AS rating_pscore, c.pscore_count AS rating_pscore_count, c.mscore AS rating_mscore');
		$query->join('LEFT', '`#__social_ratings` AS c ON c.thread_id=a.id');

		//echo nl2br(str_replace('#__','jos_',$query)).'<hr/>';
		return $query;
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

		// Add the check state.
		$id	.= ':'.$this->getState('check.state');

		// The parent class adds the list state.
		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState()
	{
		// Initialize variables.
		$app		= &JFactory::getApplication();
		$user		= &JFactory::getUser();
		$config		= &JFactory::getConfig();
		$params		= $app->getParams('com_social');
		$context	= 'com_social.threads.';

		// Load the filter state.
		$this->setState('filter.context', JRequest::getWord('context', $params->get('context', null)));
		$this->setState('filter.state', 1);

		// If the limit is set to -1, use the global config list_limit value.
		$limit	= JRequest::getInt('limit', $params->get('list_limit', 0));
		$limit	= ($limit === -1) ? $app->getCfg('list_limit', 20) : $limit;

		// Load the list state.
		$this->setState('list.start', JRequest::getInt('limitstart'));
		$this->setState('list.limit', $limit);

		// Load the list ordering.
		switch ($params->get('threads-order')) {
			case 'comments_asc':
				$this->setState('list.ordering', 'comment_count ASC');
				break;
			case 'comments_dsc':
				$this->setState('list.ordering', 'comment_count DESC');
				break;
			case 'created_asc':
				$this->setState('list.ordering', 'a.created_time ASC');
				break;
			case 'created_dsc':
				$this->setState('list.ordering', 'a.created_time DESC');
				break;
			case 'rating_asc':
				$this->setState('list.ordering', 'rating_pscore ASC');
				break;
			case 'rating_dsc':
				$this->setState('list.ordering', 'rating_pscore DESC');
				break;
			case 'random':
				$this->setState('list.ordering', 'RAND()');
				break;
			default:
				$this->setState('list.ordering', 'comment_count DESC');
				break;
		}

		// Load the user parameters.
		$this->setState('user',	$user);
		$this->setState('user.id', (int)$user->id);
		$this->setState('user.aid', (int)$user->get('aid'));

		// Load the check parameters.
		$this->setState('check.state', true);

		// Load the parameters.
		$this->setState('params', $params);
	}
}
