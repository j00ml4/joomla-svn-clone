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
		
		if(empty($this->parent_id)){
			$this->parent_id = 1;
		}
		
		return parent::bind($array, $ignore);
	}
	
	/**
	 * Over load the check()
	 * 
	 */
	public function check()
	{	
		if(!parent::check()){
			return false;
		}	
		
		if($this->type < 1 || $this->type > 3 ){
			return false;
		}
		
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
	
	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param	mixed	An optional array of primary key values to update.  If not
	 *					set the instance property value is used.
	 * @param	integer The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param	integer The user id of the user performing the operation.
	 * @return	boolean	True on success.
	 * @since	1.0.4
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k.'='.implode(' OR '.$k.'=', $pks);

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
			$checkin = '';
		}
		else {
			$checkin = ' AND (checked_out = 0 OR checked_out = '.(int) $userId.')';
		}
		
		// Consistency
		$date = JFactory::getDate()->toMySQL();
		$update = ' SET `state` = '.(int) $state;
		$update .= ', `modified` = "'. $date .'", `modified_by` = '. (int) $userId;
		switch ($state){
			case 2:
				$update .= ', `finished`="'. $date .'", `finished_by`='. (int) $userId;
				break;
				
			default:
				$update .= ', `finished`=NULL, `finished_by`=NULL';
		}
		
		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery(
			'UPDATE `'.$this->_tbl.'`' . 
			$update . 
			' WHERE ('.$where.')' .
			$checkin
		);
		$this->_db->query();

		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			// Checkin the rows.
			foreach($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks)) {
			$this->state = $state;
		}

		$this->setError('');
		return true;
	}
	
	
	public function store($updateNulls = false){
		// Parents
		$parents = $this->getPath();
		if(!empty($parents)){	
			$pks = array();
			foreach($parents as $i => $item){
				if($item->start_at > $this->start_at || $item->finish_at < $this->finish_at){
					$pks[] = $item->id;
				}
			}
			
			if(!empty($pks)){
				$pks = implode(',', $pks);	
				$db = $this->getDbo();
				
				$query = 'UPDATE #__project_tasks SET start_at = "'. $this->start_at .'"' .
					' WHERE id IN ('. $pks .') AND start_at > "'. $this->start_at .'";';
						
				$query .= 'UPDATE #__project_tasks SET finish_at = "'. $this->finish_at .'"' .
						' WHERE id IN ('. $pks .') AND finish_at < "'. $this->finish_at .'";';
							
				$db->setQuery($query);
				$db->queryBatch();
			}
		}
		
		// Children
		$children = $this->getTree();
		if(!empty($children)){	
			$pks = array();
			foreach($children as $i => $item){
				if($item->start_at < $this->start_at || $item->finish_at > $this->finish_at){
					$pks[] = $item->id;
				}
			}
			
			if(!empty($pks)){
				$pks = implode(',', $pks);	
				$db = $this->getDbo();
				
				$query = 'UPDATE #__project_tasks SET start_at = "'. $this->start_at .'"' .
					' WHERE id IN ('. $pks .') AND start_at < "'. $this->start_at .'";';
						
				$query .= 'UPDATE #__project_tasks SET finish_at = "'. $this->finish_at .'"' .
						' WHERE id IN ('. $pks .') AND finish_at > "'. $this->finish_at .'";';
							
				$db->setQuery($query);
				$db->queryBatch();
			}
		}
		
		// Store
		return parent::store($updateNulls);
	}
}