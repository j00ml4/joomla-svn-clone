<?php
/**
 * @version		$Id: extension.php 12490 2009-07-06 11:57:32Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * Extension Manager Abstract Extension Model
 *
 * @abstract
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @since		1.5
 */
class InstallerModel extends JModel
{
	/** @var array Array of installed components */
	var $_items = array();

	/** @var object JPagination object */
	var $_pagination = null;

	/**
	 * Overridden constructor
	 * @access	protected
	 */
	function __construct()
	{
		$app	= &JFactory::getApplication();

		// Call the parent constructor
		parent::__construct();

		// Set state variables from the request
		$this->setState('pagination.limit',	$app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int'));
		$this->setState('pagination.offset',$app->getUserStateFromRequest('com_installer.limitstart.'.$this->_type, 'limitstart', 0, 'int'));
		$this->setState('pagination.total',	0);
	}

	function &getItems()
	{
		if (empty($this->_items)) {
			// Load the items
			$this->_loadItems();
		}
		return $this->_items;
	}

	function &getPagination()
	{
		if (empty($this->_pagination)) {
			// Make sure items are loaded for a proper total
			if (empty($this->_items)) {
				// Load the items
				$this->_loadItems();
			}
			// Load the pagination object
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->_state->get('pagination.total'), $this->_state->get('pagination.offset'), $this->_state->get('pagination.limit'));
		}
		return $this->_pagination;
	}

	/**
	 * Remove (uninstall) an extension
	 *
	 * @static
	 * @param	array	An array of identifiers
	 * @return	boolean	True on success
	 * @since 1.0
	 */
	function remove($eid=array())
	{

		// Initialize variables
		$app	= &JFactory::getApplication();
		$failed = array ();

		/*
		 * Ensure eid is an array of extension ids in the form id => client_id
		 * TODO: If it isn't an array do we want to set an error and fail?
		 */
		if (!is_array($eid)) {
			$eid = array($eid => 0);
		}

		// Get a database connector
		$db = &JFactory::getDbo();

		// Get an installer object for the extension type
		jimport('joomla.installer.installer');
		$installer = & JInstaller::getInstance();

		// Uninstall the chosen extensions
		foreach ($eid as $id => $clientId)
		{
			$id		= trim($id);
			$result	= $installer->uninstall($this->_type, $id, $clientId);

			// Build an array of extensions that failed to uninstall
			if ($result === false) {
				$failed[] = $id;
			}
		}

		if (count($failed)) {
			// There was an error in uninstalling the package
			$msg = JText::sprintf('UNINSTALLEXT', JText::_($this->_type), JText::_('Error'));
			$result = false;
		} else {
			// Package uninstalled sucessfully
			$msg = JText::sprintf('UNINSTALLEXT', JText::_($this->_type), JText::_('Success'));
			$result = true;
		}

		$app->enqueueMessage($msg);
		$this->setState('action', 'remove');
		$this->setState('name', $installer->get('name'));
		$this->setState('message', $installer->message);
		$this->setState('extension_message', $installer->get('extension_message'));

		return $result;
	}

	function _loadItems()
	{
		return JError::raiseError(500, JText::_('Method Not Implemented'));
	}
}