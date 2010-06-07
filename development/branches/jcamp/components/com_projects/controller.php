<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */
defined("_JEXEC") or die("Restricted access");

// Imports
jimport('joomla.application.component.controller');

/**
 * Projects Controller
 * @author eden
 *
 */
class ProjectsController extends JController {
	
	/**
	 * Method to show a view
	 *
	 * @access	public
	 * @since	1.6
	 */
	function display()
	{	
		$user =& JFactory::getUser();
		$cachable = true;
		
		if ( $user->get('id') || ($_SERVER['REQUEST_METHOD'] == 'POST') ) {
			$cachable = false;
		}
		
		$safeurlparams = array(	'id'=>'INT','cid'=>'INT',
								'limit'=>'INT',	'limitstart'=>'INT',
								'filter_order'=>'CMD','filter_order_Dir'=>'CMD',
								'lang'=>'CMD');

		parent::display($cachable, $safeurlparams);
	}
	
}