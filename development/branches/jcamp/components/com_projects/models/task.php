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
	protected $text_prefix = 'COM_PROJECTS';
	//protected $context = 'com_projects.edit.project';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
	 */
	protected function canDelete($record=null, $user=null)
	{
		return ProjectsHelper::can('task.delete', $this->option, $user, $record);
	}

	/**
	 * Method to test whether a record can be edited.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 */
	protected function canEditState($record=null, $user=null)
	{
		return ProjectsHelper::can('task.edit.state', $this->option, $user, $record);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *, $user=null
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication();		
		
		if (!($pk = (int) $app->getUserState($this->option.'.edit.'.$this->getName().'.id'))) {
			$pk = (int) JRequest::getInt('id');
		}
		// id
		$this->setState('task.id', $pk);
		$app->setUserState($this->option.'.edit.'.$this->getName().'.id', $pk);

		// project
		$this->setState('project.id', $app->getUserState('project.id'));

		// task type
		$id = $app->getUserStateFromRequest('task.type', JRequest::getInt('type'));
		$this->setState('task.type', $id);
			
		// id
		$this->setState('Itemid', ProjectsHelper::getMenuItemId());
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
			if (!($pk = $this->getState('task.id'))) {
				return null;	
			}	
			
			// query
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			$query->select('a.project_id');
			$query->from('#__project_tasks AS a');
			$query->where('a.id='.$pk);
			$db->setQuery($query);
						
			// load result
			if (!($pk = $db->loadResult())) {
				return null;
			}
		}
		
		//$this->setState('portfolio.id',$pk);
		$project = JModel::getInstance('Project', 'ProjectsModel');
		return $project->getItem($pk);
	} 
	
	/**
	 * function to get the project
	 * @param $pk
	 */
	public function getParent($pk=null)
	{
		// Get project ID
		if (empty($pk)) {
			// portfolio
			if (!($pk = $this->getState('task.id'))) {
				return null;	
			}	
			
			// query
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			$query->select('a.parent_id');
			$query->from('#__project_tasks AS a');
			$query->where('a.id='.$pk);
			$db->setQuery($query);
						
			// load result
			if (!($pk = $db->loadResult())) {
				return null;
			}
		}
		
		//$this->setState('portfolio.id',$pk);
		$task = JModel::getInstance('Task', 'ProjectsModel');
		return $task->getItem($pk);
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
	public function getForm($data=array(), $loadData = true)
	{
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
	protected function loadFormData()
	{	
		$app = JFactory::getApplication();
		// Check the session for previously entered form data.
		$data = $app->getUserState('com_projects.edit.task.data', array());
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
	public function validate($form, $data)
	{
		return parent::validate($form, $data);
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
		// Initialise variables.
		$itemId = (int) (!empty($itemId)) ? $itemId : $this->getState('task.id');
		
		return parent::getItem($itemId);
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param	JTable	A JTable object.
	 *		$this->_setAccessFilters($form, $data);
	 * @return	void
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$app = JFactory::getApplication();
		$date = &JFactory::getDate();
		$user = &JFactory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias 		= JApplication::stringURLSafe($table->title);
		$table->project_id 	= $this->getState('project.id');
		$table->type		= ($table->type)? $table->type: $this->getState('task.type');
		
		if (empty($table->id)) {
			// Set the values
			$table->created		= $date->toMySQL();
			$table->created_by	= $user->get('id');
			
			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = $this->getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__project_tasks WHERE catid = '.(int)$table->catid);
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		} else {
			// Set the values
			$table->modified	= $date->toMySQL();
			$table->modified_by	= $user->get('id');
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
		$isNew	= true;
		
		// Get a row instance.
		$table = $this->getTable();
		
		// Load the row if saving an existing category.
		if ($pk > 0) {
			$table->load($pk);
			$isNew = false;
		}
		
		// Set the new parent id if set.
		if ($table->parent_id != $data['parent_id']) {
			$table->setLocation($data['parent_id'], 'last-child');
		}
		
		$table->project_id = $this->getState('project.id');
		
		// Bind the data.
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}
		
		$this->prepareTable($table);

		// Bind the rules.
		if (isset($data['rules'])) {
			$rules = new JRules($data['rules']);
			$table->setRules($rules);
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
	
}
?>