<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Categories Component Category Model
 *
 * @package		Joomla.Administrator
 * @subpackage	com_categories
 * @since		1.6
 */
class CategoriesModelCategory extends JModelAdmin
{
	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		return $user->authorise('core.delete', $record->extension.'.category.'.(int) $record->id);
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		return $user->authorise('core.edit.state', $record->extension.'.category.'.(int) $record->id);
	}

	/**
	 * Returns a Table object, always creating it
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	*/
	public function getTable($type = 'Category', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');

		// Load the User state.
		if (!($pk = (int) $app->getUserState('com_categories.edit.category.id'))) {
			$pk = (int) JRequest::getInt('item_id');
		}
		$this->setState('category.id', $pk);

		if (!($parentId = $app->getUserState('com_categories.edit.category.parent_id'))) {
			$parentId = JRequest::getInt('parent_id');
		}
		$this->setState('category.parent_id', $parentId);

		if (!($extension = $app->getUserState('com_categories.edit.category.extension'))) {
			$extension = JRequest::getCmd('extension', 'com_content');
		}
		$this->setState('category.extension', $extension);
		$parts = explode('.',$extension);
		// extract the component name
		$this->setState('category.component', $parts[0]);
		// extract the optional section name
		$this->setState('category.section', (count($parts)>1)?$parts[1]:null);

		// Load the parameters.
		$params	= JComponentHelper::getParams('com_categories');
		$this->setState('params', $params);
	}

	/**
	 * Method to get a category.
	 *
	 * @param	integer	An optional id of the object to get, otherwise the id from the model state is used.
	 * @return	mixed	Category data object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if ($result = parent::getItem($pk)) {
			// Prime required properties.
			if (empty($result->id)) {
				$result->parent_id	= $this->getState('category.parent_id');
				$result->extension	= $this->getState('category.extension');
			}

			// Convert the metadata field to an array.
			$registry = new JRegistry();
			$registry->loadJSON($result->metadata);
			$result->metadata = $registry->toArray();
		}

		return $result;
	}

	/**
	 * Method to get the row form.
	 *
	 * @return	mixed	JForm object on success, false on failure.
	 * @since	1.6
	 */
	public function getForm()
	{
		// Initialise variables.
		$extension	= $this->getState('category.extension');

		// Get the form.
		$form = parent::getForm('com_categories.category'.$extension, 'category', array('control' => 'jform'));
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
		$data = JFactory::getApplication()->getUserState('com_categories.edit.category.data', array());

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		} else {
			$form->bind($this->getItem());
		}
	}

	/**
	 * @param	object	A form object.
	 *
	 * @throws	Exception if there is an error loading the form.
	 * @since	1.6
	 */
	protected function preprocessForm($form)
	{
		jimport('joomla.filesystem.path');

		// Initialise variables.
		$lang		= JFactory::getLanguage();
		$extension	= $this->getState('category.extension');
		$component	= $this->getState('category.component');
		$section	= $this->getState('category.section');

		// Get the component form if it exists
		jimport('joomla.filesystem.path');
		$name = 'category'.($section ? ('.'.$section):'');
		$path = JPath::clean(JPATH_ADMINISTRATOR."/components/$component/$name.xml");

		if (file_exists($path)) {
			$lang->load($component, JPATH_BASE, null, false, false);
			$lang->load($component, JPATH_BASE, $lang->getDefault(), false, false);

			if (!$form->loadFile($path, false)) {
				throw new Exception(JText::_('JModelForm_Error_loadFile_failed'));
			}
		}

		// Try to find the component helper.
		$eName	= str_replace('com_', '', $component);
		$path	= JPath::clean(JPATH_ADMINISTRATOR."/components/$component/helpers/category.php");

		if (file_exists($path)) {
			require_once $path;
			$cName	= ucfirst($eName).ucfirst($section).'HelperCategory';

			if (class_exists($cName) && is_callable(array($cName, 'onPrepareForm'))) {
					$lang->load($component, JPATH_BASE, null, false, false)
				||	$lang->load($component, JPATH_BASE . '/components/' . $component, null, false, false)
				||	$lang->load($component, JPATH_BASE, $lang->getDefault(), false, false)
				||	$lang->load($component, JPATH_BASE . '/components/' . $component, $lang->getDefault(), false, false);
				call_user_func_array(array($cName, 'onPrepareForm'), array(&$form));

				// Check for an error.
				if (JError::isError($form)) {
					$this->setError($form->getMessage());
					return false;
				}
			}
		}

		// Set the access control rules field component value.
		$form->setFieldAttribute('rules', 'component', $component);
		$form->setFieldAttribute('rules', 'section', $name);

		// Trigger the default form events.
		parent::preprocessForm($form);
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array	The form data.
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function save($data)
	{
		$pk		= (!empty($data['id'])) ? $data['id'] : (int)$this->getState('category.id');
		$isNew	= true;

		// Get a row instance.
		$table = &$this->getTable();

		// Load the row if saving an existing category.
		if ($pk > 0) {
			$table->load($pk);
			$isNew = false;
		}

		// Set the new parent id if set.
		if ($table->parent_id != $data['parent_id']) {
			$table->setLocation($data['parent_id'], 'last-child');
		}

		// Bind the data.
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}

		// Bind the rules.
		if (isset($data['rules'])) {
			$rules = new JRules($data['rules']);
			$table->setRules($rules);
		}

		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}

		// Rebuild the tree path.
		if (!$table->rebuildPath($table->id)) {
			$this->setError($table->getError());
			return false;
		}

		$this->setState('category.id', $table->id);

		return true;
	}

	/**
	 * Method rebuild the entire nested set tree.
	 *
	 * @return	boolean	False on failure or error, true otherwise.
	 * @since	1.6
	 */
	public function rebuild()
	{
		// Get an instance of the table obejct.
		$table = $this->getTable();

		if (!$table->rebuild()) {
			$this->setError($table->getError());
			return false;
		}

		return true;
	}

	/**
	 * Method to perform batch operations on a category or a set of categories.
	 *
	 * @param	array	An array of commands to perform.
	 * @param	array	An array of category ids.
	 * @return	boolean	Returns true on success, false on failure.
	 * @since	1.6
	 */
	function batch($commands, $pks)
	{
		// Sanitize user ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true)) {
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks)) {
			$this->setError(JText::_('JError_No_items_selected'));
			return false;
		}

		$done = false;

		if (!empty($commands['assetgroup_id'])) {
			if (!$this->batchAccess($commands['assetgroup_id'], $pks)) {
				return false;
			}
			$done = true;
		}

		if (!empty($commands['category_id'])) {
			$cmd = JArrayHelper::getValue($commands, 'move_copy', 'c');

			if ($cmd == 'c' && !$this->batchCopy($commands['category_id'], $pks)) {
				return false;
			} else if ($cmd == 'm' && !$this->batchMove($commands['category_id'], $pks)) {
				return false;
			}
			$done = true;
		}

		if (!$done) {
			$this->setError('Categories_Error_Insufficient_batch_information');
			return false;
		}

		return true;
	}

	/**
	 * Batch access level changes for a group of rows.
	 *
	 * @param	int		The new value matching an Asset Group ID.
	 * @param	array	An array of row IDs.
	 * @return	booelan	True if successful, false otherwise and internal error is set.
	 * @since	1.6
	 */
	protected function batchAccess($value, $pks)
	{
		$table = $this->getTable();
		foreach ($pks as $pk) {
			$table->reset();
			$table->load($pk);
			$table->access = (int) $value;
			if (!$table->store()) {
				$this->setError($table->getError());
				return false;
			}
		}

		return true;
	}

	/**
	 * Batch move categories to a new parent.
	 *
	 * @param	int		The new category or sub-category.
	 * @param	array	An array of row IDs.
	 * @return	booelan	True if successful, false otherwise and internal error is set.
	 * @since	1.6
	 */
	protected function batchMove($value, $pks)
	{
	}

	/**
	 * Batch copy categories to a new parent.
	 *
	 * @param	int		The new category or sub-category.
	 * @param	array	An array of row IDs.
	 * @return	booelan	True if successful, false otherwise and internal error is set.
	 * @since	1.6
	 */
	protected function batchCopy($value, $pks)
	{
	}
}
