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
	
	/**
	 * Display project
	 */
	public function display($tpl = null) 
	{
		
		$app		= &JFactory::getApplication();
		$model 		= $this->getModel('project');
		$state		= $model->getState();

		//Get Model data
		$this->form 	= $model->getForm();
		$this->table 	= $model->getTable();
		$this->params	= &$app->getParams();
	
		// Display the view
		parent::display($tpl);
	}
}