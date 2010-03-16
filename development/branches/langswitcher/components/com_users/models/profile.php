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
jimport('joomla.utilities.utility');

/**
 * Profile model class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @version		1.0
 */
class UsersModelProfile extends JModelForm
{
	protected $_context = 'com_users';
	/**
	 * Method to auto-populate the model state.
	 *
	 * @since	1.6
	 */
	protected function _populateState($property = null, $default = null)
	{
		// Get the application object.
		$app	= &JFactory::getApplication();
		$user	= &JFactory::getUser();
		$params	= &$app->getParams('com_users');

		// Get the member id.
		$memberId = JRequest::getInt('member_id', $app->getUserState('com_users.edit.profile.id'));
		$memberId = !empty($memberId) ? $memberId : (int)$user->get('id');

		// Set the member id.
		$this->setState('member.id', $memberId);

		// Load the parameters.
		$this->setState('params', $params);
	}

	/**
	 * Method to get the profile form.
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
		$form = parent::getForm('profile', 'com_users.profile', array('array' => 'jform', 'event' => 'onPrepareForm'));

		// Check for an error.
		if (JError::isError($form)) {
			$this->setError($form->getMessage());
			return false;
		}

		// Get the dispatcher and load the users plugins.
		$dispatcher	= &JDispatcher::getInstance();
		JPluginHelper::importPlugin('user');

		// Trigger the form preparation event.
		$results = $dispatcher->trigger('onPrepareUserProfileForm', array($this->getState('member.id'), &$form));

		// Check for errors encountered while preparing the form.
		if (count($results) && in_array(false, $results, true)) {
			$this->setError($dispatcher->getError());
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the profile form data.
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
		$app	= &JFactory::getApplication();
		$false	= false;

		// Initialise the table with JUser.
		$table = &JUser::getTable('User', 'JTable');
		$data = new JUser($this->getState('member.id'));

		// Set the base user data.
		$data->email1 = $data->get('email');
		$data->email2 = $data->get('email');

		// Override the base user data with any data in the session.
		$temp = (array)$app->getUserState('com_users.edit.profile.data', array());
		foreach ($temp as $k => $v) {
			$data->$k = $v;
		}

		// Unset the passwords.
		unset($data->password1);
		unset($data->password2);

		// Get the dispatcher and load the users plugins.
		$dispatcher	= &JDispatcher::getInstance();
		JPluginHelper::importPlugin('users');

		// Trigger the data preparation event.
		$results = $dispatcher->trigger('onPrepareUserProfileData', array($this->getState('member.id'), &$data));

		// Check for errors encountered while preparing the data.
		if (count($results) && in_array(false, $results, true)) {
			$this->setError($dispatcher->getError());
			return $false;
		}

		return $data;
	}

	function &getProfile()
	{
		$false	= false;
		$data	= array();

		// Get the dispatcher and load the users plugins.
		$dispatcher	= &JDispatcher::getInstance();
		JPluginHelper::importPlugin('users');

		// Trigger the profile preparation event.
		$results = $dispatcher->trigger('onPrepareUserProfile', array($this->getState('member.id'), &$data));

		// Check for errors encountered while preparing the profile.
		if (count($results) && in_array(false, $results, true)) {
			$this->setError($dispatcher->getError());
			return $false;
		}

		return $data;
	}

	/**
	 * Method to check in a member.
	 *
	 * @access	public
	 * @param	integer		$memberId		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.0
	 */
	function checkin($memberId = null)
	{
		// Get the member id.
		$memberId = (!empty($memberId)) ? $memberId : (int)$this->getState('member.id');

		if ($memberId)
		{
			// Initialise the table with JUser.
			$table = JUser::getTable('User', 'JTable');

			// Get the current user object.
			$user = &JFactory::getUser();

			// Attempt to check the row in.
			if (!$table->checkin($memberId)) {
				$this->setError($table->getError());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to check out a member for editing.
	 *
	 * @access	public
	 * @param	integer		$memberId		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.0
	 */
	function checkout($memberId = null)
	{
		// Get the member id.
		$memberId = (!empty($memberId)) ? $memberId : (int)$this->getState('member.id');

		if ($memberId)
		{
			// Initialise the table with JUser.
			$table = JUser::getTable('User', 'JTable');

			// Get the current user object.
			$user = &JFactory::getUser();

			// Attempt to check the row out.
			if (!$table->checkout($user->get('id'), $memberId)) {
				$this->setError($table->getError());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to save the form data.
	 *
	 * @access	public
	 * @param	array		$data		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.0
	 */
	function save($data)
	{
		$memberId = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('member.id');

		// Initialise the table with JUser.
		JUser::getTable('User', 'JTable');
		$user = new JUser($memberId);

		// Prepare the data for the user object.
		$data['email']		= $data['email1'];
		$data['password']	= $data['password1'];

		// Bind the data.
		if (!$user->bind($data)) {
			$this->setError(JText::sprintf('USERS PROFILE BIND FAILED', $user->getError()));
			return false;
		}

		// Load the users plugin group.
		JPluginHelper::importPlugin('users');

		// Null the user groups so they don't get overwritten
		$user->groups	= null;

		// Store the data.
		if (!$user->save()) {
			$this->setError($user->getError());
			return false;
		}

		return $user->id;
	}
	/**
	 * Method to change the user language
	 *
	 * @access	public
	 * @return	mixed	JException on error|true
	 * @since	1.6
	 */
	function language()
	{
		$user = JFactory::getUser();
		$tag = $this->getState('language');
		if($user->id)
		{
			$user->setParam('language', $tag);
			// Save the user to the database.
			if (!$user->save(true)) {
				return new JException(JText::sprintf('USERS_USER_SAVE_FAILED', $user->getError()), 500);
			}
		}
		$config = JFactory::getConfig();
		$cookie_domain = $config->get('config.cookie_domain', '');
		$cookie_path = $config->get('config.cookie_path', '/');
		setcookie(JUtility::getHash('language'), $tag, time() + 365 * 86400, $cookie_path, $cookie_domain);
		return true;
	}
}
