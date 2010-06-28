<?php
/**
 * Media Model for Mediagalleries Component
 * 
 * @package    		Mediagalleries Suite
 * @subpackage 	Components
 * @link				http://3den.org
 * @license		GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

/**
 * Media Model
 *
 * @package    Mediagalleries Suite
 * @subpackage Components
 */
class MediagalleriesModelChannel extends JModel
{
	/**
	 * Media id
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * Media data
	 *
	 * @var array
	 */
	var $_data = null;
	
	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		$cid = JRequest::getVar('cid', array(0), '', 'array');

		$this->setId( (int)$cid[0] );		
	}
	
	/**
	 * Method to set the Media identifier
	 *
	 * @access	public
	 * @param	int Weblink identifier
	 */
	function setId($id)
	{
		// Set weblink id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get a Media
	 * 
	 * @return void
	 */	
	function &getData()
	{

		// Load the media data
		if ( $this->_loadData() )
		{
			// Initialize some variables
			$user = &JFactory::getUser();
			
		}
		else  {
			$this->_initData();
		}

		return $this->_data;
	}
	

	/**
	 * Tests if Media is checked out 
	 *
	 * @access	public
	 * @param	int	A user id
	 * @return	boolean	True if checked out
	 */
	function isCheckedOut( $uid=0 )
	{
		if ($this->_loadData())
		{
			if ($uid) {
				return ( $this->_data->checked_out && $this->_data->checked_out != $uid );
			} else {
				return $this->_data->checked_out;
			}
		}
	}


	/**
	 * Method to checkin/unlock the Media
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function checkin()
	{
		if ($this->_id)
		{
			$row =& $this->getTable('comment');
			if(! $row->checkin($this->_id) ) {
				$this->setError($row->getError());
				return false;
			}
		}
		return true;
	}

	/**
	 * Method to checkout/lock the Media
	 *
	 * @access	public
	 * @param	int $uid User ID of the user checking the article out
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkout($uid = null)
	{
		if ($this->_id)
		{
			// Make sure we have a user id to checkout the article with
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			// Lets get to it and checkout the thing...
			$row =& $this->getTable( 'comment' );
			if(! $row->checkout($uid, $this->_id) ) {
				// TODO cange as chekin 
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			return true;
		}
		return false;
	}

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store( $data )
	{
		$row =& $this->getTable('comment');
		
		// Bind the form fields to the media table
		if (!$row->bind($data)) {
			$this->setError( $row->getError() );
			return false;
		}
		
		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($row->getError());
			return false;
		}
		
		// Store to the database
		if (!$row->store()) {
			$this->setError( $row->getError() );
			return false;
		}
		
		return true;
	}


	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @param array cid
	 * @return	boolean	True on success
	 */
	function delete($cid=array())
	{
		JArrayHelper::toInteger($cid);
		$cids = implode( ',', $cid );
		$query = 'DELETE FROM #__mediagalleries_comments'
			. ' WHERE id IN ( '.$cids.' )';
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;		
	}


	/** bug?
	 * Method to (un)publish a Comment
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();

		$row =& $this->getTable( 'comment' );
		if ( !$row->publish( $cid, $publish ) ) {
			$this->setError( $row->getErrorMsg() );
			return false;				
		}
		return true;
		return true;
	}
	

	
	/**
	 * Method to load content Mediagalleries data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if ( empty($this->_data) )
		{
			$query = 'SELECT c.* '
				. ' FROM #__mediagalleries_comments AS c ' 
				. ' WHERE c.id = '.(int) $this->_id;
					
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the Mediagalleries data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if ( empty($this->_data) )
		{			
			$item = new stdClass();	
			// Create the object
			$item->id = null;
			/** @var string user IP */
			$item->ip = null;
			/** @var int component foreign key */
			$item->section = null;
			/** @var int categories foreign key */
			$item->content_id = null;
			/** @var int users foreign key */
			$item->userid = null;
			
			/** @var string title */
			$item->title = null;
			/** @var string alias */
			$item->comment= null;
			/** @var datetime date */
			$item->date = null;
			
			/** @var int hits */
			$item->hits = 0;
			/** @var int checked by */
			$item->checked_out = 0;
			/** @var datetime checked time */
			$item->checked_out_time = 0;
			/** @var boolean True on published */
			$item->published = 1;
			/** @var string commenr parameters */
			$item->params = '';
		
			// Set data
			$this->_data	= $item;
			return (boolean) $this->_data;
		}
		return true;
	}

}