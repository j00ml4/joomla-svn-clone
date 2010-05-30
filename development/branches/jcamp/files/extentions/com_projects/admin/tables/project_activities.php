<?php
/**
 * @version     $Id$
 * @package     Joomla.Administrator
 * @subpackage	jCamp
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	jCamp
 */
class jcTable_Project_activities extends JTable
{
	var $id	= null;
	var $type	= null;
	var $description	= null;
	var $link	= null;
	var $created	= null;
	var $created_by	= null;
	var $created_by_alias	= null;
	var $language	= null;
	var $featured	= null;
	var $xreference	= null;
	var $params	= null;
	
	/**
	 * @param	JDatabase	A database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__project_activities', 'id', $db);
	}
}