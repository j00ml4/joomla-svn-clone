<?php
/**
 * @version		$Id: category.php 14276 2010-01-18 14:20:28Z louis $
 * @package		Joomla
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Component Helper
jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * Projects Component Category Tree
 *
 * @static
 * @package		Joomla
 * @subpackage	com_projects
 * @since 1.6
 */
class ProjectsCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__projects';
		$options['extension'] = 'com_projects';
		parent::__construct($options);
	}
}