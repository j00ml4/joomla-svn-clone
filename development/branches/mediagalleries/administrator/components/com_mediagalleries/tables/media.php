<?php
/**
 * Media Table for mediagalleries Component 
 * @package		Joomla
 * @subpackage	mediagalleries Suite
 * @license	GNU/GPL, see LICENSE.php
 * @link http://3den.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* Weblink Table class
*
* @package		Joomla
* @subpackage	Weblinks
* @since 1.0
*/
class TableMedia extends JTable
{
	var $id = null;
	var $catid = null;
	var $userid = null;

	var $title = null;
	var $alias = null;
	var $url = null;
	var $thumb_url = null;
	var $description = null;

	var $hits = null;
	var $added = null;
	var $checked_out = 0;
	var $checked_out_time = 0;
	var $ordering = null;
	var $published = null;
	
	
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
			parent::__construct('#__mediagalleries', 'id', $db);	
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	function check(){ 

		// check for valid name 
		if (trim($this->title) == '') {
			$this->setError('MUST CONTAIN A TITLE');
			return false;
		}

		// check for valid name 
		if( empty($this->catid) ) {
			$this->setError('PLEASE SELECT A CATEGORY.');
			return false;
		}
		
		// check for valid url		
		if ( !(
			strposi($this->url,'http://') 
			|| eregi( $this->url, 'https://') 
		) ){
			$this->setError('Invalid Item');
			return false;
		}
						
		return true;
	}
	
	
	/**
	 * Overloaded blindmethod
	 * 
	 * @todo improvements 
	 * @param array
	 * @return boolean	True on success
	 */
	function bind($data){
		
		// try to Bind data
		if( !parent::bind($data) ){
			return false;
		}
		
		//Fix uid
		if(empty($this->userid)){
			$user	=& JFactory::getUser();
			$this->userid=$user->get('id');
		}
		
		// Fix alias		
		$this->alias = JFilterOutput::stringURLSafe($this->title);

		$datenow =& JFactory::getDate($this->added);
		$this->added = $datenow->toMySQL();
		
		return true;
	}
}
