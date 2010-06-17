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
			$this->setError('COM_MEDIAGALLERIES_MUST_CONTAIN_A_TITLE');
			return false;
		}

		// check for valid name 
		if( empty($this->catid) ) {
			$this->setError('COM_MEDIAGALLERIES_MUST_CONTAIN_A_TITLE');
			return false;
		}
		
		// check for valid url		
		
		if ( !(
			strpos('http://',$this->url) 
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
	
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string)$registry;
		}
		
		//Fix uid
		
		//if(empty($data['userid'])){
			$user			=& JFactory::getUser();
			$data['userid']	= $user->get('id');
		//} Just removed the condition so that the right author is saved
		
		// Fix alias
		if(!$data['alias'])
		{
			$data['alias']=$data[title];	
		}		
		$data['alias'] = JFilterOutput::stringURLSafe($data['alias']);

		
		$datenow =& JFactory::getDate($data['created']);
		$data['created'] = $datenow->toMySQL();
		
		print_r($data);
		exit;
		// bind
 		return parent::bind($data);
 		
	}
}
