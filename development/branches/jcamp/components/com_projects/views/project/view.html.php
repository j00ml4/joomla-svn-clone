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
 * Display project view
 * @author eden & elf
 */
class ProjectsViewProject extends JView
{
	protected $item;
	protected $form;
	protected $params;
	protected $canDo;
	protected $portfolio;
	
	/**
	 * Display project
	 */
	public function display($tpl = null) 
	{	
		$app		= JFactory::getApplication();
		$model		= $this->getModel();
		
		//Get Model data
		$this->item 	= $model->getItem();
		$this->params	= $app->getParams();
		$this->canDo	= ProjectsHelper::getActions(
			$app->getUserState('portfolio.id'), 
			$app->getUserState('project.id'),
			$this->item);
		
		// Layout
		$layout = $this->getLayout();
		switch($layout){
			// Form
			case 'edit':
			case 'form':
				$layout = 'edit';
				$this->form	= &$model->getForm();
				
				// State
				if (!$this->item->id) {
					$access = 'core.create';
				}else{
					$access = 'core.edit';					
				}
				// Access
				if (!$this->canDo->get($access)){
					return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));				
				}
				break;
			
			// Overview	
			default:
				$layout = 'default';
				// Access
				if (empty($this->item->id)){
					return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
				}
				
				// Get Portfolio
				$this->portfolio = $model->getPortfolio($this->item->catid);
				
				// get tickets and tasks
				$params_tasks = new JRegistry();
				$params_tasks->set('project.id', $model->getState('project.id'));
				$params_tasks->set('order.list', 't.`ordering`');
				$params_tasks->set('order.dir', 'ASC');
				$params_tasks->set('state',' >= 1');
				$params_tasks->set('task.type', 2); // get tasks
				// limit for lister
				$params_tasks->set('limit.limit', 5);
				$params_tasks->set('limit.start', 0);
				$this->assignRef('tasks',ProjectsHelper::getTasks($params_tasks));
				$params_tasks->set('task.type', 3); // get tickets
				$this->assignRef('tickets',ProjectsHelper::getTasks($params_tasks));
				
				// get documents
				$params_docs = new JRegistry();
				$params_docs->set('project.id', $model->getState('project.id'));
				$params_docs->set('order.list', 'c.`modified`, c.`created`');
				$params_docs->set('order.dir', 'ASC');
				$params_docs->set('state',' >= 1');
				// limit for lister
				$params_docs->set('limit.limit', 5);
				$params_docs->set('limit.start', 0);
				$this->assignRef('docs',ProjectsHelper::getDocuments($params_docs));
				break;
		}
		
		// Display the view
		$this->setLayout($layout);
		$this->addToolbar();
		parent::display($tpl);
	}
	
	
	protected function addToolbar() 
	{
		$this->loadHelper('toolbar');
		
		switch($this->getLayout()){
			case 'edit':
			case 'form':
				$title = JText::_('COM_PROJECTS_PROJECT_FORM_TITLE');
				$icon = 'config';
				
				ToolBar::save('project.save');
				ToolBar::cancel('project.cancel');
				break;
				
			default:
				$title = $this->item->title;
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
		        if ($this->params->get('show_back_button')) {
		            ToolBar::spacer();
		            ToolBar::back();
		        }
		}
		ToolBar::title($title, $icon);
		
		echo ToolBar::render();
	}
}