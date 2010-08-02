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
	protected $item;
	protected $project;
	protected $parent;
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
		$app		= &JFactory::getApplication();
		$model 		= $this->getModel();
			
		// Get some data from the models
		$this->items		= &$model->getItems();
		$this->pagination	= &$model->getPagination();
		$this->params		= &$app->getParams();
		$this->canDo		= &ProjectsHelper::getActions();
			
		$layout = $this->getLayout();
		switch($layout){
			default:
				$layout = 'default';
				// Get project
				$this->project	= &$model->getProject();
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
		require_once JPATH_COMPONENT.'/helpers/tasks.php';
		$this->prefix = TasksHelper::getPrefix($model->getState('task.type'));

  	// TMLP
  	$this->setLayout($layout);
		parent::display($tpl);
	}
	
	
}
