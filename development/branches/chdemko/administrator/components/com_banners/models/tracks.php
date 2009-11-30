<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
jimport('joomla.database.query');

/**
 * Methods supporting a list of tracks.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_banners
 * @since		1.6
 */
class BannersModelTracks extends JModelList
{
	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context = 'com_banners.tracks';
	/**
	 * Method to auto-populate the model state.
	 */
	protected function _populateState()
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$type = $app->getUserStateFromRequest($this->_context.'.filter.type', 'filter_type');
		$this->setState('filter.type', $type);

		$begin = $app->getUserStateFromRequest($this->_context.'.filter.begin', 'filter_begin', '', 'string');
		$this->setState('filter.begin', $begin);

		$end = $app->getUserStateFromRequest($this->_context.'.filter.end', 'filter_end', '', 'string');
		$this->setState('filter.end', $end);

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
	 * Build an SQL query to load the list data.
	 *
	 * @return	JQuery
	 */
	protected function _getListQuery()
	{
		// Get the application object
		$app = &JFactory::getApplication();
		
		require_once JPATH_COMPONENT . '/helpers/banners.php';

		// Create a new query object.
		$query = new JQuery;

		// Select the required fields from the table.
		$query->select(
				'a.track_date as track_date,'.
				'a.track_type as track_type,'.
				'a.`count` as `count`'
		);
		$query->from('`#__banner_tracks` AS a');
		
		// Join with the banners
		$query->join('LEFT','`#__banners` as b ON b.id=a.banner_id');
		$query->select('b.name as name');
		
		// Join with the client
		$query->join('LEFT','`#__banner_clients` as cl ON cl.id=b.cid');
		$query->select('cl.name as client_name');
		
		// Join with the category
		$query->join('LEFT','`#__categories` as cat ON cat.id=b.catid');
		$query->select('cat.title as category_title');
		
		// Filter by type
		$type = $this->getState('filter.type');
		if (!empty($type)) {
			$query->where('a.track_type = '.(int) $type);
		}
		
		// Filter by client
		$clientId = $this->getState('filter.client_id');
		if (is_numeric($clientId)) {
			$query->where('b.cid = '.(int) $clientId);
		}
		
		// Filter by category
		$catedoryId = $this->getState('filter.category_id');
		if (is_numeric($catedoryId)) {
			$query->where('b.catid = '.(int) $catedoryId);
		}
		
		// Filter by begin date
		
		$begin = $this->getState('filter.begin');
		if (!empty($begin)) {
			$query->where('a.track_date >= '.$this->_db->Quote($begin));
		}
		
		// Filter by end date
		$end = $this->getState('filter.end');
		if (!empty($end)) {
			$query->where('a.track_date <= '.$this->_db->Quote($end));
		}

		// Add the list ordering clause.
		$orderCol = $this->getState('list.ordering', 'name');
		$query->order($this->_db->getEscaped($orderCol).' '.$this->_db->getEscaped($this->getState('list.direction', 'ASC')));

		return $query;
	}
	/**
	 * Method to delete rows.
	 *
	 * @param	array	An array of item ids.
	 *
	 * @return	boolean	Returns true on success, false on failure.
	 */
	public function delete()
	{
		// Initialise variables
		$user	= JFactory::getUser();

		// Access checks.
		$categoryId=$this->getState('category_id');
		if ($categoryId) {
			$allow = $user->authorise('core.delete', 'com_banners.category.'.(int) $categoryId);
		}
		else {
			$allow = $user->authorise('core.delete', 'com_banners');
		}

		if ($allow)
		{
			// Delete tracks from this banner
			$query = new JQuery;
			$query->delete();
			$query->from('`#__banner_tracks`');
			
			// Filter by type
			$type = $this->getState('filter.type');
			if (!empty($type)) {
				$query->where('track_type = '.(int) $type);
			}
		
			// Filter by begin date		
			$begin = $this->getState('filter.begin');
			if (!empty($begin)) {
				$query->where('track_date >= '.$this->_db->Quote($begin));
			}
		
			// Filter by end date
			$end = $this->getState('filter.end');
			if (!empty($end)) {
				$query->where('track_date <= '.$this->_db->Quote($end));
			}
			
			$where='1';
			// Filter by client
			$clientId = $this->getState('filter.client_id');
			if (!empty($clientId)) {
				$where.=' AND cid = '.(int) $clientId;
			}
			// Filter by category
			if (!empty($categoryId)) {
				$where.=' AND catid = '.(int) $categoryId;
			}
			
			$query->where('banner_id IN (SELECT id FROM `#__banners` WHERE '.$where.')');
		
			$this->_db->setQuery((string)$query);
			$this->setError((string)$query);
			$this->_db->query();

			// Check for a database error.
			if ($this->_db->getErrorNum()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		else
		{
			JError::raiseWarning(403, JText::_('JError_Core_Delete_not_permitted'));
		}

		return true;
	}
}
