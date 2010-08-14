<?php
/**
 * @version		$Id: media.php 15757 2010-04-01 11:06:27Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	Media
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * @package		Joomla.Site
 * @subpackage	com_projects
 */
abstract class ProjectsHelperLinks
{
	protected static $links = array(
		'project' => 'index.php?option=com_projects&view=project',
		'members.assign' => 'index.php?option=com_projects&view=members&type=assign',
		'members.list' => 'index.php?option=com_projects&view=members&type=list',
	);

	public function get($link, $append='')
	{
		return JRoute::_($link.$append);	
	}
}
?>