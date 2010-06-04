<?php
/**
 * @version     $Id$
 * @package     Joomla
 * @subpackage	Projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

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
		$this->params = $this->state->get('params');
		
		// Display the view
		parent::display($tpl);
	}
}