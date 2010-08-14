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
		$bc 		= $app->getPathway();
		
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
				
				// Pathway
				$bc->addItem(JText::_('COM_PROJECTS_PROJECT_FORM_TITLE'));
				
				// State
				if (empty($this->item)) {
					$this->catid = $app->getUserState('portfolio.id', 0);
					$access = 'core.create';
				}else{
					$access = 'core.edit';
					$bc->addItem($this->item->get('title'));					
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

				// Pathway
	  			$bc->addItem($this->item->title);
	  			break;
		}
		
		// Links
		$this->links = $this->getLinks();
		
		// Display the view
		$this->setLayout($layout);
		parent::display($tpl);
	}
	
	
	protected function getLinks()
	{
		return array(
			'project' => JRoute::_('index.php?option=com_projects&view=project&id='.$this->item->id),
			'members' => JRoute::_('index.php?option=com_projects&view=members&type=list&id='.$this->item->id)
		);
	}
}