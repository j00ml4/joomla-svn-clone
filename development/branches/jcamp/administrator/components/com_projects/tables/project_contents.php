<?php
/**
 * @version     $Id$
 * @package     Joomla.Administrator
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	com_projects
 */
class jcTable_Project_contents extends JTable
{
	var $project_id	= null;
	var $content_id	= null;
	
	/**
	 * @param	JDatabase	A database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__project_contents', 'project_id', $db);
	}
}