<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');

/**
 * Joomla! Application class
 *
 * Provide many supporting API functions
 *
 * @package		Joomla.Administrator
 * @final
 */
class JAdministrator extends JApplication
{
	/**
	 * Class constructor
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * Recognized key values include 'clientId' (this list is not meant to be comprehensive).
	 */
	public function __construct($config = array())
	{
		$config['clientId'] = 1;
		parent::__construct($config);

		//Set the root in the URI based on the application name
		JURI::root(null, str_replace('/'.$this->getName(), '', JURI::base(true)));
	}

	/**
	 * Initialise the application.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 */
	function initialise($options = array())
	{
		$config = &JFactory::getConfig();

		// if a language was specified it has priority
		// otherwise use user or default language settings
		if (empty($options['language']))
		{
			$user = & JFactory::getUser();
			$lang	= $user->getParam('admin_language');

			// Make sure that the user's language exists
			if ($lang && JLanguage::exists($lang)) {
				$options['language'] = $lang;
			}
			else
			{
				$params = JComponentHelper::getParams('com_languages');
				$client	= &JApplicationHelper::getClientInfo($this->getClientId());
				$options['language'] = $params->get($client->name, $config->getValue('config.language','en-GB'));
			}
		}

		// One last check to make sure we have something
		if (! JLanguage::exists($options['language']))
		{
			$lang = $config->getValue('config.language','en-GB');
			if (JLanguage::exists($lang)) {
				$options['language'] = $lang;
			}
			else {
				$options['language'] = 'en-GB'; // as a last ditch fail to english
			}
		}

		parent::initialise($options);
	}

	/**
	 * Route the application
	 */
	public function route()
	{
		$uri = JURI::getInstance();

		if ($this->getCfg('force_ssl') >= 1 && strtolower($uri->getScheme()) != 'https')
		{
			//forward to https
			$uri->setScheme('https');
			$this->redirect((string)$uri);
		}

		// Trigger the onAfterRoute event.
		JPluginHelper::importPlugin('system');
		$this->triggerEvent('onAfterRoute');
	}

	/**
	 * Return a reference to the JRouter object.
	 *
	 * @return	JRouter
	 * @since	1.5
	 */
	static public function &getRouter($name = null, array $options = array())
	{
		$router = &parent::getRouter('administrator');
		return $router;
	}

	/**
	 * Dispatch the application
	 *
	 * @param	string	The component to dispatch.
	 */
	public function dispatch($component = null)
	{
		if ($component === null) {
			$component = JAdministratorHelper::findOption();
		}

		$document	= &JFactory::getDocument();
		$user		= &JFactory::getUser();

		switch ($document->getType())
		{
			case 'html':
				$document->setMetaData('keywords', $this->getCfg('MetaKeys'));
				JHtml::_('behavior.framework', true);
				break;

			default:
				break;
		}

		$document->setTitle(htmlspecialchars_decode($this->getCfg('sitename')). ' - ' .JText::_('Administration'));
		$document->setDescription($this->getCfg('MetaDesc'));

		$contents = JComponentHelper::renderComponent($component);
		$document->setBuffer($contents, 'component');

		// Trigger the onAfterDispatch event.
		JPluginHelper::importPlugin('system');
		$this->triggerEvent('onAfterDispatch');
	}

	/**
	 * Display the application.
	 */
	public function render()
	{
		$component	= JRequest::getCmd('option', 'com_login');
		$template	= $this->getTemplate(true);
		$file 		= JRequest::getCmd('tmpl', 'index');

		if ($component == 'com_login') {
			$file = 'login';
		}

		$params = array(
			'template' 	=> $template->template,
			'file'		=> $file.'.php',
			'directory'	=> JPATH_THEMES,
			'params'	=> $template->params
		);

		$document = &JFactory::getDocument();
		$document->parse($params);
		$this->triggerEvent('onBeforeRender');
		$data = $document->render($this->getCfg('caching'), $params);
		JResponse::setBody($data);
		$this->triggerEvent('onAfterRender');
	}

	/**
	 * Login authentication function
	 *
	 * @param	array 	Array('username' => string, 'password' => string)
	 * @param	array 	Array('remember' => boolean)
	 * @see		JApplication::login
	 */
	public function login($credentials, $options = array())
	{
		//The minimum group
		$options['group'] = 'Public Backend';

		 //Make sure users are not autoregistered
		$options['autoregister'] = false;

		//Set the application login entry point
		if (!array_key_exists('entry_url', $options)) {
			$options['entry_url'] = JURI::base().'index.php?option=com_users&task=login';
		}

		// Set the access control action to check.
		$options['action'] = 'core.login.admin';

		$result = parent::login($credentials, $options);

		if (!JError::isError($result))
		{
			$lang = JRequest::getCmd('lang');
			$lang = preg_replace('/[^A-Z-]/i', '', $lang);
			$this->setUserState('application.lang', $lang );

			JAdministrator::purgeMessages();
		}

		return $result;
	}

	/**
	 * Get the template
	 *
	 * @return	string	The template name
	 * @since	1.0
	 */
	public function getTemplate($params = false)
	{
		static $template;

		if (!isset($template))
		{
			// Load the template name from the database
			$db = &JFactory::getDbo();
			$query = 'SELECT template, params'
				. ' FROM #__template_styles'
				. ' WHERE client_id = 1'
				. ' AND home = 1'
				;
			$db->setQuery($query);
			$template = $db->loadObject();

			$template->template = JFilterInput::getInstance()->clean($template->template, 'cmd');

			if (!file_exists(JPATH_THEMES.DS.$template->template.DS.'index.php'))
			{
				$template->template = 'bluestork';
				$template->params = new JParameter();
			} else {
				$template->params = new JParameter($template->params);
			}
		}
		if ($params) {
			return $template;
		}

		return $template->template;
	}

	/**
	 * Purge the jos_messages table of old messages
	 *
	 * @since 1.5
	 */
	public static function purgeMessages()
	{
		$db		= &JFactory::getDbo();
		$user	= &JFactory::getUser();

		$userid = $user->get('id');

		$query = 'SELECT *'
		. ' FROM #__messages_cfg'
		. ' WHERE user_id = ' . (int) $userid
		. ' AND cfg_name = "auto_purge"'
		;
		$db->setQuery($query);
		$config = $db->loadObject();

		// check if auto_purge value set
		if (is_object($config) and $config->cfg_name == 'auto_purge')
		{
			$purge 	= $config->cfg_value;
		}
		else
		{
			// if no value set, default is 7 days
			$purge 	= 7;
		}
		// calculation of past date

		// if purge value is not 0, then allow purging of old messages
		if ($purge > 0)
		{
			// purge old messages at day set in message configuration
			$past = JFactory::getDate(time() - $purge * 86400);
			$pastStamp = $past->toMySQL();

			$query = 'DELETE FROM #__messages'
			. ' WHERE date_time < ' . $db->Quote($pastStamp)
			. ' AND user_id_to = ' . (int) $userid
			;
			$db->setQuery($query);
			$db->query();
		}
	}
}
