<?php
/**
 * @version		$Id: controller.php 10094 2008-03-02 04:35:10Z instance $
 * @package		Joomla
 * @subpackage	Mediagalleries
 * @copyright	Copyright (C) 2007 - 2008 3DEN Open Software. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );

/**
 * mediagalleries Component Controller
 *
 * @static
 */
class MediagalleriesController extends JController {
	/** @var Multi - controller Simulator */
	var $_control = null;
	/** @var Default Redirection target */
	var $_target = null;
	/**
	 * constructor (registers additional tasks to methods)
	 * 
	 * @return void
	 */
	function __construct($config = array())
	{
		global $option;
		
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models');		
		parent::__construct($config);
		
		$this->_control = JRequest::getCmd('controller', JRequest::getCmd('c', 'media'));
		$this->_target = 'index.php?option='.$option;
		
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
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		// get Model
		$model = $this->getModel($this->_control);
				
		// get Request data 
		$post	= JRequest::get('post');

		// Save Success or Error
		if ( $model->store($post) ) {// success
			$msg = JText::_( 'Successfully saved changes' );
			$type = 'message';
		}
		else{// error			
			$msg = JText::_( $model->getError() );
			$type = 'error';
		} 

		$this->setRedirect($this->_target, $msg, $type);			
	}
	
	/**
	 * display the edit form 
	 * @return void
	 */
	function edit()
	{	
		// Set vars
		//JRequest::setVar( 'view', $this->_control );
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
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// IDs
		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );
	
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_('Select an item to delete') );
		}

		$model = $this->getModel($this->_control);
		if( $model->delete($cid) ){// success
			$msg = JText::_( 'Successfully saved changes' );
			$type = 'message';
		}
		else{// error			
			$msg = JText::_( $model->getError() );
			$type = 'error';
		} 
		
		$this->setRedirect($this->_target, $msg, $type);			
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
			JError::raiseError(500, JText::_( 'Select an item to publish' ) );
		}

		$model = $this->getModel($this->_control);
		if( $model->publish($cid, 1) ){// success
			$msg = JText::_( 'Successfully saved changes' );
			$type = 'message';
		}
		else{// error			
			$msg = JText::_( $model->getError() );
			$type = 'error';
		} 		

		$this->setRedirect($this->_target, $msg, $type);			
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
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
		}
		
		$model = $this->getModel($this->_control);
		if( $model->publish($cid, 0) ){// success
			$msg = JText::_( 'Successfully saved changes' );
			$type = 'message';
		}
		else{// error			
			$msg = JText::_( $model->getError() );
			$type = 'error';
		} 

		$this->setRedirect($this->_target, $msg, $type);			
	}
	

	/**
	 * Cancel Editing
	 * 
	 * @return void
	 */
	function cancel()
	{	
		global $option;
		
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Checkin the weblink
		$model = $this->getModel($this->_control);
		$model->checkin();

		$this->setRedirect($this->_target, $msg, $type);			
	}		
	
	/**
	 * Link to Media plugin
	 * 
	 * @return void
	 */
	function denvideo(){
		$db =& JFactory::getDBO();
		$query = 'SELECT id '
			. ' FROM #__plugins '
			. ' WHERE '
				. ' folder= '. $db->Quote('content')
				. ' AND element = '. $db->Quote('denvideo');
		$db->setQuery($query);
		$id = $db->loadResult();		
		
		$this->setRedirect($this->_target, $msg, $type);			
	}
	
    /**
     * Up Item Order
     * 
     * @return void
     */
	function orderup()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel($this->_control);
		$model->move(-1);

		$this->setRedirect($this->_target, $msg, $type);			
	}	
	
	/**
	 * Down Item Order
	 * 
	 * @return void
	 */
	function orderdown()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel($this->_control);
		$model->move(1);

		$this->setRedirect($this->_target, $msg, $type);			
	}

	/**
	 * Save Items Order
	 * 
	 * @return void
	 */
	function saveorder()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		// Request Vars
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);
		
		$model = $this->getModel($this->_control);
		$model->saveorder($cid, $order);

		$this->setRedirect($this->_target, $msg, $type);			
	}

}