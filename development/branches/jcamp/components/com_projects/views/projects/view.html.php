<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
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
 * @subpackage	com_projects
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
		$model 		= $this->getModel('Projects');
			
		// Get some data from the models
		$this->state		= &$model->getState();
		$this->items		= &$model->getItems();
		$this->pagination	= &$model->getPagination();
		$this->params		= &$app->getParams();
		
		// trigger content plugins (in case of 'gallery layout')
		if($this->getLayout() == 'gallery') {
			$c = count($this->items);
			for($i = 0; $i < $c;$i++) {
					$this->items[$i]->description = JHtml::_('content.prepare', $this->items[$i]->description);
			}
		}
		
		// Check for errors.
//		if (count($errors = $this->get('Errors'))) {
			//JError::raiseError(500, implode("\n", $errors));
			//return false;
//		}


		// Check whether category access level allows access.
		$this->user	= &JFactory::getUser();
//		$groups	= $user->authorisedLevels();
		//if (!in_array($category->access, $groups)) {
			//return JError::raiseError(403, JText::_("JERROR_ALERTNOAUTHOR"));
		//}

		parent::display($tpl);
	}
}
