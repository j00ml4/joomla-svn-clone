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
	protected $text_prefix = 'COM_PROJECTS_TASKS';
    protected $project;
    protected $parent;
	
    
    
    public function getType(){
    	return ($this->getState('type') == 3)? 
    		'ticket': 
    		'task';				
    }
        
    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState() {
    	$app = &JFactory::getApplication();
    	 
        $value = $app->getUserStateFromRequest('task.type', 'type', 2);
        $this->setState('type', $value); 
    	$this->context .= $value;
               
    	$value = JRequest::getInt('parent_id', 0);
        $this->setState('parent.id', $value);  
    	$this->context .= '.'.$value;
    	
        // Project
        if (!($id = (int) $app->getUserState('project.id'))) {
            $id = (int) JRequest::getInt('id');
        }
        $this->setState('project.id', $id);
        $app->setUserState('project.id', $id);

        // Filters
        $value = $app->getUserStateFromRequest($this->context.'.state', 'filter_state');
        $this->setState('filter.state', $value);
       
        $value = $app->getUserStateFromRequest($this->context.'.catid', 'filter_catid');
        $this->setState('filter.catid', $value);
   
        $value = JRequest::getString('filter_search');
        $this->setState('filter.search', $value);  
        
        parent::populateState('a.lft', 'asc');
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return	string	An SQL query
     * @since	1.6
     */
    protected function getListQuery() {
        $user = &JFactory::getUser();

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select required fields from the categories.
        $query->select($this->getState('list.select', 'a.*'));
        $query->from('#__project_tasks AS a');

        // Select category of the task
        $query->select('c.title AS `category_title`');
        $query->join('LEFT', '#__categories AS c ON c.id = a.catid');

        // Select name of creator of the task
        $query->select('CASE WHEN a.created_by_alias = \'\' THEN u.name ELSE a.`created_by_alias` END `created_by`');
        $query->join('LEFT', '#__users AS u ON a.created_by = u.id');

        // Join over the users for the checked out user.
        $query->select('uc.name AS editor');
        $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

        // Join over the asset groups.
        $query->select('ag.title AS access_level');
        $query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

        // Join over the users for the author.
        $query->select('ua.name AS author_name');
        $query->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

    	// Filter by project
        if ($value = $this->getState('project.id')) {
            $query->where('a.project_id = ' . (int) $value);
            $query->join('LEFT', '#__projects AS p ON p.id = a.project_id');
        }
        
    	// Filter by parent
        if ($value = $this->getState('parent.id')) {
            $query->where('a.parent_id = ' . (int) $value);
        }
        
        // Filter by state
        if ($value = $this->getState('filter.state')) {
        	//var_dump($value);
        	$query->where('a.state = ' . (int) $value);
        }

    	// Filter by state
        if ($value = $this->getState('filter.catid')) {
        	$query->where('a.catid = ' . (int) $value);
        }
        
        // Filter by search in title
        $value = $this->getState('filter.search');
        if (!empty($value)) {
            if (stripos($value, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($value, 3));
            } else if (stripos($value, 'author:') === 0) {
                $value = $db->Quote('%' . $db->getEscaped(substr($value, 7), true) . '%');
                $query->where('(ua.name LIKE ' . $value . ' OR ua.username LIKE ' . $value . ')');
            } else {
                $value = $db->Quote('%' . $db->getEscaped($value, true) . '%');
                $query->where('(a.title LIKE ' . $value . ')');
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
        $type = $this->getState('type');
        switch ($type) {
            // tickets
            case 3:
                $query->where('a.type = 3');
                break;
			
            // tasks
            case 1:
            case 2:
            default:
                $query->where('a.type != 3');
                break;
        }


        // Add the list ordering clause.
        $query->order( 
        	$db->getEscaped(
        		$this->getState('list.ordering', 'a.title')) . ' ' . 
        		$db->getEscaped($this->getState('list.direction', 'ASC')));
				
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
    public function getTable($type = 'Task', $prefix = 'ProjectsTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    
    /**
     * function to get the project
     * @param $pk
     */
    public function getParent() 
    {
    	$pk = $this->getState('parent.id');
        if (empty($this->parent) && $pk) {
	        $model = JModel::getInstance('Task', 'ProjectsModel');
	        $this->parent = $model->getItem($pk);
        }
        
        return $this->parent;
    }
    
	/**
	 * function to get the project
	 * @param $pk
	 */
	public function getProject()
	{	
		$id = $this->getState('project.id');
		if (empty($this->project) && $id) {
			$app = JFactory::getApplication();
			$model = JModel::getInstance('Project', 'ProjectsModel');
			$this->project = $model->getItem($id);
			$this->setState('portfolio.id', $this->project->catid);
		}
		
		return $this->project;
	} 	

}
?>