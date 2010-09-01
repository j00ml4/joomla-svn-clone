<?php
/**
 * @version		$Id: featured.php 14276 2010-01-18 14:20:28Z louis $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// why the heck isn't this working?!
//jimport('joomla.database.table.content');
require_once 'libraries'.DS.'joomla'.DS.'database'.DS.'table'.DS.'content.php';

/**
 * @package		Joomla.Site
 * @subpackage	Projects
 */
class ProjectsTableContent extends JTableContent
{

}