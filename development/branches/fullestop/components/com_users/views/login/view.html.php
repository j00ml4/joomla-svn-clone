<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Login view class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.5
 */
class UsersViewLogin extends JView
{
	/**
	 * Method to display the view.
	 *
	 * @param	string	The template file to include
	 * @since	1.5
	 */
	public function display($tpl = null)
	{
		// Get the view data.
		$user		= JFactory::getUser();
		$login		= $user->get('guest') ? true : false;
		$form		= $this->get('LoginForm');
		$state		= $this->get('State');
		$params		= $state->get('params');

		// Check for errors.
		if (count($errors = &$this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Push the data into the view.
		$this->assignRef('user',	$user);
		$this->assignRef('form',	$form);
		$this->assignRef('params',	$params);

		$this->prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 * @since	1.6
	 */
	protected function prepareDocument()
	{
		$app		= JFactory::getApplication();
		$menus		= JSite::getMenu();
		$user		= JFactory::getUser();
		$login		= $user->get('guest') ? true : false;
		$title 		= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', $login ? JText::_('COM_USERS_Login_Pathway_Login') : JText::_('COM_USERS_Login_Pathway_Logout'));
		}

		$title = $this->params->get('page_title', $this->params->get('page_heading'));
		if (empty($title)) {
			$title = htmlspecialchars_decode($app->getCfg('sitename'));
		}
		$this->document->setTitle($title);
	}
}