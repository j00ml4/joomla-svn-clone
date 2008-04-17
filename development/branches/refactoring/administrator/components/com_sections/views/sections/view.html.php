<?php
/**
* @version		$Id: $
* @package		Joomla
* @subpackage	Sections
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Sections component
 *
 * @static
 * @package		Joomla
 * @subpackage	Sections
 * @since 1.0
 */
class SectionsViewSections extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;

		// get parameters from the URL or submitted form
		$scope 		= JRequest::getCmd( 'scope' );

		$db					=& JFactory::getDBO();
		$user				=& JFactory::getUser();

		// Set toolbar items for the page
		JToolBarHelper::title( JText::_( 'Section Manager' ), 'sections.png' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::customX( 'copyselect', 'copy.png', 'copy_f2.png', 'Copy', true );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::help( 'screen.sections' );

		// Get data from the model
		$rows		= & $this->get( 'Data');
		$pagination = & $this->get( 'Pagination' );
		$filter		= & $this->get( 'Filter');

		$this->assignRef('user',		$user);
		$this->assignRef('scope',		$scope);
		$this->assignRef('rows',		$rows);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('filter',		$filter);

		parent::display($tpl);
	}
}