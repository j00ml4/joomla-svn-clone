<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Projects component
 *
 * @package		Joomla.Site
 * @subpackage	com_projects
 * @since		1.6
 */
class ProjectsViewTasks extends JView
{
	protected $items;
	protected $project;
	protected $states;
	protected $maxLevel;
	protected $params;
	protected $pagination;
	protected $canDo;
	protected $prefix;
	
	/**
	 * Display View
	 * @param $tpl
	 */
	function display($tpl = null)
	{
		$app		= JFactory::getApplication();
		$model 		= $this->getModel();
			
		// Get some data from the models
		$this->items		= $model->getItems();
		$this->states		= $this->get('States');
		$this->project		= $model->getProject();
		$this->pagination	= $model->getPagination();
		$this->params		= $app->getParams();
		$this->canDo	= ProjectsHelper::getActions(
			$app->getUserState('portfolio.id'), 
			$app->getUserState('project.id'),
			$this->project);
			
		$layout = $this->getLayout();
		switch($layout){
			default:
				$layout = 'default';
				// Get project
				if(empty($this->project)){
					return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
				}
				break;
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			return JError::raiseError(500, implode("\n", $errors));
		}
		
		// add 'home page' of our component breadcrumb
	  	$bc = $app->getPathway();
	  	$bc->addItem($this->project->title, 'index.php?option=com_projects&view=project&id='.$this->project->id);
	  	$bc->addItem(JText::_('COM_PROJECTS_TASKS'));

	  	// set a correct prefix
		$this->loadHelper('tasks');
		$this->type = TasksHelper::getPrefix($model->getState('type'));

	  	// TMLP
	  	$this->setLayout($layout);
	  	$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar() 
	{
		$this->loadHelper('toolbar');
		
		switch ($this->state('type')){
			case 1:
				
				break;
			case 2:	
		}
		$title = JText::_('COM_PROJECTS_TASKS_LIST_');
		$icon = 'archive';
		if($this->canDo->get('core.edit')){
			ToolBar::editList('project.edit');
		}
		if($this->item->state && $this->canDo->get('core.edit.state')){
			ToolBar::unpublish('project.unpublish');
		}else{
			if($this->canDo->get('core.edit.state')){
				ToolBar::publish('project.publish');
			}
			if($this->canDo->get('core.delete')){
				ToolBar::deleteList(JText::_('COM_PROJECTS_CONFIRM_PROJECT_DELETE'), 'project.delete');
			}
		}
		if($this->params->get('show_back_button')){
			ToolBar::back();
		}
		
		ToolBar::title($title, $icon);
		
		echo ToolBar::render();
	}
}
