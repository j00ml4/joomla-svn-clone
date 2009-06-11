<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * The HTML Menus Menu Item View.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @since		1.6
 */
class MenusViewItem extends JView
{
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$state		= $this->get('State');
		$item		= $this->get('Item');
		$form		= $this->get('Form');
		$types		= $this->get('TypeOptions');
		$modules	= $this->get('Modules');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$form->bind($item);

		$this->assignRef('state',	$state);
		$this->assignRef('item',	$item);
		$this->assignRef('form',	$form);
		$this->assignRef('types',	$types);
		$this->assignRef('modules',	$modules);

		parent::display($tpl);
		JRequest::setVar('hidemainmenu', true);
		$this->_setToolBar();
	}

	/**
	 * Build the default toolbar.
	 *
	 * @return	void
	 */
	protected function _setToolBar()
	{
		$user		= &JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));

		JToolBarHelper::title(JText::_($isNew ? 'Menus_Title_Add_Item' : 'Menus_Title_Edit_Item'));

		// If an existing item, can save to a copy.
		if (!$isNew) {
			JToolBarHelper::custom('item.save2copy', 'copy.png', 'copy_f2.png', 'JToolbar_Save_as_copy', false);
		}

		// If not checked out, can save the item.
		if ($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'))
		{
			JToolBarHelper::addNew('item.save2new', 'JToolbar_Save_and_new');
			JToolBarHelper::save('item.save');
			JToolBarHelper::apply('item.apply');
		}
		JToolBarHelper::cancel('item.cancel');
	}
}