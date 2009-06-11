<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.theartofjoomla.com
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * @package		Joomla.Administrator
 * @subpackage	com_content
 */
class ContentViewArticle extends JView
{
	/**
	 * Display the view
	 *
	 * @access	public
	 */
	function display($tpl = null)
	{
		$state	= $this->get('State');
		$item	= $this->get('Item');
		$form 	= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$form->bind($item);

		$this->assignRef('state',	$state);
		$this->assignRef('item',	$item);
		$this->assignRef('form',	$form);

		$this->_setToolbar();
		parent::display($tpl);
		JRequest::setVar('hidemainmenu', true);
	}

	/**
	 * Display the toolbar
	 *
	 * @access	private
	 */
	function _setToolbar()
	{
		$user		= &JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));

		JToolBarHelper::title(JText::_('Content_Page_'.($checkedOut ? 'View_Article' : ($isNew ? 'Add_Article' : 'Edit_Article'))), 'article.png');

		// If an existing item, can save to a copy.
		if (!$isNew) {
			JToolBarHelper::custom('article.save2copy', 'copy.png', 'copy_f2.png', 'Content_Toolbar_Save_To_Copy', false);
		}

		// If not checked out, can save the item.
		if (!$checkedOut) {
			JToolBarHelper::custom('article.save2new', 'new.png', 'new_f2.png', 'Content_Toolbar_Save_And_New', false);
			JToolBarHelper::save('article.save');
			JToolBarHelper::apply('article.apply');
		}
		JToolBarHelper::cancel('article.cancel');
	}
}