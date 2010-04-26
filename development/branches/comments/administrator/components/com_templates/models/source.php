<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Templates
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');

/**
 * @package		Joomla.Administrator
 * @subpackage	Templates
 * @since		1.5
 */
class TemplatesModelSource extends JModelForm
{
	/**
	 * Cache for the template information.
	 *
	 * @var		object
	 */
	private $_template = null;

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');

		// Load the User state.
		$id = $app->getUserState('com_templates.edit.source.id');

		// Parse the template id out of the compound reference.
		$temp	= explode(':', base64_decode($id));
		$this->setState('extension.id', (int) array_shift($temp));
		$this->setState('filename', array_shift($temp));

		// Load the parameters.
		$params	= JComponentHelper::getParams('com_templates');
		$this->setState('params', $params);
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
		$form = parent::getForm('com_templates.source', 'source', array('control' => 'jform'));
		if (empty($form)) {
			return false;
		}

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_templates.edit.source.data', array());

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		} else {
			$form->bind($this->getSource());
		}

		return $form;
	}

	/**
	 * Method to get a single record.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function &getSource()
	{
		$item = new stdClass;

		if ($this->_template)
		{
			$fileName	= $this->getState('filename');
			$client		= JApplicationHelper::getClientInfo($this->_template->client_id);
			$filePath	= JPath::clean($client->path.'/templates/'.$this->_template->element.'/'.$fileName);

			if (file_exists($filePath))
			{
				jimport('joomla.filesystem.file');

				$item->extension_id	= $this->getState('extension.id');
				$item->filename		= $this->getState('filename');
				$item->source		= JFile::read($filePath);
			}
			else {
				$this->setError(JText::_('COM_TEMPLATES_ERROR_SOURCE_FILE_NOT_FOUND'));
			}
		}

		return $item;
	}

	/**
	 * Method to get the template information.
	 *
	 * @return	mixed	Object if successful, false if not and internal error is set.
	 * @since	1.6
	 */
	public function &getTemplate()
	{
		// Initialise variables.
		$pk		= $this->getState('extension.id');
		$db		= $this->getDbo();
		$result	= false;

		// Get the template information.
		$db->setQuery(
			'SELECT extension_id, client_id, element' .
			' FROM #__extensions' .
			' WHERE extension_id = '.(int) $pk.
			'  AND type = '.$db->quote('template')
		);

		$result = $db->loadObject();
		if (empty($result))
		{
			if ($error = $db->getErrorMsg()) {
				$this->setError($error);
			}
			else {
				$this->setError(JText::_('COM_TEMPLATES_ERROR_EXTENSION_RECORD_NOT_FOUND'));
			}
			$this->_template = false;
		}
		else {
			$this->_template = $result;
		}

		return $this->_template;
	}

	/**
	 * Method to store the source file contents.
	 *
	 * @param	array	The souce data to save.
	 *
	 * @return	boolean	True on success, false otherwise and internal error set.
	 * @since	1.6
	 */
	public function save($data)
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.client.helper');

		// Get the template.
		$template = $this->getTemplate();
		if (empty($template)) {
			return false;
		}

		$fileName	= $this->getState('filename');
		$client		= JApplicationHelper::getClientInfo($template->client_id);
		$filePath	= JPath::clean($client->path.'/templates/'.$template->element.'/'.$fileName);

		// Set FTP credentials, if given.
		JClientHelper::setCredentialsFromRequest('ftp');
		$ftp = JClientHelper::getCredentials('ftp');

		// Try to make the template file writeable.
		if (!$ftp['enabled'] && JPath::isOwner($filePath) && !JPath::setPermissions($filePath, '0755'))
		{
			$this->setError(JText::_('COM_TEMPLATES_ERROR_SOURCE_FILE_NOT_WRITABLE'));
			return false;
		}

		$return = JFile::write($filePath, $data['source']);

		// Try to make the template file unwriteable.
		if (!$ftp['enabled'] && JPath::isOwner($filePath) && !JPath::setPermissions($filePath, '0555'))
		{
			$this->setError(JText::_('COM_TEMPLATES_ERROR_SOURCE_FILE_NOT_UNWRITABLE'));
			return false;
		}
		else if (!$return)
		{
			$this->setError(JText::sprintf('COM_TEMPLATES_ERROR_FAILED_TO_SAVE_FILENAME', $fileName));
			return false;
		}

		return true;
	}
}
