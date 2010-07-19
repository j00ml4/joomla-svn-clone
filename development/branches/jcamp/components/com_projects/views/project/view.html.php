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
	protected $category;
	
	/**
	 * Display project
	 */
	public function display($tpl = null) 
	{	
		$app		= &JFactory::getApplication();
		$model		= &$this->getModel();
		$bc 		= &$app->getPathway();
		
		//Get Model data
		$this->item 	= &$model->getItem();
		$this->params	= &$app->getParams();
		$this->canDo	= &ProjectsHelper::getActions();
		
		// Layout
		$layout = $this->getLayout();
		switch($layout){
			case 'edit':
			case 'form':
				$layout = 'form';
				$this->form	= &$model->getForm();
				if (empty($this->item)) {
					$this->catid = $app->getUserState('portfolio.id', 0);
					$access = 'project.create';
				}else{
					$access = 'project.edit';
				}
				
				// Access
				if (!$this->canDo->get($access)){
					return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));				
				}
				
				// Pathway
				$bc->addItem(JText::_('COM_PROJECTS_PROJECT_FORM_TITLE'));
				break;
			
			default:
				$layout = 'default';
				// Access
				if (!$this->canDo->get('project.view')){
					return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));				
				}
				if (empty($this->item->id)){
					return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
				}
				// Get Category
				//$this->category = &$model->getCategory($this->item->catid);

				// Pathway
	  			$bc->addItem($this->item->title);
	  			break;
		}
		
		// Display the view
		$this->setLayout($layout);
		parent::display($tpl);
	}
}