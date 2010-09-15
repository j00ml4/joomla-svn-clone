<?php

/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	Projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Project model to display or edit a project
 * @author eden
 *
 */
class ProjectsModelTask extends JModelAdmin
{
    protected $text_prefix = 'COM_PROJECTS_TASK';
    protected $context = 'com_projects.edit.task';
	protected $project;
	protected $parent;
	protected $item;
	
	protected $children;
	protected $children_model;
	
	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
	 */
	protected function canDelete($record)
	{	
		$app = JFactory::getApplication();
		return ProjectsHelperACL::canDo($this->getType().'.delete',  
			$app->getUserState('portfolio.id'), 
			$app->getUserState('project.id'),
			$record);
	}

	/**
	 * Method to test whether a record can be edited.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 */
	protected function canEditState($record)
	{
		return $this->canEdit($record);
	}
	
	/**
	 * Method to test whether a record can be edited.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 */
	protected function canEdit($record)
	{
		$app = JFactory::getApplication();
		return ProjectsHelperACL::canDo($this->getType().'.edit', 
			$app->getUserState('portfolio.id'), 
			$app->getUserState('project.id'),
			$record);
	}

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     * , $user=null
     * @return	void
     * @since	1.6
     */
    protected function populateState() {
        $app = JFactory::getApplication();

        // curent task
        if (!($pk = (int) $app->getUserState($this->context.'.id'))) {
            $pk = (int) JRequest::getInt('id');
        }
        $this->setState('task.id', $pk);
        $app->setUserState('task.id', $pk);

        // project
        $this->setState('project.id', $app->getUserState('project.id'));

        // task type
        $this->setState('type', 
        	$app->getUserStateFromRequest('task.type', 'type'));
    }
	
    public function getType(){
    	return ($this->getState('type') == 3)? 
    		'ticket': 
    		'task';				
    }
	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{	
		if(!is_object($this->item)){
			$app = JFactory::getApplication();
			$this->item = parent::getItem($pk);
			if(!empty($this->item->id)){
				$this->setState('project.id', $this->item->project_id);
				$app->setUserState('project.id', $this->item->project_id);
				//$this->setState('type', $this->item->type);
				//$app->setUserState('task.type', $this->item->type);
								
				
				$q = $db->getQuery(true);
				$q->select('c.title as `category_title`');
				$q->select('ua.name as `created_by`');
				$q->select('ue.name as `finished_by`');
				$q->from('#__project_tasks AS a');
				$q->where('id = '.(int)$this->item->id);
				
				$q->join('left','#__categories AS `c` ON `c`.id = a.catid');
				$q->join('left','#__users AS `ua` ON `ua`.id = a.created_by');
				$q->join('left','#__users AS `ue` ON `ue`.id = a.finished_by');
				
				$db->setQuery($q);
				$result = $db->loadObject();
				
				$this->item->author = $result->created_by;
				$this->item->editor = $result->modified_by;
				$this->item->category_title = $result->category_title;
				
			}else{						
				$date = &JFactory::getDate();
				$this->item->start_at = $date->toMySQL();
			}
		}
		return $this->item;
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
			$app->setUserState('portfolio.id', $this->project->catid);
		}
		
