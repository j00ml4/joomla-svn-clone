<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_acl
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * @package		Joomla.Administrator
 * @subpackage	com_acl
 */
class AccessViewRoles extends JView
{
	/**
	 * Display the view
	 *
	 * @access	public
	 */
	function display($tpl = null)
	{
		$state		= $this->get( 'State' );
		$items		= $this->get( 'ExtendedItems' );
		$pagination	= $this->get( 'Pagination' );

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->assignRef( 'state',		$state );
		$this->assignRef( 'items',		$items );
		$this->assignRef( 'pagination', $pagination );

		$this->_setToolBar();
		parent::display($tpl);
	}

	/**
	 * Display the toolbar
	 */
	private function _setToolBar()
	{
		JToolBarHelper::title(JText::_('Access Control: Roles'));
		JToolBarHelper::custom('acl.edit', 'edit.png', 'edit_f2.png', 'Edit', true);
		JToolBarHelper::custom('acl.edit', 'new.png', 'new_f2.png', 'ACL New Rule', false);
		JToolBarHelper::deleteList('','acl.delete');
	}
}