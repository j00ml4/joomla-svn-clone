<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Content Component Controller
 *
 * @package		Joomla.Site
 * @subpackage	com_content
 * @since		1.5
 */
class ContentController extends JController
{
	/**
	 * Display the view
	 */
	function display()
	{
		$cachable = true;
		
		// Set the default view name and format from the Request.
		$vName		= JRequest::getWord('view', 'categories');
		JRequest::setVar('view', $vName);
		
		$user = &JFactory::getUser();
		/* @Todo Check which vies & layouts need cache off
		if ($user->get('id') ||
			($vName == 'category' && JRequest::getVar('layout') != 'blog' && $_SERVER['REQUEST_METHOD'] == 'POST') ||
			 $vName == 'archive' && $_SERVER['REQUEST_METHOD'] == 'POST') {
			$cachable = false;
		}**/
		
		if ($user->get('id') ||$_SERVER['REQUEST_METHOD'] == 'POST') {
			$cachable = false;
		}
		
		//$view->display();*/
		$safeurlparams = array('catid'=>'INT','id'=>'INT','cid'=>'ARRAY','year'=>'INT','month'=>'INT','limit'=>'INT','limitstart'=>'INT',
			'showall'=>'INT','return'=>'BASE64','filter'=>'STRING','filter_order'=>'CMD','filter_order_Dir'=>'CMD','filter-search'=>'STRING','print'=>'BOOLEAN');

			parent::display($cachable,$safeurlparams);
		}
	
}
