<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	Projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Projects component
 *
 * @package		Joomla.Site
 * @subpackage	Projects
 * @since		1.6
 */
class ProjectsViewProjects extends JView
{
	protected $items;
	protected $category;
	protected $children;
	protected $parent;
	protected $maxLevel;
	protected $state;
	protected $params;
	protected $pagination;
	
	/**
	 * Display View
	 * @param $tpl
	 */
	function display($tpl = null)
	{
		$app		= &JFactory::getApplication();
		
	
		// Layout
		//$this->setLayout(JRequest::getWord('layout', 'gallery'));
		
		// Get some data from the models
		$this->state		= &$this->get('State');
		//$this->items		= &$this->get('Items');
		//$this->pagination	= &$this->get('Pagination');
		$this->params		= &$app->getParams();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			//JError::raiseError(500, implode("\n", $errors));
			//return false;
		}


		// Check whether category access level allows access.
		$user	= &JFactory::getUser();
		$groups	= $user->authorisedLevels();
		//if (!in_array($category->access, $groups)) {
			//return JError::raiseError(403, JText::_("JERROR_ALERTNOAUTHOR"));
		//}

		parent::display($tpl);
	}
}
