<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit a plugin.
 *
 * @package		Joomla.Administrator
 * @subpackage	Plugins
 * @since		1.5
 */
class PluginsViewPlugin extends JView
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$state		= $this->get('State');
		$item		= $this->get('Item');
		$itemForm	= $this->get('Form');
		$paramsForm	= $this->get('ParamsForm');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Bind the record to the form.
		$itemForm->bind($item);
		$paramsForm->bind($item->params);

		$this->assignRef('state',		$state);
		$this->assignRef('item',		$item);
		$this->assignRef('form',		$itemForm);
		$this->assignRef('paramsform',	$paramsForm);

		$this->_setToolbar();
		parent::display($tpl);
	}

	/**
	 * Setup the Toolbar
	 */
	protected function _setToolbar()
	{
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$canDo		= PluginsHelper::getActions();

		JToolBarHelper::title(JText::_('COM_PLUGINS_MANAGER_PLUGIN'), 'plugin');

		// If not checked out, can save the item.
		if ($canDo->get('core.edit'))
		{
			JToolBarHelper::apply('plugin.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('plugin.save', 'JTOOLBAR_SAVE');
		}
		JToolBarHelper::cancel('plugin.cancel', 'JTOOLBAR_CLOSE');
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.plugins.edit','JTOOLBAR_HELP');
	}
}