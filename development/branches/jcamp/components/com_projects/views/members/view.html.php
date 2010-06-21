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
	protected $item;
	protected $items;
	protected $params;
	protected $canDo;
	protected $title;
	protected $project;
	
	/**
	 * Display managing users assigned to project
	 */
	public function display($tpl = null) 
	{	
		$app		= &JFactory::getApplication();
		$model		= &$this->getModel();
		$this->canDo	= &ProjectsHelper::getActions();
		
		//Get Model data
		$this->items 	= &$model->getItems();
		$this->params	= &$app->getParams();
		$this->project 	= &$model->getProject();
		
		// set up type and layout
		$this->setLayout('default');
		$access = 'project.edit';
		switch($model->getState('type')){
			case 'list':
				$this->title = JText::_('COM_PROJECTS_TEAM_USER_LIST');
				$access = 'project.view';
				break;
			case 'assign':
				$this->title = JText::_('COM_PROJECTS_TEAM_USER_ASSIGN');
				break;
			case 'delete':
				$this->title = JText::_('COM_PROJECTS_TEAM_USER_DELETE');
				break;
			default:
					return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
		}
		// Access
		if (!$this->canDo->get($access)){
				return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));				
		}
		
		// Pathway
		$bc = &$app->getPathway();
		$bc->addItem($this->project->title,'index.php?option=com_projects&view=project&id='.$this->project->id);
		$bc->addItem($this->title);
		// Display the view
		parent::display($tpl);
	}
}