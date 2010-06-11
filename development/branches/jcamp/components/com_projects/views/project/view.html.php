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
	protected $catid;
	protected $canDo;
	
	/**
	 * Display project
	 */
	public function display($tpl = null) 
	{	
		$app		= &JFactory::getApplication();
		$model 		= $this->getModel('project');
		$state		= $model->getState();

		//Get Model data
		$this->item 	= $model->getItem();
		$this->params	= &$app->getParams();
		$this->catid	= JRequest::getInt('catid');
		$this->canDo	= $this->getCanDoList();
		
		// Layout
		$layout = JRequest::getCMD('layout');
		switch($layout){
			case 'edit':
			case 'form':
				$this->form	= $model->getForm();
				$layout 	= 'form';
				break;
			
			default:
				$layout		= 'default';
				if (empty($this->item->id)){
					JError::raiseWarning(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));

				}	
		}
		
		// Display the view
		$this->setLayout($layout);
		parent::display($tpl);
	}
	
	/**
	 * Get the list of avaliable actions
	 * 
	 * @return array CanDoList
	 */
	protected function getCanDoList()
	{
		$canDo = array();
		
		// Project Form
		$canDo['edit_state'] = ProjectsHelper::can('project.edit.state');
		$canDo['edit_portfolio'] = ProjectsHelper::can('project.edit.portfolio');
		$canDo['edit_lang'] = ProjectsHelper::can('project.edit.portfolio');
		$canDo['edit_order'] = ProjectsHelper::can('project.edit.order');
		
		// Project Overview
		$canDo['view_tasks'] = ProjectsHelper::can('project.edit.state');
		$canDo['view_documents'] = ProjectsHelper::can('project.edit.portfolio');
		$canDo['view_tickets'] = ProjectsHelper::can('project.edit.portfolio');
		$canDo['view_activities'] = ProjectsHelper::can('project.edit.order');
		
		// Can Do List
		return $canDo;
	}
}