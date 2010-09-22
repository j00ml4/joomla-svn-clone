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
class ProjectsViewTask extends JView 
{
	protected $state;
    protected $item;
    protected $project;
    protected $form;
    protected $params;
    protected $canDo;
    protected $prefix;
	protected $type; 
	protected $pagination;
	protected $items;
	

    /**
     * Display project
     */
    public function display($tpl = null) 
    {
        $app = JFactory::getApplication();
        $model = $this->getModel();

        //Get Model data
        $this->state = $this->get('State');
        $this->item = $model->getItem();
        $this->project = $model->getProject();
        $this->params = $app->getParams();
        $this->type = $model->getType();
		$this->items = $model->getItems();
		$this->pagination = $model->getPagination();
		$this->canDo	= ProjectsHelperACL::getActions(
			$app->getUserState('portfolio.id'), 
			$app->getUserState('project.id'),
			$this->item);
        // Layout
        $layout = $this->getLayout();
        switch ($layout) {
            case 'new':
            case 'edit':
            case 'form':
                $layout = 'edit';
                $this->form = &$model->getForm();
                if (empty($this->item)) {
                    $this->params->set('catid', $app->getUserState('task.category.id', 0));
                    $access = 'task.create';
                } else {
                    $access = 'task.edit';
                }

                // Access
                if (!$this->canDo->get($access)) {
                    return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
                }
                break;
                
            default:
                $layout = 'default';
                // Access
                if (!$this->canDo->get('is.authorised')) {
                    return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
                }
                if (empty($this->item->id)) {
                    return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
                }
                
        
				if($this->params->get('use_content_plugins_tasks',0)){			
					$this->item->text = &$this->item->description;
		            ProjectsHelper::triggerContentEvents($this->item, $this->params, $this->state->get('list.offset'));
				}
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
				$type = empty($this->item->id) ? 'NEW' : 'EDIT';
				$title = JText::sprintf('COM_PROJECTS_TASK_'.$type.'_'.$this->type.'_TITLE', $this->project->title);
				$icon = 'config';
				
				ToolBar::save('task.save');
				ToolBar::cancel('task.cancel');
				break;
				
			default:
				$title = JText::sprintf('COM_PROJECTS_TASK_VIEW_'.$this->type.'_TITLE', $this->item->title);
				$icon = 'archive';			
				if($this->canDo->get($this->type.'.edit')){
					if($this->item->state == 1){
						if(empty($this->items) || $this->item->progress == 100){
							ToolBar::custom('tasks.archive', 'checkin', 'checkin', JText::_('COM_PROJECTS_STATE_FINISHED'));
						}
					}else{
						ToolBar::custom('tasks.publish', 'notice', 'notice', JText::_('COM_PROJECTS_STATE_PENDING'));
                    }
                    ToolBar::spacer();					
					ToolBar::editList('task.edit');
				}

				if($this->canDo->get('core.delete')){
					ToolBar::deleteList(JText::_('COM_PROJECTS_CONFIRM_'.$this->type.'_DELETE'), 'task.delete');
				}
				
		        if ($this->params->get('show_back_button')) {
		            ToolBar::spacer();
		            ToolBar::back(ProjectsHelper::getLink('tasks', $this->project->id));
		        }
		}
		ToolBar::title($title, $icon);
		
		echo ToolBar::render();
	}
}
