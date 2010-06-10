<?php
/**
 * Media View for mediagalleries Component
 * 
 * @package    		Joomla
 * @subpackage 	mediagalleries Suite
 * @link 			http://3den.org/joom/
 * @license	GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * Media View
 *
 * @package    		Joomla
 * @subpackage	mediagalleries Suite
 */
class mediagalleriesViewMedia extends JView
{
	/**
	 * display method of Media view
	 * @return void
	 */
	function display($tpl = null)
	{
		global $mainframe, $option;

		$document		=& JFactory::getDocument();
		$db		=& JFactory::getDBO();
		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$model	=& $this->getModel();
		
		//get the data
		$item			=& $this->get('Data');
		
		// Import Helpers
		include_once(PATH_HELPERS.'player.php');

		switch(JRequest::getVar('layout') ){
			case '_preview':
				if(!$item->id){
					JError::raiseError( 1, JText::_('COM_MEDIAGALLERIES_FIELD_RESOURCE_NOT_FOUND') );
				}
				$document->setTitle(JText::_('JGLOBAL_PREVIEW'));
				$document->addStyleSheet(URI_ASSETS.'preview.css');
				//$document->setBase(JUri::root());
				$video = PlayerHelper::play($item->url,  300, 300, 1);
				break;
			
			case 'form':
			default:
				// Is new?
				if( !$item->id ){
					$video = JText::_('COM_MEDIAGALLERIES_SELECT_MEDIA' );			
					$item->published = 1;
					$item->catid = 0;
				}else{
					
					$video = PlayerHelper::play($item->url);
				}
					
				// get the lists
				$lists =& $this->_buildLists($item);
				
				// fail if checked out not by 'me'
				if ( $model->isCheckedOut( $user->get('id') ) ) {
					$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'item' ), $item->title );
					$mainframe->redirect( 'index.php?option='. $option, $msg );
				}				
				break;				
		}

		
		// To folder
		$folder = GetmediaParam('defaultdir');
				
		//clean weblink data
		JFilterOutput::objectHTMLSafe( $item, ENT_QUOTES, 'description' );
		$action = JRoute::_('index.php?option=com_mediagalleries');
		
		// Assign References
		$this->assignRef('action', 	$action);		
		$this->assignRef('lists', 	$lists);
		$this->assignRef('item',	$item);
		$this->assignRef('video',	$video);
		$this->assignRef('folder',	$folder);

		//Display
		parent::display($tpl);
	}
	
	/**
	 * 
	 * @return array Lists 
	 */
	function &_buildLists(&$item)
	{
		global $mainframe, $option;
		
		$lists = array();
		
		// build the html select list for ordering
		$query = 'SELECT ordering AS value, title AS text'
			. ' FROM #__mediagalleries'
			. ' WHERE catid = ' . (int) $item->catid
			. ' ORDER BY ordering';
		$lists['ordering'] 	= JHTML::_('list.specificordering',  $item, $item->id, $query );

		// build list of categories
		$lists['catid'] 			= JHTML::_('list.category',  'catid', $option, intval( $item->catid ) );

		// build the html select list
		$lists['published'] 	= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $item->published );

		return $lists;
	}
	
}
