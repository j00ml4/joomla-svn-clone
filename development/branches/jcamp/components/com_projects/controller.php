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
	
	protected $default_view='portfolios';
	
	/**
	 * Method to show a view
	 *
	 * @access	public
	 * @since	1.6
	 */
	function display()
	{	
		$app 	= &JFactory::getApplication();
		$user 	= &JFactory::getUser();
		$params = &$app->getParams(); 
		
		// HTML
		$document = &JFactory::getDocument();
		if($document->getType() == 'html' && ($style = $params->get('style'))) {
			$document->addStyleSheet('components/com_projects/assets/css/'.$style);
		}		
	  	JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
	  	
		// Cache
		$cachable = true;	
		if ( $user->get('id') || ($_SERVER['REQUEST_METHOD'] == 'POST') ) {
			$cachable = false;
		}
		
		// Save Params
		$safeurlparams = array(	'id'=>'INT','cid'=>'INT',
								'limit'=>'INT',	'limitstart'=>'INT',
								'filter_order'=>'CMD','filter_order_Dir'=>'CMD',
								'lang'=>'CMD');

		// add 'home page' of our component breadcrumb
	  $bc = $app->getPathway();
	  $bc->addItem(JText::_('COM_PROJECTS_HOME_PAGE'),'index.php?option=com_projects');

	  	// display
		return parent::display($cachable, $safeurlparams);
	}
	
}