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
		return ProjectsHelper::can('project.delete', $this->option, $user, $record);
	}

	/**
	 * Method to test whether a record can be edited.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 */
	protected function canEditState($record=null, $user=null)
	{
		return ProjectsHelper::can('project.edit.state', $this->option, $user, $record);
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
		
		// id
		$pk = $app->getUserState('com_projects.edit.task.id', JRequest::getInt('id'));
		$this->setState('task.id', $pk);
		
		// parent
		$id = JRequest::getInt('parent_id', $app->getUserState('task.parent.id'));
		$this->setState('task.parent.id', $id);
		
		// project
		$id = JRequest::getInt('project_id', $app->getUserState('project.id'));
		$this->setState('project.id', $id);
		
		// category
		$id = JRequest::getInt('catid', $app->getUserState('task.category.id'));
		$this->setState('task.category.id', $id);
		
		// category
		$id = JRequest::getInt('type', $app->getUserState('task.type'));
		$this->setState('task.type', $id);
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
		return parent::getItem($pk);
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
		$date = &JFactory::getDate();
		$user = &JFactory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias 		= JApplication::stringURLSafe($table->title);
		$table->project_id 	= $this->getState('project.id');
		$table->type		= ($table->type)? $table->type: $this->getState('type');

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
}
?>