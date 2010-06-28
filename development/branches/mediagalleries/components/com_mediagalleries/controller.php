<?php
/**
 * @version		$Id: controller.php 10094 2008-03-02 04:35:10Z instance $
 * @package		Joomla
 * @subpackage	JMultimedia
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
 * JMultimedia Component Controller
 *
 * @static
 */
class MediagalleriesController extends JController {
	/** @var Multi - controller Simulator */
	var $_control = null;
	/** @var Default Redirection target */
	var $_target = null;
	var $default_view= "media";
	/**
	 * constructor (registers additional tasks to methods)
	 * 
	 * @return void
	 */
	function __construct($config = array())
	{
		$option="com_mediagalleries";
		
		$this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models');		
		parent::__construct($config);
		
		$this->_control = JRequest::getCmd('controller', JRequest::getCmd('c', 'media'));
		$this->_target = 'index.php?option='.$option;
		
		//$this->registerTask( 'apply','save' );
		//$this->registerTask( 'add','edit' );
	}
	function display()
	{
		$cachable = true;
		// Get the document object.
		$document = &JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName		= JRequest::getWord('view', 'categories');
		JRequest::setVar('view', $vName);

		$user = &JFactory::getUser();
		if ($user->get('id') ||($_SERVER['REQUEST_METHOD'] == 'POST' && $vName = 'categories')) {
			$cachable = false;
		}
		$safeurlparams = array('id'=>'INT','limit'=>'INT','limitstart'=>'INT','filter_order'=>'CMD','filter_order_Dir'=>'CMD','lang'=>'CMD');

		parent::display($cachable,$safeurlparams);
	}


}