<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');
jimport('joomla.plugin.helper');

/**
 * Registration model class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @version		1.0
 */
class UsersModelRegistration extends JModelForm
{
	/**
	 * Method to auto-populate the model state.
	 *
	 * @since	1.6
	 */
	protected function _populateState()
	{
		// Get the application object.
		$app	= &JFactory::getApplication();
		$params	= &$app->getParams('com_users');

		// Load the parameters.
		$this->setState('params', $params);
	}

	/**
	 * Method to get the registration form.
	 *
	 * The base form is loaded from XML and then an event is fired
	 * for users plugins to extend the form with extra fields.
	 *
	 * @access	public
	 * @return	mixed		JForm object on success, false on failure.
	 * @since	1.0
	 */
	function getForm()
	{
		// Get the form.
		$form = parent::getForm('registration', 'com_users.registration', array('array' => 'jform', 'event' => 'onPrepareForm'));

		// Check for an error.
		if (JError::isError($form)) {
			$this->setError($form->getMessage());
			return false;
		}

		// Get the dispatcher and load the users plugins.
		$dispatcher	= &JDispatcher::getInstance();
		JPluginHelper::importPlugin('users');

		// Trigger the form preparation event.
		$results = $dispatcher->trigger('onPrepareUserRegistrationForm', array(&$form));

		// Check for errors encountered while preparing the form.
		if (count($results) && in_array(false, $results, true)) {
			$this->setError($dispatcher->getError());
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the registration form data.
	 *
	 * The base form data is loaded and then an event is fired
	 * for users plugins to extend the data.
	 *
	 * @access	public
	 * @return	mixed		Data object on success, false on failure.
	 * @since	1.0
	 */
	function &getData()
	{
		$false	= false;
		$data	= new stdClass();
		$app	= &JFactory::getApplication();
		$params	= &JComponentHelper::getParams('com_users');

		// Override the base user data with any data in the session.
		$temp = (array)$app->getUserState('com_users.registration.data', array());
		foreach ($temp as $k => $v) {
			$data->$k = $v;
		}

		// Get the groups the user should be added to after registration.
		$data->groups = isset($data->groups) ? array_unique($data->groups) : array();

		// Get the default new user group, Registered if not specified.
		$system	= $params->get('new_usertype', 2);
		$data->usertype = $system;

		// TODO: Not sure we need all this stuff anymore. Just need to add the group to the list and we are golden.
		// Handle the system default group.
		if (!in_array($system, $data->groups)) {
			// Add the system group to the first position.
			array_unshift($data->groups, $system);
		} else {
			// Make sure the system group is the first item.
			unset($data->groups[array_search($system, $data->groups)]);
			array_unshift($data->groups, $system);
		}

		// Unset the passwords.
		unset($data->password1);
		unset($data->password2);

		// Get the dispatcher and load the users plugins.
		$dispatcher	= &JDispatcher::getInstance();
		JPluginHelper::importPlugin('users');

		// Trigger the data preparation event.
		$results = $dispatcher->trigger('onPrepareUserRegistrationData', array(&$data));

		// Check for errors encountered while preparing the data.
		if (count($results) && in_array(false, $results, true)) {
			$this->setError($dispatcher->getError());
			return $false;
		}

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @access	public
	 * @param	array		$data		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.0
	 */
	function register($temp)
	{
		$config = &JFactory::getConfig();
		$params = &JComponentHelper::getParams('com_users');

		// Initialise the table with JUser.
		JUser::getTable('User', 'JTable');
		$user = new JUser();
		$data = (array)$this->getData();

		// Merge in the registration data.
		foreach ($data as $k => $v) {
			$temp[$k] = $v;
		}

		$data = $temp;

		// Prepare the data for the user object.
		$data['email']		= $data['email1'];
		$data['password']	= $data['password1'];

		// Check if the user needs to activate their account.
		if ($params->get('useractivation')) {
			jimport('joomla.user.helper');
			$data['activation'] = JUtility::getHash(JUserHelper::genRandomPassword());
			$data['block'] = 1;
		}

		// Bind the data.
		if (!$user->bind($data)) {
			$this->setError(JText::sprintf('USERS_REGISTRATION_BIND_FAILED', $user->getError()));
			return false;
		}

		// Load the users plugin group.
		JPluginHelper::importPlugin('users');

		// Store the data.
		if (!$user->save()) {
			$this->setError($user->getError());
			return false;
		}

		// Compile the notification mail values.
		$data = $user->getProperties();
		$data['fromname'] = $config->getValue('fromname');
		$data['mailfrom'] = $config->getValue('mailfrom');
		$data['sitename'] = $config->getValue('sitename');

		// Handle account activation/confirmation e-mails.
		if ($params->get('useractivation'))
		{
			// Set the link to activate the user account.
			$uri = &JURI::getInstance();
			$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
			$data['activate'] = $base.JRoute::_('index.php?option=com_users&task=registration.activate&token='.$data['activation'], false);

			// Get the registration activation e-mail.
			if ($config->getValue('admin_approval'))
			{
				// Get the account verification e-mail.
				$message = 'com_users.registration.verify';
			}
			else
			{
				// Get the registration activation e-mail.
				$message = 'com_users.registration.activate';
			}
		}
		else
		{
			// Get the registration confirmation e-mail.
			$message = 'com_users.registration.confirm';
		}

		// Load the message template and bind the data.
		jimport('joomla.utilities.simpletemplate');
		$template = JxSimpleTemplate::getInstance($message);
		$template->bind($data);

		// Send the registration e-mail.
		$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $template->getTitle(), $template->getBody());

		// Check for an error.
		if ($return !== true) {
			$this->setError(JText::_('USERS_REGISTRATION_SEND_MAIL_FAILED'));
			return false;
		}

		return $user->id;
	}

	/**
	 * Method to activate a user account.
	 *
	 * @access	public
	 * @param	string		$token		The activation token.
	 * @param	integer		$block		Value for the block field in the users table. Default = 0
	 * @param	boolean		$isAdmin	True if an admin must approve the registration. Default = false
	 * @return	boolean		True on success, false on failure.
	 * @since	1.0
	 */
	function activate($token, $block=0, $isAdmin=false)
	{
		$config = &JFactory::getConfig();
		$params = &JComponentHelper::getParams('com_users');

		// Get the user id based on the token.
		$this->_db->setQuery(
			'SELECT `id` FROM `#__users`' .
			' WHERE `activation` = '.$this->_db->Quote($token) .
			' AND `block` = 1' .
			' AND `lastvisitDate` = '.$this->_db->Quote($this->_db->getNullDate())
		);
		$userId = (int)$this->_db->loadResult();

		// Check for a valid user id.
		if (!$userId) {
			$this->setError(JText::_('USERS_ACTIVATION_TOKEN_NOT_FOUND'));
			return false;
		}

		// Load the users plugin group.
		JPluginHelper::importPlugin('users');

		// Activate the user.
		$user = &JFactory::getUser($userId);
		$user->set('block', $block);

		if ($config->getValue('admin_approval'))
		{
			if ($isAdmin === false)
			{
				$token = JUtility::getHash(JUserHelper::genRandomPassword());
				$user->set('activation', $this->_db->Quote($token));
				$user->setParam('admin_approval', 1);
			}
			else
			{
				$user->set('activation', '');
			}
		}
		else
		{
			$user->set('activation', '');
		}

		// Store the user object.
		if (!$user->save()) {
			$this->setError($user->getError());
			return false;
		}

		// Compile the notification mail values.
		$data = $user->getProperties();
		$data['fromname'] = $config->getValue('fromname');
		$data['mailfrom'] = $config->getValue('mailfrom');
		$data['sitename'] = $config->getValue('sitename');

		if ($isAdmin === false)
		{
			$data['activate'] = $base.JRoute::_('index.php?option=com_users&task=registration.activate&token='.$token, false);

			//get all administrators.
			$gid = ($config->getValue('admin_approval_who') == 0) ? array(25, 24) : array(25);

			$query = 'SELECT name, email, sendEmail' .
						' FROM #__users' .
						' WHERE gid IN ('. implode(',', $gid) .')' .
						' AND sendEmail=1';

			$db->setQuery( $query );
			$rows = $db->loadObjectList();

			// Set data for all administrators
			$count = 0;
			$data['email'] = '';
			foreach( $rows as $row )
			{
				$data['email'] .= $row->email;
				$count++;
				if ($count < count($rows))
					$data['email'] .= ';';
			}

			// Get the admin registration activation e-mail.
			$message = 'com_users.registration.admin.activate';
		}
		else
		{
			// Get the registration confirmation e-mail.
			$message = 'com_users.registration.confirm';
		}

		// Load the message template and bind the data.
		jimport('joomla.utilities.simpletemplate');
		$template = JxSimpleTemplate::getInstance();
		$template->bind($data);

		// Send the registration e-mail.
		$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $template->getTitle(), $template->getBody());

		// Check for an error.
		if ($return !== true) {
			$this->setError(JText::_('USERS_ACTIVATION_SEND_MAIL_FAILED'));
			return false;
		}

		return true;
	}
}