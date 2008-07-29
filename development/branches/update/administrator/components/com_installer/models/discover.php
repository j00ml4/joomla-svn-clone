<?php
/**
 * @version		$Id: components.php 9764 2007-12-30 07:48:11Z ircmaxell $
 * @package		Joomla
 * @subpackage	Menus
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Import library dependencies
require_once(dirname(__FILE__).DS.'extension.php');
jimport('joomla.installer.installer');

/**
 * Installer Manage Model
 *
 * @package		Joomla
 * @subpackage	Installer
 * @since		1.5
 */
class InstallerModelDiscover extends InstallerModel
{
	/**
	 * Extension Type
	 * @var	string
	 */
	var $_type = 'discover';
	
	var $_message = '';
	
	/**
	 * Current extension list
	 */

	function _loadItems()
	{
		global $mainframe, $option;

		jimport('joomla.filesystem.folder');

		/* Get a database connector */
		$db =& JFactory::getDBO();

		$query = 'SELECT *' .
				' FROM #__extensions' .
				' WHERE state = -1' .
				' ORDER BY type, client_id, folder, name';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$apps =& JApplicationHelper::getClientInfo();
		
		$numRows = count($rows);
		for($i=0;$i < $numRows; $i++)
		{
			$row =& $rows[$i];
			if(strlen($row->manifestcache)) {
				$data = unserialize($row->manifestcache);
				if($data) {
					foreach($data as $key => $value) {
						$row->$key = $value;
					}	
				}
			}
			$row->jname = JString::strtolower(str_replace(" ", "_", $row->name));
			if(isset($apps[$row->client_id])) {
				$row->client = ucfirst($apps[$row->client_id]->name);
			} else {
				$row->client = $row->client_id;
			}
		}
		$this->setState('pagination.total', $numRows);
		if($this->_state->get('pagination.limit') > 0) {
			$this->_items = array_slice( $rows, $this->_state->get('pagination.offset'), $this->_state->get('pagination.limit') );
		} else {
			$this->_items = $rows;
		}
	}
	
	function discover() {
		$installer =& JInstaller::getInstance();
		$results = $installer->discover();
		// Get all templates, including discovered ones
		$query = 'SELECT * FROM #__extensions';
		$dbo =& JFactory::getDBO();
		$dbo->setQuery($query);
		$installed = $dbo->loadObjectList('element');
		foreach($results as $result) {
			// check if we have a match on the element
			if(!array_key_exists($result->element, $installed)) {
				// since the element doesn't exist, its definitely new
				$result->store(); // put it into the table	
				//echo '<p>Added: <pre>'.print_r($result,1).'</pre></p>';
			} else {
				// an element exists that matches this
				//echo '<p>Ignored: '. $result->name .'</p>';
			}
		}
	}
	
	function discover_install() {
		$installer =& JInstaller::getInstance();
		$eid = JRequest::getVar('eid',0);
		$app =& JFactory::getApplication();
		if($eid) {
			$result = $installer->discover_install($eid);
			if($result) { 
				$app->enqueueMessage(JText::_('Discover install successful'));
			} else {
				$app->enqueueMEssage(JText::_('Discover install failed'));
			}
			$this->setState('action', 'remove');
			$this->setState('name', $installer->get('name'));
			$this->setState('message', $installer->message);
			$this->setState('extension.message', $installer->get('extension.message'));
		} else {
			$app =& JFactory::getApplication();
			$app->enqueueMessage(JText::_('No extension selected'));
		}
	}
	
	function purge() {
		$db =& JFactory::getDBO();
		$db->setQuery('DELETE FROM #__extensions WHERE state = -1');
		if($db->Query()) {
			$this->_message = JText::_('Purged discovered extensions');
			return true;
		} else {
			$this->_message = JText::_('Failed to purge extensions');
			return false;
		}
	}
}