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
		global  $option;
		$app=&JFactory::getApplication();
		
		// Initialize some variables
		$user		=& JFactory::getUser();
		$document	= &JFactory::getDocument();
		$uri 		= &JFactory::getURI();
		$pathway	= &$app->getPathway();
		$cparams =& $app->getParams();
		$model =& $this->getModel('mediagalleries');
		
		// Add default Style
		$document->addStyleSheet( URI_ASSETS. $cparams->get('style', 'default.css') );		
		
		
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
		$items		=& $this->get('data' );
		$total		=& $this->get('total');
		$pagination	=& $this->get('pagination');
		$category	=& $this->get('category');
		$state		=& $this->get('state');		
		
	
					
		// switch layout
		switch( $this->getLayout() ){		
			case 'compact':
				//$cparams->def('limit', 30);
				break;
				
			default:

						
				// Set some defaults if not set for general params
				
				// state
				$cparams->def('show_thumbnail', 1);
				$cparams->def('show_title', 1);
				$cparams->def('show_date', 1);		
				$cparams->def('show_author', 1);
				$cparams->def('show_views', 1);
				$cparams->def('show_rating', 1);
		

				break;	
		}
		
		
		// Title
		if(!empty($category)){
			$title = $category->title;
		}
		elseif(is_object($menu)){
			$title = $menu->name;
		}
		else{
			$title = JText::_('All Medias');
		}
		
		// Set some defaults if not set for general params
		$cparams->def('page_title', $title );
		$cparams->def('show_headings', 1);
		$cparams->def('date_format',	 JText::_('DATE_FORMAT_LC2') );			
		// filter	
		$cparams->def('filter_category', 1);
		$cparams->def('filter', 1);
		$cparams->def('filter_type', 1);
		// pagination
		$cparams->def('show_pagination', 2);
		$cparams->def('show_pagination_results', 1);
		$cparams->def('show_pagination_limit', 1);
		$cparams->def('show_feed_link', 1);

	
	
				
		// assign Vars
		//$this->assignRef('lists',		$lists);
		$this->category=$category;
		$this->items=$items;
		$this->pagination=$pagination;
		$this->user=$user;
		//$this->access=$access;				
		$this->total=$total;
		$this->action=$uri->toString();
		$this->params=$cparams;
		
		//Display
		parent::display($tpl);
    }



}
