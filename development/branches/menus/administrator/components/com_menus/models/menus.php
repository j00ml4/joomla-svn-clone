<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
jimport('joomla.database.query');

/**
 * Menu List Model for Menus.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @since		1.6
 */
class MenusModelMenus extends JModelList
{
	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context = 'com_menus.menus';

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function _getListQuery()
	{
		// Create a new query object.
		$query = new JQuery;

		// Select all fields from the table.
		$query->select($this->getState('list.select', 'a.*'));
		$query->from('`#__menu_types` AS a');

		// Self join to find the number of published menu items in the menu.
		$query->select('COUNT(DISTINCT m1.id) AS count_published');
		$query->join('LEFT', '`#__menu` AS m1 ON m1.menutype = a.menutype AND m1.published = 1');

		// Self join to find the number of unpublished menu items in the menu.
		$query->select('COUNT(DISTINCT m2.id) AS count_unpublished');
		$query->join('LEFT', '`#__menu` AS m2 ON m2.menutype = a.menutype AND m2.published = 0');

		// Self join to find the number of trashed menu items in the menu.
		$query->select('COUNT(DISTINCT m3.id) AS count_trashed');
		$query->join('LEFT', '`#__menu` AS m3 ON m3.menutype = a.menutype AND m3.published = -2');

		$query->group('a.id');

		// Add the list ordering clause.
		$query->order($this->_db->getEscaped($this->getState('list.ordering', 'a.id')).' '.$this->_db->getEscaped($this->getState('list.direction', 'ASC')));

		//echo nl2br(str_replace('#__','jos_',$query->toString())).'<hr/>';
		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @return	void
	 */
	protected function _populateState()
	{
		// Initialize variables.
		$app	= &JFactory::getApplication('administrator');

		// Load the list state.
		$this->setState('list.start',		$app->getUserStateFromRequest($this->_context.'.list.start', 'limitstart', 0, 'int'));
		$this->setState('list.limit',		$app->getUserStateFromRequest($this->_context.'.list.limit', 'limit', $app->getCfg('list_limit', 25), 'int'));
		$this->setState('list.ordering',	$app->getUserStateFromRequest($this->_context.'.list.ordering', 'filter_order', 'a.id', 'cmd'));
		$this->setState('list.direction',	$app->getUserStateFromRequest($this->_context.'.list.direction', 'filter_order_Dir', 'ASC', 'word'));
	}

	/**
	 * Gets a list of all mod_mainmenu modules and collates them by menutype
	 *
	 * @return	array
	 */
	function &getModules()
	{
		$model	= &JModel::getInstance('Menu', 'MenusModel', array('ignore_request' => true));
		$result	= &$model->getModules();

		return $result;
	}

}
