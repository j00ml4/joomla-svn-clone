<?php
/**
 * @version     $Id$
 * @package     Joomla
 * @subpackage	Projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

defined("_JEXEC") or die("Restricted access");

jimport('joomla.application.component.controller');

/**
 * Projects Controller
 * @author eden
 *
 */
class ProjectsController extends JController {
	/**
	 * To DRY
	 * @var string
	 */
	protected $view;
	
	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		// Set view
		$this->view	= JRequest::getWord('view', $this->default_view);
		JRequest::setVar('view', $this->view);
	}
	
	/**
	 * Method to show a view
	 *
	 * @access	public
	 * @since	1.5
	 */
	function display()
	{
		$cachable = true;	

		$user = &JFactory::getUser();
		if ( $user->get('id') || ($_SERVER['REQUEST_METHOD'] == 'POST') ) {
			$cachable = false;
		}
		$safeurlparams = array('id'=>'INT','limit'=>'INT','limitstart'=>'INT','filter_order'=>'CMD','filter_order_Dir'=>'CMD','lang'=>'CMD');

		parent::display($cachable, $safeurlparams);
	}
}