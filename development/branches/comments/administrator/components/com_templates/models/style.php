<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Template style model.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_templates
 * @since		1.6
 */
class TemplatesModelStyle extends JModelAdmin
{
	/**
	 * Item cache.
	 */
	private $_cache = array();
	
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_TEMPLATES_STYLES';

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
		if (!$user->authorise('core.create', 'com_templates')) {
			throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
		}

		$table = $this->getTable();

		foreach ($pks as $pk) {
			if ($table->load($pk, true)) {
				// Reset the id to create a new record.
				$table->id = 0;

				// Reset the home (don't want dupes of that field).
				$table->home = 0;

				// Alter the title.
				$m = null;
				if (preg_match('#\((\d+)\)$#', $table->title, $m)) {
					$table->title = preg_replace('#\(\d+\)$#', '('.($m[1] + 1).')', $table->title);
				} else {
					$table->title .= ' (2)';
				}

				if (!$table->check() || !$table->store()) {
					throw new Exception($table->getError());
				}
			} else {
				throw new Exception($table->getError());
			}
		}


		return true;
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
			$form = parent::getForm('com_templates.style', 'style', array('control' => 'jform'));
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
	 * @param	object	A form object.
	 *
	 * @throws	Exception if there is an error in the form event.
	 * @since	1.6
	 */
	protected function preprocessForm($form)
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
			if (!$form->loadFile($formFile, false, '//config')) {
				throw new Exception(JText::_('JModelForm_Error_loadFile_failed'));
			}
		}

		// Trigger the default form events.
		parent::preprocessForm($form);
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
		if (!$user->authorise('core.edit.state', 'com_templates')) {
			throw new Exception(JText::_('JERROR_CORE_EDIT_STATE_NOT_PERMITTED'));
		}

		// Lookup the client_id.
		$db->setQuery(
			'SELECT client_id' .
			' FROM #__template_styles' .
			' WHERE id = '.(int) $id
		);
		$clientId = $db->loadResult();

		if ($error = $db->getErrorMsg()) {
			throw new Exception($error);
		} else if (!is_numeric($clientId)) {
			throw new Exception(JText::_('COM_TEMPLATES_ERROR_STYLE_NOT_FOUND'));
		}

		// Reset the home fields for the client_id.
		$db->setQuery(
			'UPDATE #__template_styles' .
			' SET home = 0' .
			' WHERE client_id = '.(int) $clientId
		);

		if (!$db->query()) {
			throw new Exception($db->getErrorMsg());
		}

		// Set the new home style.
		$db->setQuery(
			'UPDATE #__template_styles' .
			' SET home = 1' .
			' WHERE id = '.(int) $id
		);

		if (!$db->query()) {
			throw new Exception($db->getErrorMsg());
		}

		return true;
	}
}
