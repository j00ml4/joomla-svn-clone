<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.access.helper');

/**
 * Access Level model for Users.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @since		1.6
 */
class UsersModelLevel extends JModelForm
{
	/**
	 * Array of items for memory caching.
	 *
	 * @var		array
	 */
	protected $_items = array();

	/**
	 * Method to auto-populate the model state.
	 *
	 * @return	void
	 */
	protected function _populateState()
	{
		$app		= &JFactory::getApplication('administrator');
		$params		= &JComponentHelper::getParams('com_users');

		// Load the level state.
		if (!$levelId = (int)$app->getUserState('com_users.edit.level.id')) {
			$levelId = (int)JRequest::getInt('level_id');
		}
		$this->setState('level.id', $levelId);

		// Add the level id to the context to preserve sanity.
		$context = 'com_users.level.'.$levelId.'.';

		// Load the parameters.
		$this->setState('params', $params);
	}

	/**
	 * Method to get a level item.
	 *
	 * @param	integer	The id of the level to get.
	 * @return	mixed	Group data object on success, false on failure.
	 */
	public function &getItem($levelId = null)
	{
		// Initialize variables.
		$levelId = (!empty($levelId)) ? $levelId : (int)$this->getState('level.id');
		$false	= false;

		$item = & JAccessHelper::getAccessLevel((int)$levelId);

		if (count($item->getErrors())) {
			return $false;
		}

		return $item;
	}

	/**
	 * Method to get the group form.
	 *
	 * @return	mixed	JForm object on success, false on failure.
	 */
	public function getForm()
	{
		// Initialize variables.
		$app	= &JFactory::getApplication();

		// Get the form.
		$form = parent::getForm('level', 'com_users.level', array('array' => 'jform', 'event' => 'onPrepareForm'));

		// Check for an error.
		if (JError::isError($form)) {
			$this->setError($form->getMessage());
			return false;
		}

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_users.edit.level.data', array());

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		}

		return $form;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array	The form data.
	 * @return	boolean	True on success.
	 */
	public function save($data)
	{
		// Initialize variables.
		$levelId = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('level.id');

		// Are we dealing with a new access level?
		if ($levelId) {
			$isNew = false;
		} else {
			$isNew = true;
		}

		if ($isNew)
		{
			$db = &$this->getDbo();
			$db->setQuery(
				'SELECT `name`' .
				' FROM `#__access_sections`' .
				' WHERE `id` = '.(int) $data['section_id']
			);
			$section = $db->loadResult();

			// Use the AccessHelper class to register the new access level.
			$return = JAccessHelper::registerAccessLevel($data['title'], $section, $data['groups']);

			if (JError::isError($return))
			{
				$this->setError($return->getMessage());
				return false;
			}

			$this->setState('level.id', $return);
		}
		else
		{
			// Update the data as necessary and store the access level.
			$item = & $this->getItem($levelId);

			// Set the user groups.
			$item->setUserGroups($data['groups']);

			// Store the access level.
			if (!$item->store()) {
				$this->setError($item->getError());
				return false;
			}

			// Update the access level title.
			$db = &$this->getDbo();
			$db->setQuery(
				'UPDATE `#__viewlevels`' .
				' SET `title` = '.$db->Quote($data['title']) .
				' WHERE `id` = '.(int) $levelId
			);
			$db->query();

			if ($db->getErrorNum()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to delete levels.
	 *
	 * @param	array	An array of level ids.
	 * @return	boolean	Returns true on success, false on failure.
	 */
	public function delete($levelIds)
	{
		// Sanitize the ids.
		$levelIds = (array) $levelIds;
		JArrayHelper::toInteger($levelIds);

		// Get a database object.
		$db = &$this->getDbo();

		// Iterate the items to delete each one.
		foreach ($levelIds as $levelId)
		{
			$db->setQuery(
				'SELECT `section_id`' .
				' FROM `#__viewlevels`' .
				' WHERE `id` = '.(int) $levelId
			);
			$sectionId = $db->loadResult();

			$return = & JAccessHelper::removeAccessLevel((int)$levelId, (int)$sectionId);

			if (JError::isError($return)) {
				$this->setError($return->getMessage());
				return false;
			}
		}

		return true;
	}
}
