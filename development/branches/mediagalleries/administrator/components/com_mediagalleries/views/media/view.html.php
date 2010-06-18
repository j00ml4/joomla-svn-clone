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
class MediagalleriesViewMedia extends JView
{
	// new way
	protected $item;
	protected $action;
	protected $lists;
	protected $media;
	protected $folder;
	protected $user;
	protected $form;
	protected $state;
	
	/**
	 * display method of Media view
	 * 
	 * @return void
	 */
	public function display($tpl = null)
	{
		$document	=& JFactory::getDocument();
		$db			=& JFactory::getDBO();
		$uri 		=& JFactory::getURI();
		$model		=& $this->getModel();
		
		//get the data
		
		$this->user 	=& JFactory::getUser();
		$this->item		=& $this->get('Item');
		$this->form		=& $this->get('Form');
		$this->state	= $this->get('State');

		// PLG
		$plg_media=JPluginHelper::getPlugin('content', 'media');
		$this->media_params=$plg_media->params;
		// To folder
		// $this->folder= GetmediaParam('defaultdir');//does it work?
		$this->folder=$this->media_params['defaultdir'];
		
		// Is new?
		if( $this->item->id ){
			$this->media = $plg_media->addMedia($this->item->url);
		}
					
		// get the lists
		$this->lists =& $this->_buildLists($this->item);
		
		// Add the toolbar
		$this->addToolbar();
		
		//Display
		parent::display($tpl);
	}
	
	/**
	 * 
	 * @return array Lists 
	 */
	protected function &_buildLists(&$item)
	{
		global  $option;
		
		$mainframe=&JFactory::getApplication();
		
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
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar(){
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);		
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= MediagalleriesHelper::getActions($this->state->get('filter.category_id'), $this->item->id);

		// If not checked out, can save the item.
		if (!$checkedOut && $canDo->get('core.edit'))
		{
			JToolBarHelper::apply('media.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('media.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::addNew('media.save2new', 'JTOOLBAR_SAVE_AND_NEW');
		}
			// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolBarHelper::custom('media.save2copy', 'copy.png', 'copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id))  {
			JToolBarHelper::cancel('media.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('media.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
	}	
}
