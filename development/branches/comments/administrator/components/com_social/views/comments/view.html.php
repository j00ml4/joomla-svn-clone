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
 * View class for a list of comments.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_social
 * @since		1.6
 */
class SocialViewComments extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		parent::display($tpl);
		$this->setToolbar();
	}

	function getContentRoute($url)
	{
		static $router;

		// Only get the router once.
		if (!is_object($router))
		{
			// Import dependencies.
			jimport('joomla.application.router');
			require_once(JPATH_SITE.DS.'includes'.DS.'application.php');

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

	/**
	 * Setup the Toolbar.
	 */
	protected function setToolbar()
	{
		$state	= $this->get('State');
		$canDo	= SocialHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title('Comments: '.JText::_('COM_SOCIAL_MODERATE_COMMENTS_TITLE'), 'logo');

		$toolbar = JToolBar::getInstance('toolbar');
		$toolbar->appendButton('Standard', 'save', 'COM_SOCIAL_MODERATE', 'comment.moderate', false, false);

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_social');
		}
		JToolBarHelper::help('screen.comments','JTOOLBAR_HELP');
	}
}