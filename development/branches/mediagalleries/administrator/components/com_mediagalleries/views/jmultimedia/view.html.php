<?php
/**
 * JMultimedia View for Hello World Component
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
 * JMultimedia View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class JMultimediaViewJMultimedia extends JView
{
    /**
     * Hellos view display method
     * @return void
     **/
    function display($tpl = null)
    {
    	global $mainframe, $option;

		// Define
		$db		=& JFactory::getDBO();
		$uri	=& JFactory::getURI();

		$filter_state		= $mainframe->getUserStateFromRequest( $option.'filter_state', 'filter_state', '', 'word' );
		$filter_catid		= $mainframe->getUserStateFromRequest( $option.'filter_catid', 'catid', 0, 'int' );
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$search				= $mainframe->getUserStateFromRequest( $option.'search',			'search',			'',				'string' );
		$search				= JString::strtolower( $search );

		// Get data from the model
		$items		= & $this->get( 'Data');
		$total		= & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );

		// build list of categories
		$javascript 	= 'onchange="document.adminForm.submit();"';
		$lists['catid'] = JHTML::_('list.category',  'catid', $option, intval( $filter_catid ), $javascript );
		$lists['state']	= JHTML::_('grid.state',  $filter_state );
		
		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['search']= $search;

		$action = JRoute::_('index.php?option=com_jmultimedia');
		$this->assignRef('action', 	$action);		
		
		// Assign References
		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		

		// Display
		parent::display($tpl);
    }
	
}