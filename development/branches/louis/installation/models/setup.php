<?php
/**
 * @version		$Id$
 * @package		Joomla.Installation
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * Setup model for the Joomla Core Installer.
 *
 * @package		Joomla.Installation
 * @since		1.6
 */
class JInstallationModelSetup extends JModel
{
	/**
	 * Get the current setup options from the session.
	 *
	 * @return	array
	 */
	public function getOptions()
	{
		$session = & JFactory::getSession();
		$options = $session->get('setup.options', array());

		return $options;
	}

	/**
	 * Store the current setup options in the session.
	 *
	 * @return	array
	 */
	public function storeOptions($options)
	{
		// Get the current setup options from the session.
		$session = &JFactory::getSession();
		$old = $session->get('setup.options', array());

		// Merge the new setup options into the current ones and store in the session.
		$options = array_merge($old, (array)$options);
		$session->set('setup.options', $options);

		// If the setup language is set in the options, set it separately in the session.
		if (!empty($options['language'])) {
			$session->set('setup.language', $options['language']);
		}

		return $options;
	}

	/**
	 * Method to get the link form.
	 *
	 * @return	mixed	JForm object on success, false on failure.
	 * @since	1.6
	 */
	public function &getForm($view = null)
	{
		// Initialise variables.
		$false = false;

		if (!$view) {
			$view = JRequest::getWord('view', 'language');
		}

		// Get the form.
		jimport('joomla.form.form');
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT.'/models/fields');
		$form = &JForm::getInstance('jform', $view, array('control' => 'jform'));

		// Check for an error.
		if (JError::isError($form)) {
			$this->setError($form->getMessage());
			return $false;
		}

