<?php
/**
 * Media Model for mediagalleries Component
 * 
 * @package    		mediagalleries Suite
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
 * @package    mediagalleries Suite
 * @subpackage Components
 */
class mediagalleriesModelMedia extends JModel
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
	
		$cid = JRequest::getVar('cid', 
			array( JRequest::getInt('id', 0) ), 
			'', 'array');
		$this->setId($cid[0]);		
	}
	
	/**
	 * Method to set the Media identifier
	 *
	 * @access	public
	 * @param	int Weblink identifier
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	/**
	 * View item
	 *
	 */
	function hit(){
		$row =& $this->getTable('media');
		$row->hit($this->_id);
	}
	/**
	 * Method to get a Media
	 *
	 */	
	function &getData()
	{
		// Load the media data
		if ( $this->_loadData() )
		{
			// Initialize some variables
			$user = &JFactory::getUser();
			
			// Check to see if the category is published
			if (!$this->_data->cat_pub) {
				$this->setError('Resource Not Found');
				return;
			}

			// Check whether category access level allows access
			if ($this->_data->cat_access > $user->get('aid', 0)) {
				$this->setError( 'ALERTNOTAUTH' );
				return;
			}
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
	 * @since	1.5
	 */
	function checkin()
	{
		if ($this->_id)
		{
			$row =& $this->getTable('media');
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
			$row =& $this->getTable( 'media' );
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
		$row =& $this->getTable('media');
		include_once(JPATH_COMPONENT_SITE.DS.'helpers/files.php');
		
		// Local Upload
		$folder = JPATH_SITE.DS.$data['toFolder'];
		$files = JRequest::get('files');
		$prefix = 'u'.rand(1, 100).'_';	
		
		// Local file
		if( !empty($files['uplocal']['name']) ){
			$data['url'] = FilesHelper::sendFile( $files['uplocal'], $folder, $data['MAX_FILE_SIZE'], $prefix );	
			if(empty($data['url'])){
				$this->setError('ERROR SAVING FILE');
				return false;
			} 
		}
		
		$data['thumb_url'] = FilesHelper::getThumbURL( $data['url'] );	
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
			FilesHelper::delete(array($data['url']));
			$this->setError( $row->getError() );
			return false;
		}
		
		$row->reorder();
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
		
		// Fisical
		$query = 'SELECT url FROM #__mediagalleries' 
			. ' WHERE id IN ( '.$cids.' )';
		$this->_db->setQuery( $query );
		$rows = $this->_db->loadResultArray();
		
		// Logical delete
		$query = 'DELETE FROM #__mediagalleries'
			. ' WHERE id IN ( '.$cids.' )';
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Import files helper
		include_once( PATH_HELPERS.'files.php' );
		FilesHelper::delete($rows);
		
		
		return true;		
	}
	
	
	/**
	 * Method to move a mediagalleries
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable();
		$groupings = array();

		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );
			// track categories
			$groupings[] = $row->catid;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}

		// execute updateOrder for each parent group
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder('catid = '.(int) $group);
		}

		return true;
	}


	/**
	 * Move Ordering
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function move($direction)
	{
		$row =& $this->getTable('media');
		if (!$row->load($this->_id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if ( !$row->move( $direction, ' catid='.(int) $row->catid )	 ) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}


	/**
	 * Method to (un)publish a weblink
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();

		$row =& $this->getTable( 'media' );
		if ( !$row->publish( $cid, $publish, $user->get('id') ) ) {
			$this->setError( $row->getError() );
			return false;				
		}
		return true;
	}
	


	/**
	 * Method to load content mediagalleries data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if ( empty($this->_data) )
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
			. ' LEFT JOIN #__categories AS cc ON cc.id = a.catid'
			. ' LEFT JOIN #__users AS u ON u.id = a.userid'
			. ' WHERE a.id = '.(int) $this->_id;
	
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the mediagalleries data
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
			$item->catid = null;
			$item->userid = null;
			$item->author = null;
			$item->title = null;
			$item->alias = null;
			$item->url = null;
			$item->thumbnail = null;
			$item->description = null;
			$item->hits = null;
			$item->views = null;			
			$item->rank = 0;
			$item->votes = 0;
			$item->rating = 0;
			$item->date = null; 
			$item->added = null; 			
			$item->checked_out = 0;
			$item->checked_out_time = null;
			$item->ordering = null;
			$item->published = null;
			
			// Set data
			$this->_data	= $item;
			return (boolean) $this->_data;
		}
		return true;
	}

}
