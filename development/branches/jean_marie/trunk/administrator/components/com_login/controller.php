<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

// No direct access.
defined('_JEXEC') or die;

jimport( 'joomla.application.component.controller' );

/**
 * Login Controller
 *
 * @package		Joomla.Administrator
 * @subpackage	com_login
 * @since		1.5
 */
class LoginController extends JController
{
	/**
	 * Method to display a view.
	 *
	 * @return	void
	 */
	public function display($cachable = false)
	{
		// Get the document object.
		$document = &JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName		= 'login';
		$vFormat	= $document->getType();

		// Get and render the view.
		if ($view = &$this->getView($vName, $vFormat))
		{
			// Get the model for the view.
			$model = &$this->getModel($vName);

			// Push the model into the view (as default).
			$view->setModel($model, true);
			$view->setLayout('default');

			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();
		}
	}

	/**
	 * Method to log in a user.
	 *
	 * @return	void
	 */
	public function login()
	{
		// Check for request forgeries.
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

		$app = &JFactory::getApplication();

		$model = &$this->getModel('login');
		$credentials = $model->getState('credentials');
		$return = $model->getState('return');

		$result = $app->login($credentials, array('action' => 'core.login.admin'));

		if (!JError::isError($result)) {
			$app->redirect($return);
		}

		parent::display();
	}

	/**
	 * Method to log out a user.
	 *
	 * @return	void
	 */
	public function logout()
	{
		$app = &JFactory::getApplication();

		$result = $app->logout();

		if (!JError::isError($result)) {
			$app->redirect('index.php?option=com_login');
		}

		parent::display();
	}
}