<?php
/**
 * @version		$Id: tableasset.php 11952 2009-06-01 03:21:19Z robs $
 * @package		Joomla.Framework
 * @subpackage	Database
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.database.table');

/**
 * Table class supporting pre-order traversal tree behaviour
 *
 * @package		Joomla.Framework
 * @subpackage	Database
 * @since		1.6
 */
class JTableTree extends JTable
{
	/**
	 * @var int Primary key
	 */
	public $id = null;

	/**
	 * @var int Reference to the parnet id
	 */
	public $parent_id = null;

	/**
	 * @var int The level in the tree
	 */
	public $level = null;

	/**
	 * @var string The path alias
	 */
	public $alias = null;

	/**
	 * @var int
	 */
	public $left_id = null;

	/**
	 * @var int
	 */
	public $right_id = null;

	/**
	 * @var int
	 */
	public $published = null;

	/**
	 * @var int
	 */
	public $ordering = null;

	/**
	 * @var int
	 */
	public $access = null;

	/**
	 * Method to recursively rebuild the nested set tree.
	 *
	 * @access	public
	 * @param	integer	The root of the tree to rebuild.
	 * @param	integer	The left id to start with in building the tree.
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function rebuild($parent_id = 0, $left = 0, $level = 0)
	{
		// get the database object
		$db = &$this->_db;

		// get all children of this node
		$db->setQuery(
			'SELECT id FROM '. $this->_tbl .
			' WHERE parent_id = '. (int) $parent_id .
			' ORDER BY parent_id, ordering, title'
		);
		$children = $db->loadResultArray();

		// the right value of this node is the left value + 1
		$right = $left + 1;

		// execute this function recursively over all children
		for ($i = 0, $n = count($children); $i < $n; $i++)
		{
			// $right is the current right value, which is incremented on recursion return
			$right = $this->rebuild($children[$i], $right, $level + 1);

			// if there is an update failure, return false to break out of the recursion
			if ($right === false) {
				return false;
			}
		}

		// we've got the left value, and now that we've processed
		// the children of this node we also know the right value
		$db->setQuery(
			'UPDATE '. $this->_tbl .
			' SET left_id = '. (int) $left .
			' , right_id = '. (int) $right .
			' , level = '. (int) $level .
			' WHERE id = '. (int) $parent_id
		);
		// if there is an update failure, return false to break out of the recursion
		if (!$db->query()) {
			return false;
		}

		// return the right value of this node + 1
		return $right + 1;
	}

	/**
	 * Inserts a new row if id is zero or updates an existing row in the database table
	 *
	 * @access	public
	 * @param	boolean		If false, null object variables are not updated
	 * @return	boolean 	True successful, false otherwise and an internal error message is set`
	 */
	function store($updateNulls = false)
	{
		if ($result = parent::store($updateNulls))
		{
			// Get the ordering values for the group.
			$this->_db->setQuery(
				'SELECT `id`' .
				' FROM `'.$this->_tbl.'`' .
				' WHERE `parent_id` = '.(int) $this->parent_id .
				' ORDER BY `ordering`, `title`'
			);
			$ordering = $this->_db->loadResultArray();

			// Check for a database error.
			if ($this->_db->getErrorNum()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			// If the ordering has not changed, return true.
			$offset = array_search($this->id, $ordering);
			if ($offset === (int)$this->ordering) {
				return true;
			}

			// The item is set to be ordered first.
			if ($this->ordering == -1)
			{
				// Remove the current item from the ordering array.
				if ($offset !== false) {
					unset($ordering[$offset]);
					$ordering = array_values($ordering);
				}
				array_unshift($ordering, $this->id);
			}
			// The item is set to be ordered last.
			elseif ($this->ordering == -2)
			{
				// Remove the current item from the ordering array.
				if ($offset !== false) {
					unset($ordering[$offset]);
					$ordering = array_values($ordering);
				}
				array_push($ordering, $this->id);
			}
			// Use the ordering value given for the particular item.
			else {
				// Setup the ordering array.
				$ordering = array_merge(array_slice($ordering, 0, $offset), array_slice($ordering, $offset+1, 1), (array)$this->id, array_slice($ordering, $offset+2));
			}

			// Iterate through the categories and set th ordering.
			foreach ($ordering as $k => $v)
			{
				// Set the ordering for the category.
				$this->_db->setQuery(
					'UPDATE `'.$this->_tbl.'`' .
					' SET `ordering` = '.(int)$k .
					' WHERE `id` = '.(int)$v
				);
				$this->_db->query();

				// Check for a database error.
				if ($this->_db->getErrorNum()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}

			// Rebuild the nested set tree.
			$this->rebuild();
		}

		return $result;
	}

	function buildPath($nodeId=null)
	{
		// get the node id
		$nodeId = (empty($nodeId)) ? $this->id : $nodeId;

		// get the database object
		$db = &$this->_db;

		// get all children of this node
		$db->setQuery(
			'SELECT parent.alias FROM '.$this->_tbl.' AS node, '.$this->_tbl.' AS parent' .
			' WHERE node.left_id BETWEEN parent.left_id AND parent.right_id' .
			' AND node.id='. (int) $nodeId .
			' ORDER BY parent.left_id'
		);
		$segments = $db->loadResultArray();

		// make sure the root node doesn't appear in the path
		if ($segments[0] == 'root') {
			array_shift($segments);
		}

		// build the path
		$path = trim(implode('/', $segments), ' /\\');

		$db->setQuery(
			'UPDATE '. $this->_tbl .
			' SET path='. $db->Quote($path) .
			' WHERE id='. (int) $nodeId
		);
		// if there is an update failure, return false to break out of the recursion
		if (!$db->query()) {
			return false;
		}

		return true;
	}

	/**
	 * Delete this object and it's dependancies
	 */
	function delete($oid = null)
	{
		$k = $this->_tbl_key;

		if ($oid) {
			$this->load($oid);
		}
		if ($this->id == 0) {
			return new JException(JText::_('Row not found'));
		}
		if ($this->parent_id == 0) {
			return new JException(JText::_('Root rows cannot be deleted'));
		}
		if ($this->left_id == 0 or $this->right_id == 0) {
			return new JException(JText::_('Left-Right data inconsistency. Cannot delete row.'));
		}

		$db = &$this->getDBO();

		// Select the category ID and it's children
		$db->setQuery(
			'SELECT c.id' .
			' FROM `'.$this->_tbl.'` AS c' .
			' WHERE c.left_id >= '.(int) $this->left_id.' AND c.right_id <= '.$this->right_id
		);
		$ids = $db->loadResultArray();
		if (empty($ids)) {
			return new JException(JText::_('Left-Right data inconsistency. Cannot delete row.'));
		}
		$ids = implode(',', $ids);

		// Delete the category and it's children
		$db->setQuery(
			'DELETE FROM `'.$this->_tbl.'`' .
			' WHERE id IN ('.$ids.')'
		);
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		return true;
	}

	/**
	 * Generic Publish/Unpublish function
	 *
	 * @access	public
	 * @param	array	An array of id numbers
	 * @param	integer	0 if unpublishing, 1 if publishing
	 * @param	integer	The id of the user performnig the operation
	 */
	function publish($cid = null, $publish = 1, $userId = 0)
	{
		JArrayHelper::toInteger($cid);
		$userId		= (int) $userId;
		$publish	= (int) $publish;
		$k			= $this->_tbl_key;
		$db			= &$this->getDBO();
		$this->setError('');

		if (count($cid) < 1)
		{
			if ($this->$k) {
				$cid = array($this->$k);
			}
			else {
				$this->setError('No items selected.');
				return false;
			}
		}

		$temp2 = clone($this);

		// If unpublishing or trashing, we need to cascade
		foreach ($cid as $id)
		{
			$this->load($id);

			// ensure that subcats are not checked out
			$db->setQuery(
				'SELECT COUNT(c.id)' .
				' FROM `'.$this->_tbl.'` AS c' .
				' WHERE ((c.left_id > '.(int) $this->left_id.' AND c.right_id <= '.$this->right_id .') OR id = '.$id.')'.
				' AND (checked_out <> 0 AND checked_out <> '.(int) $userId.')'
			);
			if ($db->loadResult()) {
				$this->setError('Cannot unpublish or trash because parts of tree are checked out.');
				return false;
			}

			// ensure that the parent is ok
			if ($this->parent_id) {
				$temp2->load($this->parent_id);
				if ($temp2->parent_id > 0 && $temp2->published < $publish) {
					$this->setError('Cannot published or unpublish because part of the tree higher up are unpublished or trashed.');
					return false;
				}
			}

			if ($publish < 1)
			{
				// we are clear to execute
				$db->setQuery(
					'UPDATE `'.$this->_tbl.'` AS c' .
					' SET published = ' . $publish .
					' WHERE (c.left_id > '.(int) $this->left_id.' AND c.right_id < '.$this->right_id.') OR c.id = '.$id
				);
				if (!$db->query()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
			else {
				$db->setQuery(
					'UPDATE '. $this->_tbl .
					' SET published = ' . $publish .
					' WHERE id = '. $id
				);
				if (!$db->query()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Adjust the item ordering.
	 *
	 * @access	public
	 * @param	integer	Primary key of the item to adjust.
	 * @param	integer	Increment, usually +1 or -1
	 * @return	boolean	True on success.
	 * @since	1.0
	 */
	function ordering($move, $id = null)
	{
		// Sanitize arguments.
		$id = (int) (empty($id)) ? $this->id : $id;
		$move = (int) $move;

		// Get the parent id for the item.
		$this->_db->setQuery(
			'SELECT `parent_id`' .
			' FROM `'.$this->_tbl.'`' .
			' WHERE `id` = '.(int)$id
		);
		$parentId = (int) $this->_db->loadResult();

		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Get the ordering values for the group.
		$this->_db->setQuery(
			'SELECT `id`' .
			' FROM `'.$this->_tbl.'`' .
			' WHERE `parent_id` = '.(int)$parentId .
			' ORDER BY `ordering`, `title`'
		);
		$ordering = $this->_db->loadResultArray();

		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Only run the move logic if we are actually moving the item.
		if ($move != 0)
		{
			// Build a new array with the modified ordering values.
			$tmp = array();
			$idx = array_search($id, $ordering);
			foreach ($ordering as $k => $v)
			{
				if ($k == $idx) {
					continue;
				}
				else {
					if ($move > 0) {
						$tmp[] = $v;
					}
					if (($idx + $move) == $k) {
						$tmp[] = $ordering[$idx];
					}
					if ($move < 0) {
						$tmp[] = $v;
					}
				}
			}

			// Iterate through the categories and set th ordering.
			foreach ($tmp as $k => $v)
			{
				// Set the ordering for the category.
				$this->_db->setQuery(
					'UPDATE `'.$this->_tbl.'`' .
					' SET `ordering` = '.(int)$k .
					' WHERE `id` = '.(int)$v
				);
				$this->_db->query();

				// Check for a database error.
				if ($this->_db->getErrorNum()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}

			// Rebuild the nested set tree.
			$this->rebuild();
		}

		return true;
	}
}
