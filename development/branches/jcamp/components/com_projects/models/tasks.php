<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_project
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Portifolio gallery view to display all projects within a portifolio
 * @author eden & elf
 *
 */
class ProjectsModelTasks extends JModelList
{
	/**
	 * Category items data
	 *
	 * @var array
	 */
	protected $_item = null;

	protected $_siblings = null;

	protected $_children = null;

	protected $_parent = null;

	/**
	 * The category that applies.
	 *
	 * @access	protected
	 * @var		object
	 */
	protected $_project = null;

	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		// Initialise variables.
		$app	= &JFactory::getApplication();
		$params	= JComponentHelper::getParams('com_projects');
		
		// Project		
		if (!($id = (int) $app->getUserState('project.id'))) {
			$id = (int) JRequest::getInt('id');
		}
		$this->setState('project.id', $id);
		$app->setUserState('project.id', $id);

		$app->setUserState('com_projects.edit.task.id',null);
		$app->setUserState('com_projects.edit.task.data',null); 

		// Type
		$type = JRequest::getInt('type', 3); // default => tickets
		$this->setState('task.type', $type);
		$app->setUserState('task.type', $type);
		
		// Parent
/*		if (!($id = $app->getUserState('task.parent.id'))) {
			$id = JRequest::getInt('parent_id');
		}
		$this->setState('task.parent.id', $id);
		$app->setUserState('task.parent.id', $id);
		
		// Category
		if (!($id = $app->getUserState('task.category.id'))) {
			$id = JRequest::getInt('catid');
		}
		$this->setState('task.category.id', $id);
		$app->setUserState('task.category.id', $id); */
		
		
		// Filters
		$this->setState('filter.state',	1);

		// Load the parameters.
		$this->setState('params', $params);
		
		// List state information.
		parent::populateState('a.lft', 'asc');
	}
	
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		$user	= &JFactory::getUser();

		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select required fields from the categories.
		$query->select($this->getState('list.select', 'a.id, a.title, a.lft, a.rgt, a.alias, a.language'));
		$query->from('#__project_tasks AS a');

		// Select category of the task
		$query->select('c.title AS `category`');
		$query->join('LEFT','#__categories AS c ON c.id = a.catid');

		// Select name of creator of the task
		$query->select('CASE WHEN a.created_by_alias = \'\' THEN u.name ELSE a.`created_by_alias` END `created_by`');
		$query->join('LEFT','#__users AS u ON a.created_by = u.id');
		
		// Filter by project.
		if ($project_id = $this->getState('project.id')) {
			$query->where('a.project_id = '.(int) $project_id);
			$query->join('LEFT', '#__projects AS p ON p.id = a.project_id');
		}
		
		// Filter by parent
		if ($parent_id = $this->getState('parent.id')) {
			$query->where('a.parent_id = '.(int) $project_id);
			$query->join('LEFT', '#__projects AS p ON p.id = a.project_id');
		}

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the users for the author.
		$query->select('ua.name AS author_name');
		$query->join('LEFT', '#__users AS ua ON ua.id = a.created_by');
		
		// Filter by state
		if ($state = $this->getState('filter.state')) {
			$query->where('a.state >= '.(int) $state);
		}
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else if (stripos($search, 'author:') === 0) {
				$search = $db->Quote('%'.$db->getEscaped(substr($search, 7), true).'%');
				$query->where('(ua.name LIKE '.$search.' OR ua.username LIKE '.$search.')');
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('(a.title LIKE '.$search.' OR a.alias LIKE '.$search.' OR a.note LIKE '.$search.')');
			}
		}
		
		// Filter by start and end dates.
		$nullDate = $db->Quote($db->getNullDate());
		$nowDate = $db->Quote(JFactory::getDate()->toMySQL());

		// Filter by language
		if ($this->getState('filter.language')) {
			$query->where('p.`language` IN (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
		}
		
		// filter by type
		$query->where('a.type = '.$this->getState('task.type'));

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'a.title')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));

		return $query;
	}

	/**
	 * Returns a reference to the a Table object, always creating it
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	JTable	A database object
	 * @since	1.6
	*/
	public function getTable($type = 'Task', $prefix = 'ProjectsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * function to get the project
	 * @param $pk
	 */
	public function getProject($pk=null)
	{
		// Get project ID
		if (empty($pk)) {
			// portfolio
			if (!($pk = $this->getState('project.id'))) {
				return null;	
			}	
		}
		
		$project = JModel::getInstance('Project', 'ProjectsModel');
		return $project->getItem($pk);
	} 
	

	/**
	 * Get the parent categorie.
	 *
	 * @param	int		An optional category id. If not supplied, the model state 'portfolio.id' will be used.
	 *
	 * @return	mixed	An array of categories or false if an error occurs.
	 */
	public function getParent()
	{
		if(!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_parent;
	}

	/**
	 * Get the sibling (adjacent) categories.
	 *
	 * @return	mixed	An array of categories or false if an error occurs.
	 */
	function &getLeftSibling()
	{
		if(!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_leftsibling;
	}

	function &getRightSibling()
	{
		if(!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_rightsibling;
	}

	/**
	 * Get the child categories.
	 *
	 * @param	int		An optional category id. If not supplied, the model state 'portfolio.id' will be used.
	 *
	 * @return	mixed	An array of categories or false if an error occurs.
	 */
	function &getChildren()
	{
		if(!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_children;
	}
}
?>