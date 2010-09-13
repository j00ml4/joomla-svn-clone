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
class ProjectsViewDocuments extends JView {

    protected $params;
    protected $canDo;
    protected $items;
    protected $user;
    protected $pagination;
    protected $project;

    /**
     * Display project
     */
    public function display($tpl = null) {
        $app 	= JFactory::getApplication();
        $model 	= $this->getModel('Documents');
        
        $this->params 		= $app->getParams();
        $this->items 		= $model->getItems();
        $this->project 		= $model->getProject();
        $this->state 		= $this->get('State');  
        $this->pagination 	= $model->getPagination();
        $this->canDo		= ProjectsHelperACL::getActions(
			$app->getUserState('portfolio.id'), 
			$app->getUserState('project.id'),
			$this->project);
	
    	if (empty($this->project)){
			return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
		}	
			
        // Display the view
        $this->setLayout('default');
        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar() {
        $this->loadHelper('toolbar');

        $title = JText::sprintf('COM_PROJECTS_DOCUMENTS_LIST_TITLE', $this->project->title);
        $icon = 'article';

        if ($this->canDo->get('document.create')) {
            ToolBar::addNew('document.add');
        }
        
        if(count($this->items)){
	        if ($this->canDo->get('document.edit')) {
	            ToolBar::editList('document.edit');
	        }
	        if ($this->canDo->get('document.delete')) {
	            ToolBar::deleteList(JText::_('COM_PROJECTS_CONFIRM_DOCUMENTS_DELETE'), 'documents.delete');
	        }
        }
        
        if ($this->params->get('show_back_button')) {
            ToolBar::spacer();
            ToolBar::back(ProjectsHelper::getLink('project', $this->project->id));
        }

        ToolBar::title($title, $icon);
        echo ToolBar::render();
    }

}