<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

require_once dirname(__FILE__) . DS . 'articles.php';

/**
 * Content Component Archive Model
 *
 * @package		Joomla
 * @subpackage	com_content
 * @since		1.5
 */
class ContentModelArchive extends ContentModelArticles
{
	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	public $_context = 'com_content.archive';

	/**
	 * Method to auto-populate the model state.
	 *
	 * @since	1.6
	 */
	protected function _populateState()
	{
		parent::_populateState();

		// Add archive properties
		$params = $this->_state->params;
		
		// Filter on archived articles
		$this->setState('filter.published', -1);
		
		// Filter on month, year
		$this->setState('filter.month', JRequest::getInt('month'));
		$this->setState('filter.year', JRequest::getInt('year'));
		
		// Optional filter text
		$this->setState('list.filter', JRequest::getString('filter-search'));
	}	
	
	/**
	 * @return	JDatabaseQuery
	 */
	function _getListQuery()
	{
		// Set the archive ordering
		$params = $this->_state->params;
		$articleOrderby = $params->get('orderby_sec', 'rdate');
		$articleOrderDate = $params->get('order_date');
		
		// No category ordering
		$categoryOrderby = '';
		$secondary = ContentHelperQuery::orderbySecondary($articleOrderby, $articleOrderDate) . ', ';
		$primary = ContentHelperQuery::orderbyPrimary($categoryOrderby);

		$orderby = $primary . ' ' . $secondary . ' a.created DESC ';
		$this->setState('list.ordering', $orderby);
		$this->setState('list.direction', '');
		// Create a new query object.
		$query = parent::_getListQuery();
		
		// Add routing for archive
		$query->select(' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug');
		$query->select(' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as catslug');
		
		// Filter on month, year
		// First, get the date field
		$queryDate = ContentHelperQuery::getQueryDate($articleOrderDate);
		
		if ($month = $this->getState('filter.month')) {
			$query->where('MONTH('. $queryDate . ') = ' . $month);
		}
		
		if ($year = $this->getState('filter.year')) {
			$query->where('YEAR('. $queryDate . ') = ' . $year);
		}
		
		return $query;
	}
	
	/**
	 * Method to get the archived article list
	 *
	 * @access public
	 * @return array
	 */
	public function getData()
	{
		$app = JFactory::getApplication();
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			// Get the page/component configuration
			$params = &$app->getParams();

			// Get the pagination request variables
			$limit		= JRequest::getVar('limit', $params->get('display_num', 20), '', 'int');
			$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');

			$query = $this->_buildQuery();

			$this->_data = $this->_getList($query, $limitstart, $limit);
		}

		return $this->_data;
	}

	// JModel override to add alternating value for $odd
	protected function _getList($query, $limitstart=0, $limit=0)
	{
		$result = &parent::_getList($query, $limitstart, $limit);

		$odd = 1;
		foreach ($result as $k => $row) {
			$result[$k]->odd = $odd;
			$odd = 1 - $odd;
		}

		return $result;
	}

}
