<?php
/**
 * @version		$Id: source.php 12592 2009-08-01 08:49:56Z hackwar $
 * @package		Joomla.Administrator
 * @subpackage	Templates
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * @package		Joomla.Administrator
 * @subpackage	Templates
 */
class TemplatesModelSource extends JModel
{
	/**
	 * Template id
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * Template data
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * client object
	 *
	 * @var object
	 */
	var $_client = null;

	/**
	 * Template name
	 *
	 * @var string
	 */
	var $_template = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		$this->_template = JRequest::getVar('template');
		$this->_client	= &JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
	}

	/**
	 * Method to get a Template
	 *
	 * @since 1.6
	 */
	function &getData()
	{
		// Load the data
		if (!$this->_loadData())
			$this->_initData();

		return $this->_data;
	}

	/**
	 * Method to get the client object
	 *
	 * @since 1.6
	 */
	function &getClient()
	{
		return $this->_client;
	}

	function &getTemplate()
	{
		return $this->_template;
	}

	/**
	 * Method to store the Template
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.6
	 */
	function store($filecontent)
	{
		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');
		$ftp = JClientHelper::getCredentials('ftp');

		$file = $this->_client->path.DS.'templates'.DS.$this->_template.DS.'index.php';

		// Try to make the template file writeable
		if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0755')) {
			$this->setError(JText::_('Could not make the template file writable'));
			return false;
		}

		jimport('joomla.filesystem.file');
		$return = JFile::write($file, $filecontent);

		// Try to make the template file unwriteable
		if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0555')) {
			$this->setError(JText::_('Could not make the template file unwritable'));
			return false;
		}

		if (!$return)
		{
			$this->setError(JText::_('Operation Failed').': '.JText::sprintf('Failed to open file for writing.', $file));
			return false;
		}

		return true;
	}

	/**
	 * Method to load Template data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.6
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$file		= $this->_client->path.DS.'templates'.DS.$this->_template.DS.'index.php';

			// Read the source file
			jimport('joomla.filesystem.file');
			$content = JFile::read($file);

			if ($content === false)
			{
				$this->setError(JText::sprintf('Operation Failed Could not open', $file));
				return false;
			}
			$content = htmlspecialchars($content, ENT_COMPAT, 'UTF-8');

			$this->_data = $content;
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the Template data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.6
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$template = new stdClass();
			$this->_data = $template;
			return (boolean) $this->_data;
		}
		return true;
	}
}
