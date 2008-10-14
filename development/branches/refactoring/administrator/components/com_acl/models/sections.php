<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_acl
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT.DS.'models'.DS.'_prototypelist.php';

/**
 * @package		Joomla.Administrator
 * @subpackage	com_acl
 */
class AccessModelSections extends AccessModelPrototypeList
{
	/**
	 * Valid types
	 */
	function isValidType($type)
	{
		$types	= array('acl', 'aro', 'aco', 'axo');
		return in_array($type, $types);
	}

	/**
	 * Gets a list of objects
	 *
	 * @param	boolean	True to resolve foreign keys
	 *
	 * @return	string
	 */
	function _getListQuery($resolveFKs = false)
	{
		if (empty($this->_list_sql))
		{
			$db			= &$this->getDBO();
			$query		= new JQuery;
			$type		= $this->getState('list.section_type');
			$select		= $this->getState('list.select', 'a.*');
			$search		= $this->getState('list.search');
			$where		= $this->getState('list.where');
			$orderBy	= $this->getState('list.order');

			if (!$this->isValidType($type)) {
				return JError::raiseError(500, $type.' is not a valid section type');
			}

			$query->select($select);
			$query->from('#__core_acl_'.$type.'_sections AS a');

			if ($resolveFKs) {
				// No foreign keys
			}

			// Search in the name
			if ($search) {
				$serach = $db->Quote('%'.$db->getEscaped($search, true).'%', false);
				$query->where('a.name LIKE '.$serach);
			}

			// An abritrary where clause
			if ($where) {
				$query->where($where);
			}

			if ($orderBy) {
				$query->order($this->_db->getEscaped($orderBy));
			}

			$this->_list_sql = (string) $query;
			//echo nl2br($this->_list_sql);
		}

		return $this->_list_sql;
	}
}