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
 * Hybrid view to display or edit a newsfeed.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_social
 * @since		1.6
 */
class SocialViewComment extends JView
{
	protected $addressList;
	protected $item;
	protected $form;
	protected $nameList;
	protected $state;
	protected $threadList;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->thread	= $this->get('Thread');
		$this->form = $this->get('Form');

		if ($this->getLayout() == 'edit') {
			
		} else {
			$this->addressList	= $this->get('ListByIP');
			$this->nameList		= $this->get('ListByName');
			$this->threadList	= $this->get('ListByThread');
		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		if ($this->form) {
			// Bind the record to the form.
			$this->form->bind($this->item);
		}

		$this->setToolbar();
		parent::display($tpl);
	}

	/**
	 * Setup the Toolbar.
	 */
	protected function setToolbar()
	{
		JRequest::setVar('hidemainmenu', true);

		if ($this->getLayout() == 'edit') {
			JRequest::setVar('hidemainmenu', true);
			JToolBarHelper::title(JText::_('COM_SOCIAL_EDIT_COMMENT'));
			JToolBarHelper::apply('comment.apply', 'JToolbar_Apply');
			JToolBarHelper::save('comment.save', 'JToolbar_Save');
			JToolBarHelper::cancel('comment.cancel');
		} else {
			JToolBarHelper::title(JText::_('COM_SOCIAL_MODERATE_COMMENT'));
			JToolBarHelper::custom('comment.edit', 'edit.png', 'edit_f2.png', 'JToolbar_Edit', false);
			JToolBarHelper::cancel('comment.cancel');
		}
	}

	function getContentRoute($url)
	{
		static $router;

		// Only get the router once.
		if (!is_object($router)) {
			// Import dependencies.
			jimport('joomla.application.router');
			require_once JPATH_SITE.'/includes/application.php';

			// Get the site router.
			$config	= JFactory::getConfig();
			$router = JRouter::getInstance('site');
			$router->setMode($config->get('sef', 1));
		}

		// Build the route.
		$uri	= $router->build($url);
		$route	= $uri->toString(array('path', 'query', 'fragment'));

		// Strip out the base portion of the route.
		$route = str_replace('administrator/', '', $route);

		return $route;
	}
}