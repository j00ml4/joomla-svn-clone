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
		/**
	 * Category items data
	 *
	 * @var array
	 */
	protected $_item = null;

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

		$type = JRequest::getCmd('type','list');
		$this->setState('type', $type);
 		$this->context .= '.'.$type;
		
		// project id
		$this->setState('project.id', $app->getUserStateFromRequest('project.id','id'));
/*		
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select('pr.title AS `project`, po.title AS `portfolio`');
		$query->from('#__projects pr');
		$query->join('LEFT','#__categories po ON po.id=pr.catid');
		$query->where('pr.id='.$id);
		$db->setQuery($query);
		$res = $db->loadObject();
		$this->setState('portfolio.title',$res->portfolio);
		$this->setState('project.title',$res->project);
*/
 		parent::populateState();
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

		$query->select($this->getState('list.select', 'u.id, u.name'));
		$query->from('#__users u');
		switch($this->getState('type')) {
			case 'delete' :
			case 'list' :
				$query->join('LEFT','#__project_members pm ON pm.user_id=u.id');
				$query->where('pm.project_id ='.$this->getState('project.id'));
				break;
			case 'assign' :
				$query->join('LEFT','(SELECT pm.user_id FROM #__project_members pm WHERE pm.project_id='.$this->getState('project.id').') AS tmp ON tmp.user_id = u.id');
				$query->where('tmp.user_id IS NULL');
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
}
?>