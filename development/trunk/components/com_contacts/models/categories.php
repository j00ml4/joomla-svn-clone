<?php
/**
 * @version		$Id: categories.php
 * @package		Joomla
 * @subpackage	ContactDirectory
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * @package		Joomla
 * @subpackage	ContactDirectory
 */
class ContactsModelCategories extends JModel
{
	var $_data = null;
	var $_total = null;
	var $_categories = null;
	var $_fields = null;
	var $_pagination = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	public function __construct()
	{
		parent::__construct();
		$mainframe = JFactory::getApplication();
		$config = JFactory::getConfig();
		
		// Get the pagination request variables
		$this->setState('limit', $mainframe->getUserStateFromRequest('com_contacts.limit', 'limit', $config->getValue('config.list_limit'), 'int'));
		$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));

		// In case limit has been changed, adjust limitstart accordingly
		$this->setState('limitstart', ($this->getState('limit') != 0 ? (floor($this->getState('limitstart') / $this->getState('limit')) * $this->getState('limit')) : 0));
	}

	/**
	 * Method to get contact item data for the categories
	 *
	 * @access public
	 * @return array
	 */
	public function getData($groupby_cat)
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery($groupby_cat);
			$rows = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		
			foreach($rows as $row) {
				$row->slug = $row->id.':'.$row->alias;
			}
			$this->_data = $rows;
		}
		return $this->_data;
	}

	public function getFields($groupby_cat)
	{
		if(!$this->_fields){
			$this->getData($groupby_cat);
			for($i=0; $i<count($this->_data); $i++) {
				$id = $this->_data[$i]->id;
				$query = " SELECT f.id, f.title, d.data, f.pos, f.type, d.show_directory AS show_field, f.params, f.access "
						." FROM #__contacts_fields f "
						." LEFT JOIN #__contacts_details d ON d.field_id = f.id "
						." WHERE f.published = 1 AND d.contact_id = $id"
						." ORDER BY f.pos, f.ordering ";
				$this->_db->setQuery($query);
				$this->_fields[] = $this->_db->loadObjectList();	
			}
		}
		return $this->_fields;
	}
	
	/**
	 * Method to load category data if it doesn't exist.
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	public function getCategories()
	{
		$mainframe = JFactory::getApplication();

		if (empty($this->_categories))
		{
			// Get the page/component configuration
			$params = &$mainframe->getParams();
	
			$orderby_params	= $params->def('orderby_cat', 'order');
			switch ($orderby_params) {
				case 'alpha' :
					$orderby = ' ORDER BY title ';
					break;
				case 'ralpha' :
					$orderby = ' ORDER BY title DESC ';
					break;
				case 'order' :
					$orderby = ' ORDER BY ordering';
					break;
				default :
					$orderby = '';
					break;
			}
			
			// Lets get the information for the current category
			$query = "SELECT *, "
							." CASE WHEN CHAR_LENGTH(alias) "
							." THEN CONCAT_WS(':', id, alias) ELSE id END AS catslug "
							." FROM #__categories WHERE extension = 'com_contacts'".$orderby;
			$this->_db->setQuery($query);
			$this->_categories = $this->_db->loadObjectList();
		}
		return $this->_categories;
	}
	
	/**
	 * Method to get the total number of contact items for the categories
	 *
	 * @access public
	 * @return integer
	 */
	public function getTotal($groupby_cat)
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery($groupby_cat);
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}
	
	function getPagination($groupby_cat)
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal($groupby_cat), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}

	function _buildQuery($groupby_cat)
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy($groupby_cat);

		// if the Group By Category parameter is selected, all contacts are returned (even multiples)
		// if not only the distinct contacts are returned
		if($groupby_cat){
			$query = ' SELECT c.*, cat.title AS category, '
				. ' CASE WHEN CHAR_LENGTH(cat.alias) '
				. ' THEN CONCAT_WS(\':\', cat.id, cat.alias) ELSE cat.id END AS catslug '
				. ' FROM #__contacts_contacts AS c '
				. ' LEFT JOIN #__contacts_con_cat_map AS map ON map.contact_id = c.id '
				. ' LEFT JOIN #__categories AS cat ON cat.id = map.category_id '.
				$where.
				$orderby;
		}else{
			$query =' SELECT DISTINCT c.*, '
				. ' 	CASE WHEN CHAR_LENGTH(cat.alias) '
				. ' THEN CONCAT_WS(\':\', cat.id, cat.alias) ELSE cat.id END AS catslug '
				. ' FROM #__contacts_contacts AS c '
				. ' LEFT JOIN #__contacts_con_cat_map AS map ON map.contact_id = c.id '
				. ' LEFT JOIN #__categories AS cat ON cat.id = map.category_id '.
			$where.
			$orderby;
			
		}
		return $query;
	}


	protected function _buildContentOrderBy($groupby_cat)
	{
		global $mainframe;
		// Get the page/component configuration
		$params = &$mainframe->getParams();

		$orderby = ' ORDER BY ';
		
		if($groupby_cat){
			$orderby .= ' cat.title, ';
		}
		
		$orderby_params	= $params->def('orderby', 'order');
		switch ($orderby_params) {
			case 'alpha' :
				$orderby .= ' c.name ';
				break;
			case 'ralpha' :
				$orderby .= ' c.name DESC ';
				break;
			case 'order' :
				$orderby .= ' map.ordering';
				break;
		}
		return $orderby;
	}
	
	protected function _buildContentWhere()
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$user =& JFactory::getUser();
		$gid	= $user->get('aid', 0);
		$db =& JFactory::getDBO();
		
		$alphabet	= $mainframe->getUserStateFromRequest( $option.'alphabet', 	'alphabet',	'',	'string' );
		$search		= $mainframe->getUserStateFromRequest( $option.'search',		'search',	'',	'string' );
		$search		= JString::strtolower( $search );

		// Get the page/component configuration
		$params = &$mainframe->getParams();

        $where = ' WHERE 1';

		// Does the user have access to view the items?
		$where .= ' AND c.access <= '.(int) $gid;

		/*
		 * If we have a filter, and this is enabled... lets tack the AND clause
		 * for the filter onto the WHERE clause of the contact item query.
		 */
		if ($params->get('search')) {
			if ($search) {
				// clean filter variable
				$search = JString::strtolower($search);
				$search	= $this->_db->Quote( '%'.$this->_db->getEscaped( $search, true ).'%', false );

				$where .= ' AND LOWER( c.name ) LIKE '.$search;
			}
		}
		if ($params->get('alphabet')) 	{
			if ($alphabet) {
				// clean filter variable
				$alphabet = JString::strtolower($alphabet);
				$alphabet	= $this->_db->Quote( $this->_db->getEscaped( $alphabet, true ).'%', false );

				$where .= ' AND LOWER( c.name ) LIKE '.$alphabet;
			}
		}
		return $where;
	}
}
?>