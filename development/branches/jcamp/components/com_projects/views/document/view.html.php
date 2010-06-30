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
	protected $form;
	protected $params;
	protected $canDo;
	protected $user;
		
	/**
	 * Display document form
	 */
	public function display($tpl = null) 
	{
		$app		= &JFactory::getApplication();
		$model		= &$this->getModel();
		
		//Get data
		$this->item 	= &$model->getItem();
		$this->canDo	= &ProjectsHelper::getActions();
		$this->state	= $this->get('State');
		$this->params	= &$this->state->params;
		$this->form	= &$model->getForm();
		$this->user = &JFactory::getUser();
		
		$this->form	= &$model->getForm();
		if (empty($this->item)) {
			$access = 'project.create';
		}else{
			$access = 'project.edit';
		}
		
		// Access
		if (!$this->canDo->get($access)){
			return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));				
		}
		
		// Display the view
		$this->setLayout('form');
		parent::display($tpl);
	}
}