		// Check the session for previously entered form data.
		$data = (array) $this->getOptions();

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		}

		return $form;
	}

	public function getDboptions()
	{
		// Initialise variables.
		$options = array();

		// Create an array of known database connect functions.
		$map = array(
			'MySQL'  => 'mysql_connect',
			'MySQLi' => 'mysqli_connect',
		);

		// Iterate over the options, building an array.
		$found = false;
		foreach ($map as $k => $v)
		{
			// Only list available options.
			if (!function_exists($v)) {
				continue;
			}

			// Create the option object.
			$option = new stdClass;
			$option->text = $k;
			$option->value = strtolower($k);

			// Select the first available.
			if (!$found) {
				$option->selected = ' selected="selected"';
				$found = true;
			}

			$options[] = $option;
		}

		return $options;
	}

	/**
	 * Generate a panel of language choices for the user to select their language
	 *
	 * @return	boolean True if successful
	 * @since	1.5
	 */
	public function getLanguages()
	{
		// Initialise variables.
		$app = &JFactory::getApplication();

		// Detect the native language.
		jimport('joomla.language.helper');
		$native = JLanguageHelper::detectLanguage();

		// Get a forced language if it exists.
		$forced = $app->getLocalise();
		if (!empty($forced['language'])) {
			$native = $forced['language'];
		}

		// Get the list of available languages.
		$list = JLanguageHelper::createLanguageList($native);
		if (!$list || JError::isError($list)) {
			$list = array();
		}

		return $list;
	}

	/**
	 * Gets PHP options.
	 *
	 * @return	array
	 */
	public function getPhpOptions()
	{
		// Initialise variables.
		$options = array();

		// Check the PHP Version.
		$option = new stdClass;
		$option->label  = JText::_('PHP_VERSION').' >= 5.2.0';
		$option->state  = (phpversion() >= '5.2.0');
		$option->notice = null;
		$options[] = $option;

		// Check for zlib support.
		$option = new stdClass;
		$option->label  = JText::_('ZLIB_COMPRESSION_SUPPORT');
		$option->state  = extension_loaded('zlib');
		$option->notice = null;
		$options[] = $option;

		// Check for XML support.
		$option = new stdClass;
		$option->label  = JText::_('XML_SUPPORT');
		$option->state  = extension_loaded('xml');
		$option->notice = null;
		$options[] = $option;

		// Check for MySQL support.
		$option = new stdClass;
		$option->label  = JText::_('MYSQL_SUPPORT');
		$option->state  = (function_exists('mysql_connect') || function_exists('mysqli_connect'));
		$option->notice = null;
		$options[] = $option;

		// Check for mbstring options.
		if (extension_loaded('mbstring'))
		{
			// Check for default MB language.
			$option = new stdClass;
			$option->label  = JText::_('MB_LANGUAGE_IS_DEFAULT');
			$option->state  = (strtolower(ini_get('mbstring.language')) == 'neutral');
			$option->notice = ($option->state) ? null : JText::_('NOTICEMBLANGNOTDEFAULT');
			$options[] = $option;

			// Check for MB function overload.
			$option = new stdClass;
			$option->label  = JText::_('MB_STRING_OVERLOAD_OFF');
			$option->state  = (ini_get('mbstring.func_overload') == 0);
			$option->notice = ($option->state) ? null : JText::_('NOTICEMBSTRINGOVERLOAD');
			$options[] = $option;
		}

		// Check for configuration file writeable.
		$option = new stdClass;
		$option->label  = 'configuration.php '.JText::_('writable');
		$option->state  = ((@file_exists('../configuration.php') && @is_writable('../configuration.php')) || is_writable('../'));
		$option->notice = ($option->state) ? null : JText::_('NOTICEYOUCANSTILLINSTALL');
		$options[] = $option;

		return $options;
	}

	/**
	 * Gets PHP Settings.
	 *
	 * @return	array
	 */
	public function getPhpSettings()
	{
		// Initialise variables.
		$settings = array();

		// Check for safe mode.
		$setting = new stdClass;
		$setting->label = JText::_('SAFE_MODE');
		$setting->state = (bool) ini_get('safe_mode');
		$setting->recommended = false;
		$settings[] = $setting;

		// Check for display errors.
		$setting = new stdClass;
		$setting->label = JText::_('DISPLAY_ERRORS');
		$setting->state = (bool) ini_get('display_errors');
		$setting->recommended = false;
		$settings[] = $setting;

		// Check for file uploads.
		$setting = new stdClass;
		$setting->label = JText::_('FILE_UPLOADS');
		$setting->state = (bool) ini_get('file_uploads');
		$setting->recommended = true;
		$settings[] = $setting;

		// Check for magic quotes.
		$setting = new stdClass;
		$setting->label = JText::_('MAGIC_QUOTES_RUNTIME');
		$setting->state = (bool) ini_get('magic_quotes_runtime');
		$setting->recommended = false;
		$settings[] = $setting;

		// Check for register globals.
		$setting = new stdClass;
		$setting->label = JText::_('REGISTER_GLOBALS');
		$setting->state = (bool) ini_get('register_globals');
		$setting->recommended = false;
		$settings[] = $setting;

		// Check for output buffering.
		$setting = new stdClass;
		$setting->label = JText::_('OUTPUT_BUFFERING');
		$setting->state = (bool) ini_get('output_buffering');
		$setting->recommended = false;
		$settings[] = $setting;

		// Check for session auto-start.
		$setting = new stdClass;
		$setting->label = JText::_('SESSION_AUTO_START');
		$setting->state = (bool) ini_get('session.auto_start');
		$setting->recommended = false;
		$settings[] = $setting;

		return $settings;
	}

	/**
	 * Method to validate the form data.
	 *
	 * @param	array		The form data.
	 * @return	array|bool	Array of filtered data if valid, false otherwise.
	 * @since	1.6
	 */
	public function validate($data, $view = null)
	{
		// Get the form.
		$form = &$this->getForm($view);

		// Check for an error.
		if ($form === false) {
			return false;
		}

		// Filter and validate the form data.
		$data = $form->filter($data);
		$return	= $form->validate($data);

		// Check for an error.
		if (JError::isError($return)) {
			$this->setError($return->getMessage());
			return false;
		}

		// Check the validation results.
		if ($return === false)
		{
			// Get the validation messages from the form.
			foreach ($form->getErrors() as $message) {
				$this->setError($message);
			}

			return false;
		}

		return $data;
	}
}