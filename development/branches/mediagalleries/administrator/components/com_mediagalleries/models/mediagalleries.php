<?php
/**
 * Madias Model for mediagalleries Component
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
 * JMulti Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class mediagalleriesModelmediagalleries extends JModel
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
	
	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;
	
	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();
		global  $option;
		$mainframe=&JFactory::getApplication();

		// Get the pagination request variables
		$limit = $mainframe->getUserStateFromRequest( 
			'global.list.limit', 'limit', 
			$mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= JRequest::getInt('limitstart', 0);

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
			$query = 'SELECT id FROM #__mediagalleries';
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
		$query = 'SELECT a.id AS id, a.catid AS catid, a.userid AS userid, '
			. ' a.published AS published,  a.ordering AS ordering, a.checked_out AS checked_out, '
			. ' a.title AS title, a.alias AS alias, a.description AS description, '
			. ' a.url AS url, a.thumb_url AS thumbnail, '
			. ' a.added AS added, a.hits AS hits, '
			. ' ( a.rank / (a.votes+1) ) AS rating, '
			. ' cc.title AS category, u.name AS author, '
			. ' 	cc.published AS cat_pub, cc.access AS cat_access'		
		. ' FROM #__mediagalleries AS a' 
		. ' LEFT JOIN #__categories AS cc ON cc.id = a.catid '
		. ' LEFT JOIN #__users AS u ON u.id = a.userid '
		. $this->_buildContentWhere()
		. $this->_buildContentOrderBy();

		return $query;
	}

	function _buildContentOrderBy()
	{
		global $option;
		$mainframe=&JFactory::getApplication();

		$filter_order		= JRequest::getCmd('filter_order');
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		switch($filter_order){
			case 'a.title':
			case 'a.published':
			case 'category':
			case 'a.hits':
			case 'a.id':
				$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , category, a.ordering ';
				break;
				
			case 'a.ordering':
			default:	
				$orderby 	= ' ORDER BY category, a.ordering '.$filter_order_Dir;
				break;
									
		}

		return $orderby;
	}

	function _buildContentWhere()
	{
		global $option;
		$mainframe=&JFactory::getApplication();
		
		$db					=& JFactory::getDBO();
		$filter_state		= $mainframe->getUserStateFromRequest( $option.'filter_state',		'filter_state',		'',				'word' );
		$filter_catid		= JRequest::getInt( 'catid' );
		$filter_order		= JRequest::getCmd('filter_order');
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$search				= JString::strtolower( JRequest::getString( 'search') );

		$where = array();

		if ($filter_catid > 0) {
			$where[] = 'a.catid = '.(int) $filter_catid;
		}
		if ($search) {
			$where[] = 'LOWER(a.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}
		if ( $filter_state ) {
			$filter_state = strtoupper($filter_state);
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