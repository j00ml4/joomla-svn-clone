<?php
/**
 * @version		$Id: view.html.php 10206 2008-04-17 02:52:39Z instance $
 * @package		Joomla
 * @subpackage	Weblinks
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the WebLinks component
 *
 * @static
 * @package		Joomla
 * @subpackage	Weblinks
 * @since 1.0
 */
class MediagalleriesViewGallery extends JView
{
	
    /**
     * Hellos view display method
     * @return void
     **/
    function display($tpl = null)   
    {		
		$app=&JFactory::getApplication();
					
		// Initialize some variables
		$user		=& JFactory::getUser();
		$document	= &JFactory::getDocument();
		$uri 		= &JFactory::getURI();
		$pathway	= &$app->getPathway();
		$cparams =& $app->getParams();
		$model =& $this->getModel('gallery');
		
		
    	// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
    	
    	
		//Access Check
		$user	= &JFactory::getUser();
		$groups	= $user->authorisedLevels();
		
		// Get the parameters of the active menu item
		$menus = &JSite::getMenu();
		$menu  = $menus->getActive();		
		
		
		// Set Custom Limit
		if($cparams->get('limit') ){
			$limit = $app->getUserStateFromRequest(
				'gallery.list.limit', 'limit', 
				$cparams->get('limit'), 'int' );
				
			$model->setState('limit', $limit);
		}
		
		// Get some data from the model
		
		//$total		=& $this->get('total');
		
		$this->pagination=& $model->getPagination();
		$this->category  =& $model->getCategory();
		$this->items	 =& $model->getItems();
		$this->state	 =& $model->getState();
		
		$this->children= $model->getChildren();
		foreach($this->children as $children)
		{
			echo $children->title;
			echo "<br>";
		}
			
		// Title
		if(!empty($this->category)){
			$this->title = $this->category->title;
		}
		elseif(is_object($menu)){
			$this->title = $menu->title;
		}
		else{
			
			$title = JText::_($this->category->title);
		}	
				
		// assign Vars
		//$this->assignRef('lists',		$lists);
		
		//$this->access=$access;				
	
		$this->action=$uri->toString();
		$this->params=$cparams;
		
		//Display
		parent::display($tpl);
    }

}
