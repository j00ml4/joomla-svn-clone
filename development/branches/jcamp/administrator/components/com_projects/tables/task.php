<?php
/**
 * @version     $Id$
 * @package     Joomla.Administrator
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;
jimport('joomla.database.tablenested'); 

/**
 * @package		Joomla.Administrator
 * @subpackage	com_projects
 */
class ProjectsTableTask extends JTableNested
{	
	/**
	 * @param	JDatabase	A database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__project_tasks', 'id', $db);
	}
	
 	/* Overloaded bind function.
	 *
	 * @param	array		named array
	 * @return	null|string	null is operation was satisfactory, otherwise returns an error
	 * @see		JTable:bind
	 * @since	1.5
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string)$registry;
		}

		return parent::bind($array, $ignore);
	}
	
	/**
	 * Over load the check()
	 * 
	 */
	public function check()
	{		
		// Parent id
		if (!empty($this->parent_id))
		{
			$query = $this->_db->getQuery(true);
			$query->select('COUNT(id)');
			$query->from($this->_tbl);
			$query->where('id = '.$this->parent_id);
			$this->_db->setQuery($query);

			if (!$this->_db->loadResult()) {
				if ($this->_db->getErrorNum())
				{
					$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_CHECK_FAILED', get_class($this), $this->_db->getErrorMsg()));
					$this->setError($e);
				}
				else
				{
					$e = new JException(JText::sprintf('JLIB_DATABASE_ERROR_INVALID_PARENT_ID', get_class($this)));
					$this->setError($e);
				}
				// No parent
				return false;
			}
		}
		
		return true;
	}
}