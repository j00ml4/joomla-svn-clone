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

//jimport('joomla.application.component.modeladmin');
require_once 'components'.DS.'com_content'.DS.'models'.DS.'form.php';

/**
 * Model to display editing form for a document
 * @author elf
 *
 */
class ProjectsModelDocument extends ContentModelForm
{
	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context = 'com_projects.edit.document';
	protected $project;
	protected $item;
	
	
	public function __construct($config){
		parent::__construct($config);
		
		$this->addTablePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_content'.DS.'tables');
	}
	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
	 */
	protected function canDelete($record)
	{	
		$app = JFactory::getApplication();
		return ProjectsHelper::canDo('document.delete',  
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
		return ProjectsHelper::canDo('document.edit', 
			$app->getUserState('portfolio.id'), 
			$app->getUserState('project.id'),
			$record);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		parent::populateState();
		$app = JFactory::getApplication();
		
		// project
        $this->setState('project.id', 
        	$app->getUserState('project.id'));

        //$this->setState('article.id', 	
       // $app->setUserState($this->_context.'id', null);
		//$app->setUserState($this->_context.'data',	null);	
	}

	
	
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	*/
	public function getTable($type = 'Content', $prefix = 'ProjectsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method to get the login form.
	 *
	 * The base form is loaded from XML and then an event is fired
	 * for users plugins to extend the form with extra fields.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_projects.document', 'document', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		
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
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState($this->_context.'.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
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
			$this->item = parent::getItem($pk);
			
			if(!empty($this->item)){
				$app = JFactory::getApplication();				
				if($this->item->id){
					$db = $this->getDbo();
					$query = $db->getQuery(true);
					$query->select('project_id');
					$query->from('#__project_contents');
					$query->where('content_id='.(int)$this->item->id);
					$db->setQuery($query);				
					$this->item->project_id = $db->loadResult();
					$this->setState('project.id', $this->item->project_id);
					$app->setUserState('project.id', $this->item->project_id);
				}
				
				// Convert parameter fields to objects.
				$registry = new JRegistry;
				$registry->loadJSON($this->item->attribs);
				$this->item->params = clone $this->getState('params');
				$this->item->params->merge($registry);
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
	
	public function save($data){
		$isNew = !$data['id'];
		if(!parent::save($data)){
			return false;
		}
		
		if($isNew){
			$db = $this->getDbo();
			$id = $this->getState('article.id', $db->insertid());
			$app= JFactory::getApplication();		
			$q = 'INSERT INTO `#__project_contents`'. 
				'(`project_id`,`content_id`)'. 
				'VALUES ('.$this->getState('project.id').', '.$id.')';		
			$db->setQuery($q);
			$db->query();
			if($db->getErrorNum()){
				return false;
			}
		}	
		return true;
	}
	
	public function delete($pks){
		if(!parent::delete($pks)){
			return false;
		}
					
		$app = JFactory::getApplication();
		$db = $this->getDbo();
		$q = 'DELETE FROM `#__project_contents` WHERE `content_id` IN ('.implode(',', $pks).')';		
		$db->setQuery($q);
		$db->query($q);
		if($db->getErrorNum()){
			return false;
		}	
		return true;
	}
	
}