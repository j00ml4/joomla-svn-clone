<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of banner records.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_banners
 * @since		1.6
 */
class BannersModelBanners extends JModelList
{
	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context = 'com_banners.banners';
	/**
	 * Categories data
	 * @var		array
	 */
	protected $_categories;

	/**
	 * Method to auto-populate the model state.
	 */
	protected function _populateState()
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->_context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$state = $app->getUserStateFromRequest($this->_context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $state);

		$categoryId = $app->getUserStateFromRequest($this->_context.'.filter.category_id', 'filter_category_id', '');
		$this->setState('filter.category_id', $categoryId);

		$clientId = $app->getUserStateFromRequest($this->_context.'.filter.client_id', 'filter_client_id', '');
		$this->setState('filter.client_id', $clientId);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_banners');
		$this->setState('params', $params);

		// List state information.
		parent::_populateState('name', 'asc');
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	*/
	public function getTable($type = 'Banner', $prefix = 'BannersTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
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
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.category_id');

		return parent::_getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 */
	protected function _getListQuery()
	{
		// Get the application object
		$app = &JFactory::getApplication();

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id AS id, a.name AS name, a.alias AS alias,'.
				'a.checked_out AS checked_out,'.
				'a.checked_out_time AS checked_out_time, a.catid AS catid,' .
				'a.clicks AS clicks, a.metakey AS metakey, a.sticky AS sticky,'.
				'a.impmade AS impmade, a.imptotal AS imptotal,' .
				'a.state AS state, a.ordering AS ordering,'.
				'a.purchase_type as purchase_type'
			)
		);
		$query->from('`#__banners` AS a');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the categories.
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Join over the clients.
		$query->select('cl.name AS client_name,cl.purchase_type as client_purchase_type');
		$query->join('LEFT', '#__banner_clients AS cl ON cl.id = a.cid');

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = '.(int) $published);
		}
		else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by category.
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId)) {
			$query->where('a.catid = '.(int) $categoryId);
		}

		// Filter by client.
		$clientId = $this->getState('filter.client_id');
		if (is_numeric($clientId)) {
			$query->where('a.cid = '.(int) $clientId);
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('(a.name LIKE '.$search.' OR a.alias LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol = $this->getState('list.ordering', 'ordering');
		$app->setUserState($this->_context . '.'.$orderCol.'.orderdirn',$this->getState('list.direction', 'ASC'));
		if ($orderCol=='ordering') {
			$query->order($db->getEscaped('category_title').' '.$db->getEscaped($app->getUserState($this->_context . '.category_title.orderdirn','ASC')));
		}
		$query->order($db->getEscaped($orderCol).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));
		if ($orderCol=='category_title') {
			$query->order($db->getEscaped('ordering').' '.$db->getEscaped($app->getUserState($this->_context . '.ordering.orderdirn','ASC')));
		}
		$query->order($db->getEscaped('state').' '.$db->getEscaped($app->getUserState($this->_context . '.state.orderdirn','ASC')));

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}
	/**
	 * method to give information about categories
	 */
	function &getCategories()
	{
		if (!isset($this->_categories))
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('MAX(ordering) as `max`');
			$query->select('catid');
			$query->from('#__banners');
			$query->where('state>=0');
			$query->group('catid');
			$db->setQuery((string)$query);
			$this->_categories = $db->loadObjectList('catid');
		}
		return $this->_categories;
	}
	/**
	 * Method to delete rows.
	 *
	 * @param	array	An array of item ids.
	 *
	 * @return	boolean	Returns true on success, false on failure.
	 */
	public function delete(&$pks)
	{
		// Initialise variables
		$user	= JFactory::getUser();

		// Typecast variable.
		$pks = (array) $pks;

		// Get a row instance.
		$table = &$this->getTable();

		// Iterate the items to delete each one.
		foreach ($pks as $i => $pk)
		{
			if ($table->load($pk)) {
				// Access checks.
				if ($table->catid) {
					$allow = $user->authorise('core.delete', 'com_banners.category.'.(int) $table->catid);
				} else {
					$allow = $user->authorise('core.delete', 'com_banners');
				}

				if ($allow) {
					if (!$table->delete($pk)) {
						$this->setError($table->getError());
						return false;
					}

					// Delete tracks from this banner
					$db = $this->getDbo();
					$query = $db->getQuery(true);
					$query->delete();
					$query->from('#__banner_tracks');
					$query->where('banner_id='.(int)$pk);
					$db->setQuery((string)$query);
					$db->query();

					// Check for a database error.
					if ($db->getErrorNum()) {
						$this->setError($db->getErrorMsg());
						return false;
					}
				} else {
					// Prune items that you can't change.
					unset($pks[$i]);
					JError::raiseWarning(403, JText::_('JERROR_CORE_DELETE_NOT_PERMITTED'));
				}
			} else {
				$this->setError($table->getError());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to publish records.
	 *
	 * @param	array	The ids of the items to publish.
	 * @param	int		The value of the published state
	 *
	 * @return	boolean	True on success.
	 */
	function publish(&$pks, $value = 1)
	{
		// Initialise variables.
		$user	= JFactory::getUser();
		$table	= $this->getTable();
		$pks	= (array) $pks;

		// Access checks.
		foreach ($pks as $i => $pk) {
			if ($table->load($pk)) {
				if ($table->catid) {
					$allow = $user->authorise('core.edit.state', 'com_banners.category.'.(int) $table->catid);
				} else {
					$allow = $user->authorise('core.edit.state', 'com_banners');
				}

				if (!$allow) {
					// Prune items that you can't change.
					unset($pks[$i]);
					JError::raiseWarning(403, JText::_('JERROR_CORE_EDIT_STATE_NOT_PERMITTED'));
				}
			}
		}

		// Attempt to change the state of the records.
		if (!$table->publish($pks, $value, $user->get('id'))) {
			$this->setError($table->getError());
			return false;
		}

		return true;
	}
	/**
	 * Method to stick records.
	 *
	 * @param	array	The ids of the items to publish.
	 * @param	int		The value of the published state
	 *
	 * @return	boolean	True on success.
	 */
	function stick(&$pks, $value = 1)
	{
		// Initialise variables.
		$user	= JFactory::getUser();
		$table	= $this->getTable();
		$pks	= (array) $pks;

		// Access checks.
		foreach ($pks as $i => $pk) {
			if ($table->load($pk)) {
				if ($table->catid) {
					$allow = $user->authorise('core.edit.state', 'com_banners.category.'.(int) $table->catid);
				} else {
					$allow = $user->authorise('core.edit.state', 'com_banners');
				}

				if (!$allow) {
					// Prune items that you can't change.
					unset($pks[$i]);
					JError::raiseWarning(403, JText::_('JERROR_CORE_EDIT_STATE_NOT_PERMITTED'));
				}
			}
		}

		// Attempt to change the state of the records.
		if (!$table->stick($pks, $value, $user->get('id'))) {
			$this->setError($table->getError());
			return false;
		}

		return true;
	}
	/**
	 * Saves the manually set order of records.
	 *
	 * @param	array	An array of primary key ids.
	 * @param	int		+/-1
	 */
	function saveorder(&$pks, $order)
	{
		// Get the user
		$user = JFactory::getUser();

		// Initialise variables.
		$table		= $this->getTable();
		$conditions	= array();

		if (empty($pks)) {
			return JError::raiseWarning(500, JText::_('COM_BANNERS_NO_BANNERS_SELECTED'));
		}

		// update ordering values
		foreach ($pks as $i => $pk) {
			$table->load((int) $pk);

			if ($table->state>=0) {
				// Access checks.
				if ($table->catid) {
					$allow = $user->authorise('core.edit.state', 'com_banners.category.'.(int) $table->catid);
				} else {
					$allow = $user->authorise('core.edit.state', 'com_banners');
				}

				if (!$allow) {
					// Prune items that you can't change.
					unset($pks[$i]);
					JError::raiseWarning(403, JText::_('JERROR_CORE_EDIT_STATE_NOT_PERMITTED'));
				} else if ($table->ordering != $order[$i]) {
					$table->ordering = $order[$i];
					if (!$table->store()) {
						$this->setError($table->getError());
						return false;
					}
					// remember to reorder this category
					$condition = 'catid = '.(int) $table->catid.' AND state>=0';
					$found = false;
					foreach ($conditions as $cond) {
						if ($cond[1] == $condition) {
							$found = true;
							break;
						}
					}
					if (!$found) {
						$conditions[] = array ($table->id, $condition);
					}
				}
			}
		}

		// Execute reorder for each category.
		foreach ($conditions as $cond) {
			$table->reorder($cond[1]);
		}

		// Clear the component's cache
		$cache = JFactory::getCache('com_banners');
		$cache->clean();

		return true;
	}
}
