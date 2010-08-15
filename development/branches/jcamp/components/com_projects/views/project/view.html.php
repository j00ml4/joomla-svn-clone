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
				if (empty($this->item)) {
					$this->catid = $app->getUserState('portfolio.id', 0);
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
				
				// Get Category
				$this->portfolio = $model->getPortfolio();
	  			break;
		}
		
		// Display the view
		$this->setLayout($layout);
		$this->addToolbar();
		parent::display($tpl);
	}
	
	public function getLink($key, $append=''){
		return ProjectsHelper::getLink($key, $append);
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
				if($this->params->get('show_back_button')){
					ToolBar::back();
				}
		}
		ToolBar::title($title, $icon);
		
		echo ToolBar::render();
	}
}