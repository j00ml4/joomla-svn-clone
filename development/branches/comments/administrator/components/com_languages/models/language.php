<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Languages Component Language Model
 *
 * @package		Joomla.Administrator
 * @subpackage	com_languages
 * @since		1.5
 */
class LanguagesModelLanguage extends JModelAdmin
{
	/**
	 * Override to get the table
	 */
	public function getTable()
	{
		return JTable::getInstance('Language');
	}

	/**
	 * Method to get the group form.
	 *
	 * @return	mixed	JForm object on success, false on failure.
	 * @since	1.0
	 */
	public function getForm()
	{
		// Initialise variables.
		$app	= &JFactory::getApplication();

		// Get the form.
		$form = parent::getForm('com_languages.language', 'language', array('control' => 'jform'));

		// Check for an error.
		if (JError::isError($form)) {
			$this->setError($form->getMessage());
			return false;
		}

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_languages.edit.language.data', array());

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		}

		return $form;
	}
}