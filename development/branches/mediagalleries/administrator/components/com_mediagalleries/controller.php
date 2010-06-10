<?php
/**
 * Controller for mediagalleries Component
 * 
 * @package  			mediagalleries Suite
 * @subpackage 	Components
 * @link 				http://3den.org
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/** TODO
 * mediagalleries Controller
 *
 * @package		Joomla
 * @subpackage	mediagalleries
 * @since 1.5
 */
class mediagalleriesController extends JController{
	/** @var Multi - controller Simulator */
	var $_control = null;
	
	
	/**
	 * constructor (registers additional tasks to methods)
	 * 
	 * @return void
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
		
		$this->_control = JRequest::getCmd('controller', JRequest::getCmd('c', 'media'));
		$this->registerTask( 'apply', 	'save' );
		$this->registerTask( 'add',		'edit' );
	}

	/**
	 * Save Comment
	 * 
	 * @return void
	 */
	function save()
	{	
		global $option;
		
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		
		// get Model
		$model = $this->getModel($this->_control);
				
		// get Request data 
		$post	= JRequest::get('post');

		// Save Success or Error
		if ( $model->store($post) ) {// success
			$msg = JText::_( 'JLIB_APPLICATION_SAVE_SUCCESS' );
			$type = 'message';
		}
		else{// error			
			$msg = JText::_( $model->getError() );
			$type = 'error';
		} 

		$link =  'index.php?option='.$option;
		$this->setRedirect($link, $msg, $type);			
	}
	
	/**
	 * display the edit form 
	 * @return void
	 */
	function edit()
	{	
		// Set vars
		JRequest::setVar( 'view', $this->_control );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);
		
		$model = $this->getModel($this->_control);
		$model->checkout();
		
		$this->display();
	}	

	/**
	 * Remove selected Items
	 * 
	 * @return void 
	 */
	function remove()
	{
		global $option;
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );

		// IDs
		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );
	
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_('JWARNING_DELETE_MUST_SELECT') );
		}

		$model = $this->getModel($this->_control);
		if( $model->delete($cid) ){// success
			$msg = JText::_( 'JLIB_APPLICATION_SAVE_SUCCESS' );
			$type = 'message';
		}
		else{// error			
			$msg = JText::_( $model->getError() );
			$type = 'error';
		} 
		
		$link =  'index.php?option='.$option;
		$this->setRedirect($link, $msg, $type);				
	}

	/**
	 * Publish selected Items
	 * 
	 * @return 
	 */
	function publish()
	{
		global $option;
				
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'JWARNING_PUBLISH_MUST_SELECT' ) );
		}

		$model = $this->getModel($this->_control);
		if( $model->publish($cid, 1) ){// success
			$msg = JText::_( 'JLIB_APPLICATION_SAVE_SUCCESS' );
			$type = 'message';
		}
		else{// error			
			$msg = JText::_( $model->getError() );
			$type = 'error';
		} 		

		$link =  'index.php?option='.$option;
		$this->setRedirect($link, $msg, $type);			
	}

	
	/**
	 * Unpublish selected Items
	 * 
	 * @return 
	 */
	function unpublish()
	{
		global $option;		
		
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'JWARNING_UNPUBLISH_MUST_SELECT' ) );
		}
		
		$model = $this->getModel($this->_control);
		if( $model->publish($cid, 0) ){// success
			$msg = JText::_( 'JLIB_APPLICATION_SAVE_SUCCESS' );
			$type = 'message';
		}
		else{// error			
			$msg = JText::_( $model->getError() );
			$type = 'error';
		} 

		$link =  'index.php?option='.$option;
		$this->setRedirect($link, $msg, $type);			
	}
	

	/**
	 * Cancel Editing
	 * 
	 * @return void
	 */
	function cancel()
	{	
		global $option;
		
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );

		// Checkin the weblink
		$model = $this->getModel($this->_control);
		$model->checkin();

		$link =  'index.php?option='.$option;
		$this->setRedirect($link, $msg, $type);			
	}		
	
	/**
	 * Link to media plugin
	 * 
	 * @return void
	 */
	function media(){
		$db =& JFactory::getDBO();
		$query = 'SELECT id '
			. ' FROM #__plugins '
			. ' WHERE '
				. ' folder= '. $db->Quote('content')
				. ' AND element = '. $db->Quote('media');
		$db->setQuery($query);
		$id = $db->loadResult();		
		
		$link = 'index.php?option=com_plugins&view=plugin&client=site&task=edit&cid[]='.$id;
		$this->setRedirect($link);
	}
	
    /**
     * Up Item Order
     * 
     * @return void
     */
	function orderup()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );

		$model = $this->getModel($this->_control);
		$model->move(-1);

		$this->setRedirect( 'index.php?option=com_mediagalleries');
	}	
	
	/**
	 * Down Item Order
	 * 
	 * @return void
	 */
	function orderdown()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );

		$model = $this->getModel($this->_control);
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_mediagalleries' );
	}

	/**
	 * Save Items Order
	 * 
	 * @return void
	 */
	function saveorder()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		
		// Request Vars
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);
		
		$model = $this->getModel($this->_control);
		$model->saveorder($cid, $order);

		$msg = JText::_('JLIB_APPLICATION_SUCCESS_ORDERING_SAVED');
		$this->setRedirect( 'index.php?option=com_mediagalleries', $msg );
	}

}