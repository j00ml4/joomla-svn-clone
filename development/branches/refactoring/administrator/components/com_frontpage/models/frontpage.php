<?php
/**
 * @version		$Id $
 * @package		Joomla
 * @subpackage	Frontpage
 * @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

/**
 * Frontpage Component Frontpage Model
 *
 * @package		Joomla
 * @subpackage	Frontpage
 * @since 1.5
 */
class FrontpageModelFrontpage extends JModel
{
	/**
	 * Category ata array
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Category total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;

	/**
	 * Filter object
	 *
	 * @var object
	 */
	var $_filter = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		global $mainframe, $option;

		// Get the pagination request variables
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

		$filter = new stdClass();
		$filter->order		= $mainframe->getUserStateFromRequest( $option.'filter_order',		'filter_order',		'fpordering',	'cmd' );
		$filter->order_Dir	= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$filter->state		= $mainframe->getUserStateFromRequest( $option.'filter_state',		'filter_state',		'',				'word' );
		$filter->catid		= $mainframe->getUserStateFromRequest( $option.'filter_catid',		'filter_catid',		0,				'int' );
		$filter->search		= $mainframe->getUserStateFromRequest( $option.'search',			'search',			'',				'string' );
		$filter->authorid	= $mainframe->getUserStateFromRequest( $option.'filter_authorid',	'filter_authorid',	0,				'int' );
		$filter->sectionid	= $mainframe->getUserStateFromRequest( $option.'filter_sectionid',	'filter_sectionid',	-1,				'int' );
		$this->_filter = $filter;
	}

	/**
	 * Method to get Frontpages item data
	 *
	 * @access public
	 * @return array
	 */
	function getData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}

	/**
	 * Method to get the total number of Frontpage items
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	/**
	 * Method to get a pagination object for the Frontpages
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Method to get filter object for the Frontpages
	 *
	 * @access public
	 * @return object
	 */
	function getFilter()
	{
		return $this->_filter;
	}

	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = 'SELECT c.*, g.name AS groupname, cc.title as name, s.title AS sect_name, u.name AS editor, f.ordering AS fpordering, v.name AS author'
			. ' FROM #__content AS c'
			. ' LEFT JOIN #__categories AS cc ON cc.id = c.catid'
			. ' LEFT JOIN #__sections AS s ON s.id = cc.section AND s.scope="content"'
			. ' INNER JOIN #__content_frontpage AS f ON f.content_id = c.id'
			. ' INNER JOIN #__groups AS g ON g.id = c.access'
			. ' LEFT JOIN #__users AS u ON u.id = c.checked_out'
			. ' LEFT JOIN #__users AS v ON v.id = c.created_by'
			. $where
			. $orderby
		;

		return $query;
	}

	function _buildContentOrderBy()
	{
		$orderby 	= ' ORDER BY '. $this->_filter->order .' '. $this->_filter->order_Dir .', fpordering';
		return $orderby;
	}

	function _buildContentWhere()
	{
		$search				= JString::strtolower( $this->_filter->search );

		$where = array();
		$where[] = "c.state >= 0";

		if ($this->_filter->catid > 0) {
			$where[] = 'c.catid = '.(int) $this->_filter->catid;
		}
		if ( $this->_filter->sectionid >= 0 ) {
			$where[] = 'c.sectionid = '.(int) $this->_filter->sectionid;
		}
		if ( $this->_filter->authorid > 0 ) {
			$where[] = 'c.created_by = '. (int) $this->_filter->authorid;
		}
		if ( $this->_filter->state ) {
			if ( $this->_filter->state == 'P' ) {
				$where[] = 'c.state = 1';
			} else if ($this->_filter->state == 'U' ) {
				$where[] = 'c.state = 0';
			}
		}
		if ($search) {
			$where[] = 'LOWER(c.title) LIKE '.$this->_db->Quote('%'.$this->_db->getEscaped( $search, true ).'%', false);
		}

		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
	}

	/**
	 * Method to save article order
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.6
	 */
	function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable();

		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}

		// execute updateOrder
		$row->reorder(' 1 ');

		return true;
	}

	/**
	 * Method to remove articles
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.6
	 */
	function delete($cid = array())
	{
		$result = false;

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__content_frontpage'
				. ' WHERE content_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
}