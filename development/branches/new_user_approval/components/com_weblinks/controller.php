<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	Weblinks
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Weblinks Component Controller
 *
 * @package		Joomla.Site
 * @subpackage	Weblinks
 * @since 1.5
 */
class WeblinksController extends JController
{
	/**
	 * Method to show a weblinks view
	 *
	 * @access	public
	 * @since	1.5
	 */
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
		$safeurlparams = array('id'=>'INT','limit'=>'INT','limitstart'=>'INT','filter_order'=>'CMD','filter_order_Dir'=>'CMD');
		
		parent::display($cachable,$safeurlparams);
	}
}