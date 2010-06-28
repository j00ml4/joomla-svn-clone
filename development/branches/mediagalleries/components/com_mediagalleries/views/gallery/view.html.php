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
class MediagalleriesViewGalleries extends JView
{
	
    /**
     * Hellos view display method
     * @return void
     **/
    function display($tpl = null)
    {
		global  $option;
		$mainframe=&JFactory::getApplication();
		
		// Initialize some variables
		$user		=& JFactory::getUser();
		$document	= &JFactory::getDocument();
		$uri 		= &JFactory::getURI();
		$pathway	= &$mainframe->getPathway();
		$cparams =& $mainframe->getParams();
		$model =& $this->getModel('mediagalleries');
		
		// Add default Style
		$document->addStyleSheet( URI_ASSETS. $cparams->get('style', 'default.css') );		
		
		
		// Get the parameters of the active menu item
		$menus = &JSite::getMenu();
		$menu  = $menus->getActive();
		
		// Set Custom Limit
		if($cparams->get('limit') ){
			$limit = $mainframe->getUserStateFromRequest(
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

		// Add alternate feed link
		if($cparams->get('show_feed_link', 1) == 1)
		{
			$link	= '&view=category&id=&format=feed&limitstart=';
			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
			$document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', $attribs);
			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
			$document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', $attribs);
		}
		
		
		// If has Category
		if(!empty($category)){
			// Set page title per category
			$document->setTitle( $category->title. ' - '. $cparams->get( 'page_title'));
			// Prepare category description
			$category->description = JHTML::_('content.prepare', $category->description);			
			// Define image tag attributes
			if (isset( $category->image ) && $category->image != '')
			{
				$attribs['align']  = $category->image_position;
				$attribs['hspace'] = 6;
	
				// Use the static HTML library to build the image tag
				$category->image = JHTML::_('image', 'images/stories/'.$category->image, JText::_('Web Links'), $attribs);
			}	
			//set breadcrumbs
			if(is_object($menu) && $menu->query['view'] != 'category') {
				$pathway->addItem($category->title, '');
			}
		}		

		// Create a user access object for the user
		$access = new stdClass();
		$access->canEdit	= $user->authorize('com_content', 'edit', 'content', 'all');
		$access->canEditOwn	= $user->authorize('com_content', 'edit', 'content', 'own');
		$access->canPublish = $user->authorize('com_content', 'publish', 'content', 'all');
		
					
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
		$this->assignRef('category',	$category);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('user',		$user);
		$this->assignRef('access',		$access);				
		$this->assign('total',		$total);
		$this->assign('action', 	$uri->toString());
		$this->assignRef('params', $cparams);
		
		//Display
		parent::display($tpl);
    }



}
