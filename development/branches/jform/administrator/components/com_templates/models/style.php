<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');

/**
 * Template style model.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_templates
 * @since		1.6
 */
class TemplatesModelStyle extends JModelForm
{
	/**
	 * Item cache.
	 */
	private $_cache = array();

	/**
	 * Method to auto-populate the model state.
	 */
	protected function _populateState()
	{
		$app = JFactory::getApplication('administrator');

		// Load the User state.
		if (!($pk = (int) $app->getUserState('com_templates.edit.style.id'))) {
			$pk = (int) JRequest::getInt('id');
		}
		$this->setState('style.id', $pk);

		// Load the parameters.
		$params	= JComponentHelper::getParams('com_templates');
		$this->setState('params', $params);
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 */
	protected function _prepareTable(&$table)
	{
	}

	/**
	 * @param	object	A form object.
	 *
	 * @return	mixed	True if successful.
	 * @throws	Exception if there is an error loading the form.
	 * @since	1.6
	 */
	protected function addForms($form)
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		// Initialise variables.
		$clientId	= $this->getState('item.client_id');
		$template	= $this->getState('item.template');
		$lang		= JFactory::getLanguage();
		$client		= JApplicationHelper::getClientInfo($clientId);
		$formFile	= JPath::clean($client->path.'/templates/'.$template.'/templateDetails.xml');

		// Load the core and/or local language file(s).
			$lang->load('tpl_'.$template, $client->path, null, false, false)
		||	$lang->load('tpl_'.$template, $client->path.'/templates/'.$template, null, false, false)
		||	$lang->load('tpl_'.$template, $client->path, $lang->getDefault(), false, false)
		||	$lang->load('tpl_'.$template, $client->path.'/templates/'.$template, $lang->getDefault(), false, false);

		if (file_exists($formFile)) {
			// Get the template form.
			try {
				$form->loadFile($formFile, false, '//config');
			} catch (Exception $e) {
				$this->setError($e->getMessage());
				return false;
			}
		}
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	*/
	public function getTable($type = 'Style', $prefix = 'TemplatesTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('style.id');

		if (!isset($this->_cache[$pk]))
		{
			$false	= false;

			// Get a row instance.
			$table = &$this->getTable();

			// Attempt to load the row.
			$return = $table->load($pk);

			// Check for a table object error.
			if ($return === false && $table->getError())
			{
				$this->setError($table->getError());
				return $false;
			}

			// Convert to the JObject before adding other data.
			$this->_cache[$pk] = JArrayHelper::toObject($table->getProperties(1), 'JObject');

			// Convert the params field to an array.
			$registry = new JRegistry;
			$registry->loadJSON($table->params);
			$this->_cache[$pk]->params = $registry->toArray();
		}

		return $this->_cache[$pk];
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array		An optional array of source data.
	 *
	 * @return	mixed		JForm object on success, false on failure.
	 */
	public function getForm($data = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// The folder and element vars are passed when saving the form.
		if (empty($data)) {
			$item		= $this->getItem();
			$clientId	= $item->client_id;
			$template	= $item->template;
		} else {
			$clientId	= JArrayHelper::getValue($data, 'client_id');
			$template	= JArrayHelper::getValue($data, 'template');
		}

		// These variables are used to add data from the plugin XML files.
		$this->setState('item.client_id',	$clientId);
		$this->setState('item.template',	$template);

		// Get the form.
		try {
			$form = parent::getForm('com_templates.style', 'style', array('control' => 'jform', 'event' => 'onPrepareForm'));
		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_templates.edit.style.data', array());

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		}

		return $form;
	}

	/**
	 * Method to get a form object for the template params.
	 *
	 * @param	string		An optional template folder.
	 * @param	int			An client id.
	 *
	 * @return	mixed		A JForm object on success, false on failure.
	 */
	public function getParamsForm($template = null, $clientId = null)
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		// Initialise variables.
		$lang			= JFactory::getLanguage();
		$form			= null;
		$formName		= 'com_templates.style.params';
		$formOptions	= array('control' => 'jformparams', 'event' => 'onPrepareForm');

		if (empty($template) && is_null($clientId))
		{
			$item		= $this->getItem();
			$clientId	= $item->client_id;
			$template	= $item->template;
		}
		$client			= JApplicationHelper::getClientInfo($clientId);
		$formFile		= JPath::clean($client->path.'/templates/'.$template.'/templateDetails.xml');

		// Load the core and/or local language file(s).
			$lang->load('tpl_'.$template, $client->path, null, false, false)
		||	$lang->load('tpl_'.$template, $client->path.DS.'templates'.DS.$template, null, false, false)
		||	$lang->load('tpl_'.$template, $client->path, $lang->getDefault(), false, false)
		||	$lang->load('tpl_'.$template, $client->path.DS.'templates'.DS.$template, $lang->getDefault(), false, false);
		//$lang->load('tpl_'.$template, JPATH_SITE);
		//$lang->load('tpl_'.$template, JPATH_ADMINISTRATOR);

		if (file_exists($formFile))
		{
			// If an XML file was found in the component, load it first.
			// We need to qualify the full path to avoid collisions with component file names.
			$form = parent::getForm($formName, $formFile, $formOptions, true);

			// Check for an error.
			if (JError::isError($form)) {
				$this->setError($form->getMessage());
				return false;
			}
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
		// Initialise variables;
		$dispatcher = JDispatcher::getInstance();
		$table		= $this->getTable();
		$pk			= (!empty($data['id'])) ? $data['id'] : (int)$this->getState('style.id');
		$isNew		= true;

		// Include the content plugins for the onSave events.
		JPluginHelper::importPlugin('content');

		// Load the row if saving an existing record.
		if ($pk > 0)
		{
			$table->load($pk);
			$isNew = false;
		}

		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError(JText::sprintf('JERROR_TABLE_BIND_FAILED', $table->getError()));
			return false;
		}

		// Prepare the row for saving
		$this->_prepareTable($table);

		// Check the data.
		if (!$table->check())
		{
			$this->setError($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}

		// Clean the cache.
		$cache = JFactory::getCache('com_templates');
		$cache->clean();

		$this->setState('style.id', $table->id);

		return true;
	}

	/**
	 * Method to delete rows.
	 *
	 * @param	array	An array of item ids.
	 *
	 * @return	boolean	Returns true on success, false on failure.
	 */
	public function delete(&$pks)
	{
		// Initialise variables.
		$pks	= (array) $pks;
		$user	= JFactory::getUser();
		$table	= $this->getTable();

		// Iterate the items to delete each one.
		foreach ($pks as $i => $pk)
		{
			if ($table->load($pk))
			{
				// Access checks.
				if (!$user->authorise('core.delete', 'com_templates'))
				{
					throw new Exception(JText::_('JERROR_CORE_DELETE_NOT_PERMITTED'));
				}

				if (!$table->delete($pk))
				{
					throw new Exception($table->getError());
				}
			}
			else
			{
				throw new Exception($table->getError());
			}
		}

		return true;
	}

	/**
	 * Method to duplicate styles.
	 *
	 * @param	array	An array of primary key IDs.
	 *
	 * @return	boolean	True if successful.
	 * @throws	Exception
	 */
	public function duplicate(&$pks)
	{
		// Initialise variables.
		$user	= JFactory::getUser();
		$db		= $this->getDbo();

		// Access checks.
		if (!$user->authorise('core.create', 'com_templates'))
		{
			throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
		}

		$table = $this->getTable();

		foreach ($pks as $pk)
		{
			if ($table->load($pk, true))
			{
				// Reset the id to create a new record.
				$table->id = 0;

				// Reset the home (don't want dupes of that field).
				$table->home = 0;

				// Alter the title.
				$m = null;
				if (preg_match('#\((\d+)\)$#', $table->title, $m))
				{
					$table->title = preg_replace('#\(\d+\)$#', '('.($m[1] + 1).')', $table->title);
				}
				else
				{
					$table->title .= ' (2)';
				}

				if (!$table->check() || !$table->store()) {
					throw new Exception($table->getError());
				}
			}
			else
			{
				throw new Exception($table->getError());
			}
		}


		return true;
	}

	/**
	 * Method to set a template style as home.
	 *
	 * @param	int		The primary key ID for the style.
	 *
	 * @return	boolean	True if successful.
	 * @throws	Exception
	 */
	public function setHome($id = 0)
	{
		// Initialise variables.
		$user	= JFactory::getUser();
		$db		= $this->getDbo();

		// Access checks.
		if (!$user->authorise('core.edit.state', 'com_templates'))
		{
			throw new Exception(JText::_('JERROR_CORE_EDIT_STATE_NOT_PERMITTED'));
		}

		// Lookup the client_id.
		$db->setQuery(
			'SELECT client_id' .
			' FROM #__template_styles' .
			' WHERE id = '.(int) $id
		);
		$clientId = $db->loadResult();

		if ($error = $db->getErrorMsg())
		{
			throw new Exception($error);
		}
		else if (!is_numeric($clientId))
		{
			throw new Exception(JText::_('COM_TEMPLATES_ERROR_STYLE_NOT_FOUND'));
		}

		// Reset the home fields for the client_id.
		$db->setQuery(
			'UPDATE #__template_styles' .
			' SET home = 0' .
			' WHERE client_id = '.(int) $clientId
		);
		if (!$db->query())
		{
			throw new Exception($db->getErrorMsg());
		}

		// Set the new home style.
		$db->setQuery(
			'UPDATE #__template_styles' .
			' SET home = 1' .
			' WHERE id = '.(int) $id
		);
		if (!$db->query())
		{
			throw new Exception($db->getErrorMsg());
		}

		return true;
	}
}
