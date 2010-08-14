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
 * @author elf
 *
 */
class ProjectsModelMembers extends JModelList
{
	protected $project = null;

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
		
		// Initialise variables.
		$app	= &JFactory::getApplication();

		$type = JRequest::getCmd('type','list');
		$this->setState('type', $type);
 		$this->context .= '.'.$type;
		
		// project id
		$this->setState('project.id', $app->getUserStateFromRequest('project.id','id'));
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
		$id 	= (int) $this->getState('project.id');
		$query->select($this->getState('list.select', 'u.id, u.name, u.username, u.email'));
		$query->from('#__users u');
		switch($this->getState('type')) {
			case 'delete' :
			case 'list' :
				$query->join('LEFT','#__project_members pm ON pm.user_id=u.id');
				$query->where('pm.project_id ='.$id);
				break;
			case 'assign' :
				$query->where('u.id NOT IN (SELECT user_id FROM #__project_members WHERE project_id='.$id.')');
				//$query->join('LEFT','(SELECT pm.user_id FROM #__project_members pm WHERE pm.project_id='.$this->getState('project.id').') AS tmp ON tmp.user_id = u.id');
				//$query->where('tmp.user_id IS NULL');
				break;
		}

		// Add the list ordering clause.
		$query->order($db->getEscaped($this->getState('list.ordering', 'u.`name`')).' '.$db->getEscaped($this->getState('list.direction', 'ASC')));
		return $query;
	}	
	
	/**
	 * function to get the project
	 * @param $pk
	 */
	public function getProject()
	{
		if (!is_object($this->project)) {
			$model = JModel::getInstance('Project', 'ProjectsModel');
			$this->project = $model->getItem($this->getState('project.id'));
		}
		
		return $this->project;
	} 	
}
?>