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
class mediagalleriesViewChannel extends JView
{
	/**
	 * display method of Media view
	 * @return void
	 */
	function display($tpl = null)
	{
		global $mainframe, $option;

		$db		=& JFactory::getDBO();
		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$model	=& $this->getModel();

		$lists = array();

		//get the data
		$item			=& $this->get('Data');

		// fail if checked out not by 'me'
		if ( $model->isCheckedOut( $user->get('id') ) ) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'COM_MEDIAGALLERIES_MEDIA_ITEM' ), $item->title );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Is new?
		if( !$item->id ){
			$video = JText::_('COM_MEDIAGALLERIES_SELECT_PREVIEW' );			
			$item->published = 1;
			$item->catid = 0;
		}else{
			$video = StreamallHelper::showMedia($item->url);
		}

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

		//clean media data
		JFilterOutput::objectHTMLSafe( $item, ENT_QUOTES, 'description' );
		
		
		// Assign References
		$this->assignRef('lists', 	$lists);
		$this->assignRef('item',	$item);
		$this->assignRef('video',	$video);

		//Display
		parent::display($tpl);
	}
	
}
