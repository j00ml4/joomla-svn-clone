<?php
/**
 * Comments View for ... Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license        GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * Comments View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class MediagalleriesViewMedia extends JView
{
    /**
     * Hellos view display method
     * @return void
     **/
    function display($tpl = null)
    {
		global $option;
		$mainframe=&JFactory::getApplication();
		
		// Initialize some variables
		$user		=& JFactory::getUser();
		$document	= &JFactory::getDocument();
		$uri 		= &JFactory::getURI();
		$pathway	= &$mainframe->getPathway();
		$model	=& $this->getModel();
		$cparams =& $mainframe->getParams(); 

		// Add default Style
		$document->addStyleSheet( URI_ASSETS. $cparams->get('style', 'default.css') );		
		
		// Get some data from the model
		$item	=& $this->get('Data');		
		// Build lists
		$lists 	=& $this->_buildLists($item);
		$links	=& $this->_buildLinks($item);
		
		// switch layout
		switch( JRequest::getVar('layout') ){
			/**
			 * Media Form Layout 
			 */
			case 'form2':
			case 'form':
				// fail if checked out not by 'me'
				if ( $model->isCheckedOut( $user->get('id') ) or !JRequest::getInt('Itemid') ) {
					$msg = JText::_( 'YOU HAVE NO ACCESS TO THIS PAGE' );
					$mainframe->redirect( 'index.php?option='. $option, $msg, 'error' );
				}
			
				// Is new?
				if( !$item->id ){
					$item->published = 1;
					$item->catid = 0;
					$title = JText::_('New media');
					$item->added = '';
				}else{
					$title = JText::_('Edit') .': '. $item->title; JText::_('New');
					$pathway->addItem( $item->category );					
				}		
				
				// Set page title
				$document->setTitle($title);
				$pathway->addItem($title, '');

				//clean weblink data
				JFilterOutput::objectHTMLSafe( $item );//, ENT_QUOTES, 'description' );
				
				//Action
				$action = $uri->toString();
		
				// Assign Referencesss
				$this->assignRef('action', $action);
				break;
			
			/**
			 * 
			 * Default Play Media Layout
			 */	
			default:
				// dont exists?
				if( empty($item->id) ){
					$msg = JText::_( 'PAGE COULD NOT BE FOUND' );
					$mainframe->redirect( 'index.php?option='. $option, $msg, 'error' );
				}
				
				//  Increment views count
				$model->hit();
				$item->hits++;

				// get video			
				$video = PlayerHelper::play($item->url, 
					$cparams->get('width'), 
					$cparams->get('height'), 
					$cparams->get('autostart'));
				$embed = PlayerHelper::safeStr($video); 					
			
				// Comments
				$comments = $this->_buildCommentList($item);
				$comments .= $this->_buildCommentForm($item);

				// Author	
				if($item->author==''){ $item->author = JText::_('Guest'); }  
				
				// Date
				$date =& JFactory::getDate($item->added);
				$item->added = $date->toFormat( JText::_('DATE_FORMAT_LC2') ) ;	
				
				// links
				$links['cat'] = JRoute::_('index.php?option=com_mediagalleries&catid='.$item->catid);
				
				
				// Set page title
				$document->setTitle($item->title);
				$pathway->addItem($item->category, $links['cat']);
				$pathway->addItem($item->alias, '');
						
				//clean data
				JFilterOutput::objectHTMLSafe( $item );//, ENT_QUOTES, 'description' );
					
				// Assign References
				$this->assignRef('embed',	$embed);
				$this->assignRef('comments',	$comments);				
				$this->assignRef('video',	$video);
				break;	
		}
		
		// Assign General References 
		$this->assignRef('params', $cparams);
		$this->assignRef('lists', 	$lists);
		$this->assignRef('links', 	$links);
		$this->assignRef('item',	$item);
		
		// Display
		parent::display($tpl);
    }
	
	/**
	 * 
	 * @return array Lists 
	 */
	function &_buildLists(&$item){
		global $option, $mainframe;
				
		// Build lists
		$lists = array();
		
			// build list of categories
			$lists['catid'] = JHTML::_('list.category',  'catid', $option, intval( $item->catid ) );
			// build the html select list
			$lists['published'] 	= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $item->published );
		return $lists;
	}
	
	/**
	 * 
	 * @return array Lists 
	 */
	function &_buildLinks(&$item){
		global $option, $mainframe;
				
		// Build lists
		$links = array();
		if(!empty($item->id)){
			// build list of categories
			$links['media'] = JRoute::_('index.php?option=com_mediagalleries&view=media&layout=default&id='. $item->id );
		}
		return $links;
	}	
	/**
	 * 
	 * @return 
	 * @param $item Object
	 */
	function _buildCommentList(&$item){
		global $mainframe;
		
		$args = array('list', &$item); 
		$res = $mainframe->triggerEvent('onGetComments', $args );

		return @$res[0];
	}
	
	/**
	 * 
	 * @return 
	 * @param $item Object
	 */
	function _buildCommentForm(&$item){
		global $mainframe;
		
		$args = array('form', &$item ) ;
		$res = $mainframe->triggerEvent('onGetComments', $args);
		
		return @$res[0];
	}
}