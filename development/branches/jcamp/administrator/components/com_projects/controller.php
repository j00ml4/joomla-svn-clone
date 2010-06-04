<?php
/**
 * @version     $Id$
 * @package     Joomla.Administrator
 * @subpackage	Projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

defined("_JEXEC") or die("Restricted access");
jimport('joomla.application.component.controller');

class ProjectsController extends JController {
	
	protected $default_view = 'config';
	 
	/**
	 * Display
	 */
	public function display()
	{
		parent::display();
	}

}