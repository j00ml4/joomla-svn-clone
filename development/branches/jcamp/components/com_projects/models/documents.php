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
 * Model to display all documents belonging to a project
 * @author elf
 *
 */
class ProjectsModelDocuments extends JModelList
{
	protected $project;
    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return	void
     * @since	1.6
     */
    protected function populateState() {
    	parent::populateState();
    	
        $app = JFactory::getApplication();
		
        // project id
		$this->setState('project.id', 
			$app->getUserStateFromRequest('project.id','id'));
        
		$params = $app->getParams();
        $this->setState('params', $params);

        $this->setState('filter.published', 1);

        $this->setState('filter.language', $app->getLanguageFilter());

        // process show_noauth parameter
        if (!$params->get('show_noauth')) {
            $this->setState('filter.access', true);
        } else {
            $this->setState('filter.access', false);
        }
    }

    /**
     * @param	boolean	True to join selected foreign information
     *
     * @return	string
     * @since	1.6
     */
    function getListQuery() {
        $app = &JFactory::getApplication();
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);        
		
        // Select the required fields from the table.
        $query->select(
                $this->getState('list.select',
                        'a.id, a.title, a.alias, a.title_alias, a.state, a.catid, a.access, ' .
                        // use created if modified is 0
                        'CASE WHEN a.modified = 0 THEN a.created ELSE a.modified END as modified,' .
                        'a.modified_by, uam.name as modified_by_name,' .
                		'a.created, a.created_by, a.created_by_alias AS author,' .
                        // use created if publish_up is 0
                        'CASE WHEN a.publish_up = 0 THEN a.created ELSE a.publish_up END as publish_up'
                )
        );
        $query->from('#__content AS a');
		
        // Join over the categories.
        $query->select('c.title AS category_title, c.path AS category_route, c.alias AS category_alias');
        $query->join('LEFT', '#__categories AS c ON c.id = a.catid');
		
        // Join over the users for the author and modified_by names.
        $query->select("CASE WHEN a.created_by_alias > ' ' THEN a.created_by_alias ELSE ua.name END AS author");

        $query->join('LEFT', '#__users AS ua ON ua.id = a.created_by');
        $query->join('LEFT', '#__users AS uam ON uam.id = a.modified_by');

        // Join over the categories to get parent category titles
        $query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias');
        $query->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');
     		
		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		
		// Filter by access level.
		$value = $this->getState('filter.access');
		if ($value) {
			$user = JFactory::getUser();
			$value = $user->authorisedLevels();
			$query->where('a.access IN ('. implode(',', $value) .')');
		}	
		
        $query->select('p.project_id AS project_id');
        $query->join('left', '#__project_contents AS p ON p.content_id=a.id');
       	$query->where('p.project_id = '. $db->quote($this->getState('project.id')));

        // Add the list ordering clause.
        $query->order($this->getState('list.ordering', 'a.ordering') . ' ' . $this->getState('list.direction', 'ASC'));

        return $query;
    }

    /**
     * Returns a Table object, always creating it
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     */
    public function getTable($type = 'Content', $prefix = 'ProjectsTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
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