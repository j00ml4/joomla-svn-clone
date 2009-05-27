<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Menus
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// Import library dependencies
jimport('joomla.application.component.model');
jimport('joomla.installer.installer');
jimport('joomla.updater.updater');

/**
 * Update Default Model
 *
 * @package		Joomla.Administrator
 * @subpackage	Installer
 * @since		1.5
 */
class UpdateModelUpdate extends JModel
{
	/**
	 * Extension Type
	 * @var	string
	 */
	var $_type = 'update';

	var $_message = '';

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
	 * Current extension list
	 */
	function _loadItems()
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		jimport('joomla.filesystem.folder');

		/* Get a database connector */
		$db = &JFactory::getDbo();

		$query = 'SELECT *' .
				' FROM #__updates' .
				//' WHERE extension_id != 0' . // we only want actual updates
				' ORDER BY type, client_id, folder, name';
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$apps = &JApplicationHelper::getClientInfo();

		$numRows = count($rows);
		for ($i=0;$i < $numRows; $i++)
		{
			$row = &$rows[$i];
			$row->jname = JString::strtolower(str_replace(" ", "_", $row->name));
			if (isset($apps[$row->client_id])) {
				$row->client = ucfirst($apps[$row->client_id]->name);
			} else {
				$row->client = $row->client_id;
			}
		}
		$this->setState('pagination.total', $numRows);
		if ($this->_state->get('pagination.limit') > 0) {
			$this->_items = array_slice($rows, $this->_state->get('pagination.offset'), $this->_state->get('pagination.limit'));
		} else {
			$this->_items = $rows;
		}
	}

	function findUpdates($eid=0) {
		$updater = &JUpdater::getInstance();
		$results = $updater->findUpdates($eid);
		return true;
	}

	function purge() {
		$db = &JFactory::getDbo();
		$db->setQuery('TRUNCATE TABLE #__updates');
		if ($db->Query()) {
			$this->_message = JText::_('Purged updates');
			return true;
		} else {
			$this->_message = JText::_('Failed to purge updates');
			return false;
		}
	}
}
