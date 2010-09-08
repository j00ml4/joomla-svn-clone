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
class ProjectsViewDocument extends JView
{
	protected $state;
	protected $item;
	protected $project;
	protected $form;
	protected $params;
	protected $canDo;
	protected $user;
		
	/**
	 * Display document form
	 */
	public function display($tpl = null) 
	{
		$app		= JFactory::getApplication();
		$model		= $this->getModel();
		
		//Get data
		$this->item 	= $model->getItem();
		$this->project 	= $model->getProject();
		$this->state	= $this->get('State');
		$this->params	= $this->state->params;
		$this->canDo	= ProjectsHelper::getActions(
			$app->getUserState('portfolio.id'), 
			$app->getUserState('project.id'),
			$this->item);
		
		
		if (empty($this->project)){
			return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
		}
				
		$layout = $this->getLayout();
		switch($layout){
			// Form
			case 'edit':
			case 'form':
				$layout = 'edit';
				$this->form	= $model->getForm();
				
				// State
				if (empty($this->item)) {
					$access = 'document.create';
				}else{
					$access = 'document.edit';					
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
				
				//
				// Process the content plugins.
				//
				$offset 	= $this->state->get('list.offset');
				$dispatcher	= JDispatcher::getInstance();
				JPluginHelper::importPlugin('content');
				$results = $dispatcher->trigger('onContentPrepare', array ('com_content.article', &$this->item, &$this->params, $offset));
		
				$this->item->event = new stdClass();
				$results = $dispatcher->trigger('onContentAfterTitle', array('com_content.article', &$this->item, &$this->params, $offset));
				$this->item->event->afterDisplayTitle = trim(implode("\n", $results));
		
				$results = $dispatcher->trigger('onContentBeforeDisplay', array('com_content.article', &$this->item, &$this->params, $offset));
				$this->item->event->beforeDisplayContent = trim(implode("\n", $results));
		
				$results = $dispatcher->trigger('onContentAfterDisplay', array('com_content.article', &$this->item, &$this->params, $offset));
				$this->item->event->afterDisplayContent = trim(implode("\n", $results));	
	  			break;
		}	
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
				$type = empty($this->item->id)? 'NEW' : 'EDIT';			
				$title = JText::sprintf('COM_PROJECTS_DOCUMENT_'.$type.'_TITLE', $this->project->title);
				$icon = 'config';
				
				ToolBar::save('document.save');
				ToolBar::cancel('document.cancel');
				break;
				
			default:
				$title = $this->item->title;
				$icon = 'archive';
				if($this->canDo->get('document.edit')){
					ToolBar::editList('document.edit');
				}
			
				if($this->canDo->get('document.delete')){
					ToolBar::deleteList(JText::_('COM_PROJECTS_CONFIRM_DOCUMENT_DELETE'), 'documents.delete');
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