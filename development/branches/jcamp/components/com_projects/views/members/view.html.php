<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

// Imports
jimport('joomla.application.component.view');

/**
 * Display managing users assigned to project view
 */
class ProjectsViewMembers extends JView
{
	protected $items;
	protected $type;
	protected $params;
	protected $canDo;
	protected $project;
	protected $pagination;
	protected $state;
	protected $prefix_text;

	/**
	 * Display managing users assigned to project
	 */
	public function display($tpl = null)
	{
		$app		= JFactory::getApplication();
		$model		= $this->getModel();

		//Get Model data
		$this->items 		= $model->getItems();
		$this->params		= $app->getParams();
		$this->project 		= $model->getProject();
		$this->type			= $model->getState('type');
		$this->pagination	= $model->getPagination();
		$this->state		= $this->get('State');
		$this->canDo		= ProjectsHelper::getActions(
			$app->getUserState('portfolio.id'),
			$app->getUserState('project.id'),
			$this->project);

		if(empty($this->project)){
			return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
		}

		//$this->loadHelper('links');
		$this->links = $this->getLinks();

		// set up type and layout
		$this->setLayout('default');
		
		// Access
		if (!$this->params->get('show_members') && !$this->canDo->get('is.member')){
			return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		// Display the view
		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * 
	 * Enter description here ...
	 * @return multitype:Ambigous <The, NULL, unknown, mixed>
	 * @deprecated
	 */
	protected function getLinks()
	{
		return array(
			'project' => JRoute::_('index.php?option=com_projects&view=project&id='.$this->project->id),
			'assign' => JRoute::_('index.php?option=com_projects&view=members&type=assign&id='.$this->project->id),
			'delete' => JRoute::_('index.php?option=com_projects&view=members&type=list&id='.$this->project->id),
		);
	}

	protected function addToolbar() 
	{
		$this->loadHelper('toolbar');
		
		if($this->canDo->get('core.edit')){
			// set a correct prefix
			switch($this->type)
			{
				case 'assign' :
					$title = JText::_('COM_PROJECTS_MEMBERS_ASSIGN_TITLE');
					ToolBar::assign('project.assign');
					break;
					 
				case 'delete':
				case 'list' :
					$title = JText::_('COM_PROJECTS_MEMBERS_DELETE_TITLE');
					ToolBar::deleteList(JText::_('COM_PROJECTS_CONFIRM_MEMBERS_DELETE'), 'project.unassign');
					break;
	
				default:
					return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
			}
		}else{
			$title = JText::_('COM_PROJECTS_MEMBERS_LIST_TITLE');
		}
		ToolBar::title($title, 'user');
		ToolBar::back();
		
		// Pathway
		$app = JFactory::getApplication();
		$bc = $app->getPathway();
		$bc->addItem($this->project->title,$this->links['project']);
		$bc->addItem($title);
		
		echo ToolBar::render();
	}
}