		return $this->project;
	} 	

    /**
     * function to get the project
     * @param $pk
     */
    public function getParent() {
        // Get project ID
        if (empty($this->parent)) {
        	$pk  = $this->getItem()->parent_id;
           	$this->parent = parent::getItem($pk);
        }

        return $this->parent;
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
     * Method to get a form object.
     *
     * @access	public
     * @param	string		$xml		The form data. Can be XML string if file flag is set to false.
     * @param	array		$options	Optional array of parameters.
     * @param	boolean		$clear		Optional argument to force load a new form.
     *
     * @return	mixed		JForm object on success, False on error.
     * @since	1.6
     */
    public function getForm($data=array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm('com_projects.task', 'task', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        //dump($data);
        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData() {
        $app = JFactory::getApplication();
        // Check the session for previously entered form data.
        $data = $app->getUserState($this->context.'.data', array());
        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }

    /**
     * Method to validate the form data.
     *
     * @param	object		$form		The form to validate against.
     * @param	array		$data		The data to validate.
     *
     * @return	mixed		Array of filtered data if valid, false otherwise.
     */
    public function validate($form, $data) {
        return parent::validate($form, $data);
    }

    /**
     * Prepare and sanitise the table data prior to saving.
     *
     * @param	JTable	A JTable object.
     * 		$this->_setAccessFilters($form, $data);
     * @return	void
     * @since	1.6
     */
    protected function prepareTable(&$table) {
        jimport('joomla.filter.output');
        $app = JFactory::getApplication();
        $date = &JFactory::getDate();
        $user = &JFactory::getUser();

        $table->title = htmlspecialchars_decode($table->title, ENT_QUOTES);
        $table->alias = JApplication::stringURLSafe($table->title);
        $table->project_id = $this->getState('project.id');
        $table->type = ($table->type) ? $table->type : $this->getState('type'); 
        
        if (empty($table->id)) {
            // Set the values
            $table->created = $date->toMySQL();
            $table->created_by = $user->get('id');
            if($table->type == 3){
            	$table->state = -3;
            }else{
            	$table->state = 1;
            }
            
            // Set ordering to the last item if not set
            if (empty($table->ordering)) {
                $db = $this->getDbo();
                $db->setQuery('SELECT MAX(ordering) FROM #__project_tasks WHERE catid = ' . (int) $table->catid);
                $max = $db->loadResult();

                $table->ordering = $max + 1;
            }
        } else {
            // Set the values
            $table->modified = $date->toMySQL();
            $table->modified_by = $user->get('id');
        }
    }

	/**
	 * Method to save the form data.
	 *
	 * @param	array	The form data.
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function save($data)
	{
		$pk		= (!empty($data['id'])) ? $data['id'] : (int)$this->getState('task.id');
		
		// Get a row instance.
		$table = $this->getTable();

		// Load the row if saving an existing category.
		if ($pk > 0) {
			$table->load($pk);
			$isNew = false;
		}

		// Prepare
		$this->prepareTable($table);
		if(empty($data['parent_id'])){
			$data['parent_id'] = 1;
		}
		// Set the new parent id if parent id not matched OR while New/Save as Copy .
		if ($table->parent_id != $data['parent_id'] || $data['id'] == 0) {
			$table->setLocation($data['parent_id'], 'last-child');
		}
		
		
		// Bind the data.
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}


		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}

		// Rebuild the tree path.
		if (!$table->rebuildPath($table->id)) {
			$this->setError($table->getError());
			return false;
		}

		$this->setState('task.id', $table->id);
		return true;
	}
	
	/**
	 * Get the chidren model
	 * Enter description here ...
	 */
	public function getChildren(){
		if(empty($this->children)){
			$item = $this->getItem();
			if(empty($item)){
				return null;
			}
		
			JRequest::setVar('parent_id', $item->id);
			$model = JModel::getInstance('tasks', 'ProjectsModel');
			$model->setState('parent.id', $item->id);
			$this->children = $model;	
		}
		
		return $this->children;
	}
	
	public function getItems()
	{
		$model = $this->getChildren();
		if(empty($model)){
			return null;
		}
		
		return $model->getItems();
	}
	
	public function getPagination(){
		$model = $this->getChildren();
		if(empty($model)){
			return null;
		}
		
		return $model->getPagination();
	}
	
	
	public function publish($pks, $value){
		if($value == 2){
			$db = $this->getDbo();
			$db->setQuery('SELECT COUNT(id) FROM #__project_tasks ' .
				' WHERE parent_id IN ('. implode(',', $pks) .') AND state != 2 AND state != 0'.
					' AND id NOT IN ('. implode(',', $pks) .')');
			
			if($db->loadResult() > 0){
				$this->setError(JText::_($this->text_prefix.'_ERROR_FINISH_PARENT_TASK'));
				return false;
			}
		}
		
		return parent::publish($pks, $value);
	}
	
	
	public function delete($pks){
		if(!parent::delete($pks)){
			return false;
		}

		return true;
	}
	
}
?>