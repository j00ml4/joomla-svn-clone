<?php
/**
 * Comments Model for Mediagalleries Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:modules/
 * @license    GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * Reviews Model 
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class MediagalleriesModelChannels extends JModel
{
 	/**
     * Media data array
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
	
	/** @var object Pagination object */
	var $_pagination = null;
	
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

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		
	}
	
	
	/**
	 * Method to get weblinks item data
	 *
	 * @access public
	 * @return array
	 */
	function &getData()
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
	 * Method to get the total number of weblink items
	 * @access public
	 * @return integer
	 */
	function &getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			//$query = $this->_buildQuery();
			$query = 'SELECT id FROM #__mediagalleries_comments';
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	/**
	 * Method to get a pagination object for the weblinks
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
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */	
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();
		
		$tbl = str_replace( 'com_', '#__', JRequest::getCmd('section', 'com_content') );
		$query = ' SELECT a.*, u.name AS author, cc.title AS container '
			. ' FROM #__mediagalleries_comments AS a '
			. ' LEFT JOIN #__users AS u ON u.id = a.userid '
			. ' LEFT JOIN '.$tbl.' AS cc ON cc.id = a.content_id '
			. $where
			. $orderby;

		return $query;
	}

	function _buildContentOrderBy()
	{
		global $mainframe, $option;

		$filter_order			= $mainframe->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'a.date', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir',	'',	'word' );

		switch($filter_order){
			case 'a.title':
			case 'a.published':
			case 'a.date':
			case 'container':
			case 'a.userid':
			case 'a.hits':
			case 'a.id':
				$orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' ,  container, a.date';				
				break;
			default:
				$orderby = ' ORDER BY container '.$filter_order_Dir.', a.date';				
		}


		return $orderby;
	}

	function _buildContentWhere()
	{
		global $mainframe, $option;
		
		$db						=& JFactory::getDBO();
		$section				= JRequest::getCmd('section', 'com_content');
		$filter_state		= $mainframe->getUserStateFromRequest( $option.'filter_state',		'filter_state',		'',				'word' );
		$filter_itemid		= $mainframe->getUserStateFromRequest( $option.'filter_itemid',		'filter_itemid',		0,				'int' );
		$search				= $mainframe->getUserStateFromRequest( $option.'search', 'search', '',	'string' );
		$search				= JString::strtolower( $search );
		
		
		$where = array();		
		$where[] = 'a.section = '. $db->Quote($section);
		
		if ($filter_itemid > 0) {
			$where[] = 'a.content_id = '.(int) $filter_itemid;
		}
		if ($search) {
			$where[] = 'LOWER(a.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}
		if ($filter_state) {
			if ( $filter_state == 'P' ) {
				$where[] = 'a.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'a.published = 0';
			}
		}
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );

		return $where;
	}
	
}