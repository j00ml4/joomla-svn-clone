<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');
jimport('joomla.access.helper');

/**
 * User view level model.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @since		1.6
 */
class UsersModelLevel extends JModelAdmin
{
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	*/
	public function getTable($type = 'Viewlevel', $prefix = 'JTable', $config = array())
	{
		$return = JTable::getInstance($type, $prefix, $config);
		return $return;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		$result = parent::getItem($pk);

		// Convert the params field to an array.
		$result->rules = json_decode($result->rules);

		return $result;
	}

	/**
	 * Method to get the record form.
	 *
	 * @return	mixed	JForm object on success, false on failure.
	 * @since	1.6
	 */
	public function getForm()
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Get the form.
		$form = parent::getForm('com_users.level', 'level', array('control' => 'jform'));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to load the form data.
	 *
	 * @param	JForm	The form object.
	 * @throws	Exception if there is an error in the data load.
	 * @since	1.6
	 */
	protected function loadFormData(JForm $form)
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_users.edit.level.data', array());

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		} else {
			$form->bind($this->getItem());
		}
	}

	/**
	 * Override preprocessForm to load the user plugin group instead of content.
	 *
	 * @param	object	A form object.
	 * @throws	Exception if there is an error in the form event.
	 * @since	1.6
	 */
	protected function preprocessForm(JForm $form)
	{
		parent::preprocessForm($form, 'user');
	}
}