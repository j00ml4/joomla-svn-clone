<?php
/**
 * @version		$Id: configuration.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Installation
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');
jimport('joomla.user.helper');
require_once JPATH_INSTALLATION.'/helpers/database.php';

/**
 * Install Configuration model for the Joomla Core Installer.
 *
 * @package		Joomla.Installation
 * @since		1.6
 */
class JInstallationModelConfiguration extends JModel
{
	private $final_config = "";
	function setup($options)
	{
		// Get the options as a JObject for easier handling.
		$options = JArrayHelper::toObject($options, 'JObject');

		// Attempt to create the root user.
		if (!$this->_createConfiguration($options)) {
			return false;
		}

		// Attempt to create the root user.
		if (!$this->_createRootUser($options)) {
			return false;
		}

		return true;
	}

	function _createConfiguration($options)
	{
		jimport('joomla.registry.registry');
		// Create a new registry to build the configuration options.
		$registry = new JRegistry();

		/* Site Settings */
		$registry->set('offline', 0);
		$registry->set('offline_message', JText::_('INSTL_STD_OFFLINE_MSG'));
		$registry->set('sitename', $options->site_name);
		$registry->set('editor', 'tinymce');
		$registry->set('list_limit', 20);
		$registry->set('access', 1);

		/* Debug Settings */
		$registry->set('debug', 0);
		$registry->set('debug_lang', 0);

		/* Database Settings */
		$registry->set('dbtype', $options->db_type);
		$registry->set('host', $options->db_host);
		$registry->set('user', $options->db_user);
		$registry->set('password', $options->db_pass);
		$registry->set('db', $options->db_name);
		$registry->set('dbprefix', $options->db_prefix);

		/* Server Settings */
		$registry->set('live_site', '');
		$registry->set('secret', JUserHelper::genRandomPassword(16));
		$registry->set('gzip', 0);
		$registry->set('error_reporting', -1);
		$registry->set('helpurl', 'http://help.joomla.org/proxy/index.php?option=com_help&amp;keyref=Help{major}{minor}:{keyref}');
		$registry->set('ftp_host', $options->ftp_host);
		$registry->set('ftp_port', $options->ftp_port);
		$registry->set('ftp_user', $options->ftp_save ? $options->ftp_user : '');
		$registry->set('ftp_pass', $options->ftp_save ? $options->ftp_pass : '');
		$registry->set('ftp_root', $options->ftp_save ? $options->ftp_root : '');
		$registry->set('ftp_enable', $options->ftp_enable);

		/* Cloud Server Settings */
		$registry->set('storage_type', $options->storage_type);
		$registry->set('cloud_acc_name', $options->acc_name);
		$registry->set('cloud_access_key', $options->access_key);
		$registry->set('secret_key', $options->secret_key);
		$registry->set('container', $options->container);
		
		/* Locale Settings */
		$registry->set('offset', 'UTC');
		$registry->set('offset_user', 'UTC');

		/* Mail Settings */
		$registry->set('mailer', 'mail');
		$registry->set('mailfrom', $options->admin_email);
		$registry->set('fromname', $options->site_name);
		$registry->set('sendmail', '/usr/sbin/sendmail');
		$registry->set('smtpauth', 0);
		$registry->set('smtpuser', '');
		$registry->set('smtppass', '');
		$registry->set('smtphost', 'localhost');
		$registry->set('smtpsecure', 'none');
		$registry->set('smtpport', '25');

		/* Cache Settings */
		$registry->set('caching', 0);
		$registry->set('cache_handler', 'file');
		$registry->set('cachetime', 15);

		/* Meta Settings */
		$registry->set('MetaDesc', $options->site_metadesc);
		$registry->set('MetaKeys', $options->site_metakeys);
		$registry->set('MetaTitle', 1);
		$registry->set('MetaAuthor', 1);

		/* SEO Settings */
		$registry->set('sef', 1);
		$registry->set('sef_rewrite', 0);
		$registry->set('sef_suffix', 0);
		$registry->set('unicodeslugs', 0);

		/* Feed Settings */
		$registry->set('feed_limit', 10);
		$registry->set('log_path', JPATH_ROOT.DS.'logs');
		$registry->set('tmp_path', JPATH_ROOT.DS.'tmp');

		/* Session Setting */
		$registry->set('lifetime', 15);
		$registry->set('session_handler', 'database');
		
		$this->final_config = serialize($registry);
		// Generate the configuration class string buffer.
		$buffer = $registry->toString('PHP', array('class'=>'JConfig', 'closingtag' => false));
		// Build the configuration file path.
		$path = JPATH_CONFIGURATION.DS.'configuration.php';

		// Determine if the configuration file path is writable.
		if (file_exists($path)) {
			$canWrite = is_writable($path);
		} else {
			$canWrite = is_writable(JPATH_CONFIGURATION.DS);
		}

		/*
		 * If the file exists but isn't writable OR if the file doesn't exist and the parent directory
		 * is not writable we need to use FTP
		 */
		$useFTP = false;
		if ((file_exists($path) && !is_writable($path)) || (!file_exists($path) && !is_writable(dirname($path).'/'))) {
			$useFTP = true;
		}

		// Check for safe mode
		if (ini_get('safe_mode')) {
			$useFTP = true;
		}

		// Enable/Disable override
		if (!isset($options->ftpEnable) || ($options->ftpEnable != 1)) {
			$useFTP = false;
		}

		if ($useFTP == true)
		{
			// Connect the FTP client
			jimport('joomla.client.ftp');
			jimport('joomla.filesystem.path');

			$ftp = JFTP::getInstance($options->ftp_host, $options->ftp_port);
			$ftp->login($options->ftp_user, $options->ftp_pass);

			// Translate path for the FTP account
			$file = JPath::clean(str_replace(JPATH_CONFIGURATION, $options->ftp_root, $path), '/');

			// Use FTP write buffer to file
			if (!$ftp->write($file, $buffer)) {
				// Set the config string to the session.
				$session = JFactory::getSession();
				$session->set('setup.config', $buffer);
			}

			$ftp->quit();
		}
		else
		{
			if ($canWrite) {
				/*file_put_contents($path, $buffer);
				$session = JFactory::getSession();
				$session->set('setup.config', null);*/
			} else {
				// Set the config string to the session.
				/*$session = JFactory::getSession();
				$session->set('setup.config', $buffer);*/
			}
		}

		return true;
	}
	function _createRootUser($options)
	{
		// Get a database object.
		$db = JInstallationHelperDatabase::getDBO($options->db_type, $options->db_host, $options->db_user, $options->db_pass, $options->db_name, $options->db_prefix);

		// Check for errors.
		if (JError::isError($db)) {
			$this->setError(JText::sprintf('INSTL_ERROR_CONNECT_DB', (string)$db));
			return false;
		}

		// Check for database errors.
		if ($err = $db->getErrorNum()) {
			$this->setError(JText::sprintf('INSTL_ERROR_CONNECT_DB', $db->getErrorNum()));
			return false;
		}

		// Create random salt/password for the admin user
		$salt = JUserHelper::genRandomPassword(32);
		$crypt = JUserHelper::getCryptedPassword($options->admin_password, $salt);
		$cryptpass = $crypt.':'.$salt;

		// create the admin user
		date_default_timezone_set('UTC');
		$installdate	= date('Y-m-d H:i:s');
		$nullDate		= $db->getNullDate();
    //sqlsrv change
    $query = $db->getQuery(true);
    $query->select('id');
    $query->from('#__users');
    $query->where('id = 42');
    
    $db->setQuery($query);
    
    if($db->loadResult())
    {
      $query = $db->getQuery(true);
      $query->update('#__users');
      $query->set('name = '.$db->quote('Super User'));
      $query->set('username = '.$db->quote($options->admin_user));
      $query->set('email = '.$db->quote($options->admin_email));
      $query->set('password = '.$db->quote($cryptpass));
      $query->set('usertype = '.$db->quote('deprecated'));
      $query->set('block = 0');
      $query->set('sendEmail = 1');
      $query->set('registerDate = '.$db->quote($installdate));
      $query->set('lastvisitDate = '.$db->quote($nullDate));
      $query->set('activation = '.$db->quote('0'));
      $query->set('params = '.$db->quote(''));
      $query->where('id = 42');
    }else{
      $query = $db->getQuery(true);
      $fields = 'id, name, username, email, password, usertype, block, sendEmail, registerDate, lastvisitDate, activation, params';
      $query->insertInto('#__users', true);
      $query->fields($fields);
      $query->values('42');
      $query->values($db->quote('Super User'));
      $query->values($db->quote($options->admin_user));
      $query->values($db->quote($options->admin_email));
      $query->values($db->quote($cryptpass));
      $query->values($db->quote('deprecated'));
      $query->values('0');
      $query->values('1');
      $query->values($db->quote($installdate));
      $query->values($db->quote($nullDate));
      $query->values($db->quote('0'));
      $query->values($db->quote(''));
    }
    
		$db->setQuery($query);
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}
	
    
    // Map the super admin to the Super Admin Group
    $query = $db->getQuery(true);
    $query->select('user_id');
    $query->from('#__user_usergroup_map');
    $query->where('user_id = 42');
    
    $db->setQuery($query);
    
    if($db->loadResult())
    {
      $query = $db->getQuery(true);
      $query->update('#__user_usergroup_map');
      $query->set('user_id = 42');
      $query->set('group_id = 8');
    }else{
      $query = $db->getQuery(true);
      $query->insertInto('#__user_usergroup_map');
      $query->fields('user_id');
      $query->fields('group_id');
      $query->values('42');
      $query->values('8');
    }

		$db->setQuery($query);
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		$query = $db->getQuery(true);
		$query->insertInto('#__configuration');
		$query->fields('config');
		$query->fields('date');
		$query->values($db->quote($this->final_config));
		$query->values($db->quote($installdate));
		$db->setQuery($query);
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		return true;
	}
}
