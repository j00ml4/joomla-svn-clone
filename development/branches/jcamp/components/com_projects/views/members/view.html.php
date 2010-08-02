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
	protected $project;
	protected $pagination;
	protected $prefix_text;
	
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
		$this->pagination	= &$model->getPagination();

	  // set a correct prefix
	  switch($model->getState('type'))
	  {
	  	case 'assign' :
	  			$this->prefix_text = 'ASSIGN';
	  			break;
	  	case 'delete' :
	  			$this->prefix_text = 'DELETE';
	  			break;
	  	case 'list' :
	  			$this->prefix_text = 'LIST';
	  			break;
	  }
		
		// set up type and layout
		$this->setLayout('default');
		$access = 'project.edit';
		if($model->getState('type') != 'list' &&
			 $model->getState('type') != 'assign' &&
			 $model->getState('type') != 'delete')
		return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));

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