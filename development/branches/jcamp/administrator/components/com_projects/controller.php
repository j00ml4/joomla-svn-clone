<?php
/**
 * @version     $Id$
 * @package     Joomla.Administrator
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */
defined("_JEXEC") or die("Restricted access");

/**
 * Projects Admin Controller
 * @author eden
 * @since 1.6
 *
 */
class ProjectsController extends JController {
	protected $default_view = 'projects';
	 
	/**
	 * Display
	 */
	public function display($cacheable=false)
	{
		// Display view
		parent::display($cacheable);
		
		// Set Vars
		$vName = JRequest::getWord('view', $this->default_view);
		
		// Submenu
		ProjectsHelper::addSubmenu($vName);
	}

}