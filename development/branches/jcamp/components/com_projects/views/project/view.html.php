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
}