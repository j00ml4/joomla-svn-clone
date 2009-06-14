<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Config
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	Config
 * @version		1.6
 */
class ConfigControllerApplication extends JController
{
	/**
	 * Class Constructor
	 * 
	 * @param	array	$config		An optional associative array of configuration settings.
	 * @return	void
	 * @since	1.5
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
		
		// Map the apply task to the save method.
		$this->registerTask('apply', 'save');
	}


	/**
	 * Method to save the configuration.
	 * 
	 * @return	bool	True on success, false on failure.
	 * @since	1.5
	 */
	public function save()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('Invalid_Token'));
		
		// Check if the user is authorized to do this.
		if (!JFactory::getUser()->authorize('core.config.manage')) {
			$mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
		}
		
		// Set FTP credentials, if given.
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		// Initialize variables.
		$app	= &JFactory::getApplication();
		$model	= $this->getModel('Application');
		$form	= $model->getForm();
		$data	= JRequest::getVar('jform', array(), 'post', 'array');

		// Validate the posted data.
		$return = $model->validate($form, $data);

var_dump($return);
var_dump($model->getErrors());
exit;
		// Check for validation errors.
		if ($return === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'notice');
				} else {
					$app->enqueueMessage($errors[$i], 'notice');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_weblinks.edit.weblink.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=com_weblinks&view=weblink&layout=edit&hidemainmenu=1', false));
			return false;
		}

		// Attempt to save the weblink.
		$data	= $return;
		$return = $model->save($data);


		var_dump($_REQUEST);
		exit;
		


		

		

		//Save user and media manager settings
		$table = &JTable::getInstance('component');

		$userpost['params'] = JRequest::getVar('userparams', array(), 'post', 'array');
		$userpost['option'] = 'com_users';
		$table->loadByOption('com_users');
		$table->bind($userpost);

		// pre-save checks
		if (!$table->check()) {
			JError::raiseWarning(500, $table->getError());
			return false;
		}

		// save the changes
		if (!$table->store()) {
			JError::raiseWarning(500, $table->getError());
			return false;
		}

		$mediapost['params'] = JRequest::getVar('mediaparams', array(), 'post', 'array');
		$mediapost['option'] = 'com_media';
		//Sanitize $file_path and $image_path
		$file_path = $mediapost['params']['file_path'];
		$image_path = $mediapost['params']['image_path'];
		if (strpos($file_path, '/') === 0 || strpos($file_path, '\\') === 0) {
			//Leading slash.  Kill it and default to /media
			$file_path = 'images';
		}
		if (strpos($image_path, '/') === 0 || strpos($image_path, '\\') === 0) {
			//Leading slash.  Kill it and default to /media
			$image_path = 'images/stories';
		}
		if (strpos($file_path, '..') !== false) {
			//downward directories.  Kill it and default to images/
			$file_path = 'images';
		}
		if (strpos($image_path, '..') !== false) {
			//downward directories  Kill it and default to images/stories
			$image_path = 'images/stories';
		}
		$mediapost['params']['file_path'] = $file_path;
		$mediapost['params']['image_path'] = $image_path;

		$table->loadByOption('com_media');
		$table->bind($mediapost);

		// pre-save checks
		if (!$table->check()) {
			JError::raiseWarning(500, $table->getError());
			return false;
		}

		// save the changes
		if (!$table->store()) {
			JError::raiseWarning(500, $table->getError());
			return false;
		}

		$config = new JRegistry('config');
		$config_array = array();

		// SITE SETTINGS
		$config_array['offline']	= JRequest::getVar('offline', 0, 'post', 'int');
		$config_array['editor']		= JRequest::getVar('editor', 'tinymce', 'post', 'cmd');
		$config_array['access']		= JRequest::getVar('access', '1', 'post', 'int');
		$config_array['list_limit']	= JRequest::getVar('list_limit', 20, 'post', 'int');
		$config_array['helpurl']	= JRequest::getVar('helpurl', 'http://help.joomla.org', 'post', 'string');
		$config_array['root_user']	= JRequest::getVar('root_user', '42', 'post', 'int');

		// DEBUG
		$config_array['debug']		= JRequest::getVar('debug', 0, 'post', 'int');
		$config_array['debug_lang']	= JRequest::getVar('debug_lang', 0, 'post', 'int');

		// SEO SETTINGS
		$config_array['sef']			= JRequest::getVar('sef', 0, 'post', 'int');
		$config_array['sef_rewrite']	= JRequest::getVar('sef_rewrite', 0, 'post', 'int');
		$config_array['sef_suffix']		= JRequest::getVar('sef_suffix', 0, 'post', 'int');

		// FEED SETTINGS
		$config_array['feed_limit']		= JRequest::getVar('feed_limit', 10, 'post', 'int');
		$config_array['feed_email']		= JRequest::getVar('feed_email', 'author', 'post', 'word');

		// SERVER SETTINGS
		$config_array['secret']				= JRequest::getVar('secret', 0, 'post', 'string');
		$config_array['gzip']				= JRequest::getVar('gzip', 0, 'post', 'int');
		$config_array['error_reporting']	= JRequest::getVar('error_reporting', -1, 'post', 'int');
		$config_array['xmlrpc_server']		= JRequest::getVar('xmlrpc_server', 0, 'post', 'int');
		$config_array['log_path']			= JRequest::getVar('log_path', JPATH_ROOT.DS.'logs', 'post', 'string');
		$config_array['tmp_path']			= JRequest::getVar('tmp_path', JPATH_ROOT.DS.'tmp', 'post', 'string');
		$config_array['live_site'] 			= rtrim(JRequest::getVar('live_site','','post','string'), '/\\');
		$config_array['force_ssl'] 			= JRequest::getVar('force_ssl', 0, 'post', 'int');

		// LOCALE SETTINGS
		$config_array['offset']				= JRequest::getVar('offset', 0, 'post', 'float');

		// CACHE SETTINGS
		$config_array['caching']			= JRequest::getVar('caching', 0, 'post', 'int');
		$config_array['cachetime']			= JRequest::getVar('cachetime', 900, 'post', 'int');
		$config_array['cache_handler']		= JRequest::getVar('cache_handler', 'file', 'post', 'word');
		$config_array['memcache_settings']	= JRequest::getVar('memcache_settings', array(), 'post');

		// FTP SETTINGS
		$config_array['ftp_enable']	= JRequest::getVar('ftp_enable', 0, 'post', 'int');
		$config_array['ftp_host']	= JRequest::getVar('ftp_host', '', 'post', 'string');
		$config_array['ftp_port']	= JRequest::getVar('ftp_port', '', 'post', 'int');
		$config_array['ftp_user']	= JRequest::getVar('ftp_user', '', 'post', 'string');
		$config_array['ftp_pass']	= JRequest::getVar('ftp_pass', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$config_array['ftp_root']	= JRequest::getVar('ftp_root', '', 'post', 'string');

		// DATABASE SETTINGS
		$config_array['dbtype']		= JRequest::getVar('dbtype', 'mysql', 'post', 'word');
		$config_array['host']		= JRequest::getVar('host', 'localhost', 'post', 'string');
		$config_array['user']		= JRequest::getVar('user', '', 'post', 'string');
		$config_array['db']			= JRequest::getVar('db', '', 'post', 'string');
		$config_array['dbprefix']	= JRequest::getVar('dbprefix', 'jos_', 'post', 'string');

		// MAIL SETTINGS
		$config_array['mailer']		= JRequest::getVar('mailer', 'mail', 'post', 'word');
		$config_array['mailfrom']	= JRequest::getVar('mailfrom', '', 'post', 'string');
		$config_array['fromname']	= JRequest::getVar('fromname', 'Joomla 1.5', 'post', 'string');
		$config_array['sendmail']	= JRequest::getVar('sendmail', '/usr/sbin/sendmail', 'post', 'string');
		$config_array['smtpauth']	= JRequest::getVar('smtpauth', 0, 'post', 'int');
		$config_array['smtpuser']	= JRequest::getVar('smtpuser', '', 'post', 'string');
		$config_array['smtppass']	= JRequest::getVar('smtppass', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$config_array['smtphost']	= JRequest::getVar('smtphost', '', 'post', 'string');

		// META SETTINGS
		$config_array['MetaAuthor']	= JRequest::getVar('MetaAuthor', 1, 'post', 'int');
		$config_array['MetaTitle']	= JRequest::getVar('MetaTitle', 1, 'post', 'int');

		// SESSION SETTINGS
		$config_array['lifetime']			= JRequest::getVar('lifetime', 0, 'post', 'int');
		$config_array['session_handler']	= JRequest::getVar('session_handler', 'none', 'post', 'word');

		//LANGUAGE SETTINGS
		//$config_array['lang']				= JRequest::getVar('lang', 'none', 'english', 'cmd');
		//$config_array['language']			= JRequest::getVar('language', 'en-GB', 'post', 'cmd');

		$config->loadArray($config_array);

		//override any possible database password change
		$config->setValue('config.password', $mainframe->getCfg('password'));

		// handling of special characters
		$sitename			= htmlspecialchars(JRequest::getVar('sitename', '', 'post', 'string'), ENT_COMPAT, 'UTF-8');
		$config->setValue('config.sitename', $sitename);

		$MetaDesc			= htmlspecialchars(JRequest::getVar('MetaDesc', '', 'post', 'string'),  ENT_COMPAT, 'UTF-8');
		$config->setValue('config.MetaDesc', $MetaDesc);

		$MetaKeys			= htmlspecialchars(JRequest::getVar('MetaKeys', '', 'post', 'string'),  ENT_COMPAT, 'UTF-8');
		$config->setValue('config.MetaKeys', $MetaKeys);

		// handling of quotes (double and single) and amp characters
		// htmlspecialchars not used to preserve ability to insert other html characters
		$offline_message	= JRequest::getVar('offline_message', '', 'post', 'string');
		$offline_message	= JFilterOutput::ampReplace($offline_message);
		$offline_message	= str_replace('"', '&quot;', $offline_message);
		$offline_message	= str_replace("'", '&#039;', $offline_message);
		$config->setValue('config.offline_message', $offline_message);

		//purge the database session table (only if we are changing to a db session store)
		if ($mainframe->getCfg('session_handler') != 'database' && $config->getValue('session_handler') == 'database')
		{
			$table = &JTable::getInstance('session');
			$table->purge(-1);
		}

		// Get the path of the configuration file
		$fname = JPATH_CONFIGURATION.DS.'configuration.php';

		// Update the credentials with the new settings
		$oldconfig = &JFactory::getConfig();
		$oldconfig->setValue('config.ftp_enable', $config_array['ftp_enable']);
		$oldconfig->setValue('config.ftp_host', $config_array['ftp_host']);
		$oldconfig->setValue('config.ftp_port', $config_array['ftp_port']);
		$oldconfig->setValue('config.ftp_user', $config_array['ftp_user']);
		$oldconfig->setValue('config.ftp_pass', $config_array['ftp_pass']);
		$oldconfig->setValue('config.ftp_root', $config_array['ftp_root']);
		JClientHelper::getCredentials('ftp', true);

		if (!$config->get('caching') && $oldconfig->get('caching')) {
			$cache = JFactory::getCache();
			$cache->clean();
		}

		// Try to make configuration.php writeable
		jimport('joomla.filesystem.path');
		if (!$ftp['enabled'] && JPath::isOwner($fname) && !JPath::setPermissions($fname, '0644')) {
			JError::raiseNotice('SOME_ERROR_CODE', 'Could not make configuration.php writable');
		}

		// Get the config registry in PHP class format and write it to configuation.php
		jimport('joomla.filesystem.file');
		if (JFile::write($fname, $config->toString('PHP', 'config', array('class' => 'JConfig')))) {
			$msg = JText::_('The Configuration Details have been updated');
		} else {
			$msg = JText::_('ERRORCONFIGFILE');
		}

		// Redirect appropriately
		$task = $this->getTask();
		switch ($task) {
			case 'apply' :
				$this->setRedirect('index.php?option=com_config', $msg);
				break;

			case 'save' :
			default :
				$this->setRedirect('index.php', $msg);
				break;
		}

		// Try to make configuration.php unwriteable
		//if (!$ftp['enabled'] && JPath::isOwner($fname) && !JPath::setPermissions($fname, '0444')) {
		if ($config_array['ftp_enable']==0 && !$ftp['enabled'] && JPath::isOwner($fname) && !JPath::setPermissions($fname, '0444')) {
			JError::raiseNotice('SOME_ERROR_CODE', 'Could not make configuration.php unwritable');
		}
	}

	/**
	 * Cancel operation
	 */
	function cancel()
	{
		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		$this->setRedirect('index.php');
	}

	function refreshHelp()
	{
		jimport('joomla.filesystem.file');

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		if (($data = file_get_contents('http://help.joomla.org/helpsites-15.xml')) === false) {
			$this->setRedirect('index.php?option=com_config', JText::_('HELPREFRESH ERROR FETCH'), 'error');
		} else if (!JFile::write(JPATH_BASE.DS.'help'.DS.'helpsites-15.xml', $data)) {
			$this->setRedirect('index.php?option=com_config', JText::_('HELPREFRESH ERROR STORE'), 'error');
		} else {
			$this->setRedirect('index.php?option=com_config', JText::_('HELPREFRESH SUCCESS'));
		}
	}
}
