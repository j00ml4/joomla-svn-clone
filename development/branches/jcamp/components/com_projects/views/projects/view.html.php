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
	protected $canDo;
	
	/**
	 * Display View
	 * @param $tpl
	 */
	function display($tpl = null)
	{
		$app		= &JFactory::getApplication();
		$model 		= $this->getModel('Projects');
			
		// Get some data from the models
		$this->state		= $this->get('State');
		$this->items		= &$model->getItems();
		$this->pagination	= &$model->getPagination();
		$this->params		= &$app->getParams();
		$this->canDo		= &ProjectsHelper::getActions();
		
		
		$layout = $this->getLayout();
		switch($layout){
			case 'gallery':
				$c = count($this->items);
				for($i = 0; $i < $c;$i++) {
						$this->items[$i]->description = JHtml::_('content.prepare', $this->items[$i]->description);
				}
				
				// Get category
				$this->category		= $this->get('Category');
				if(empty($this->category)){
					return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
				}
				$app->setUserState('project.category.id', $this->category->id);
				break;

			default:
				$app->setUserState('project.category.id', 0);
				break;
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			return JError::raiseError(500, implode("\n", $errors));
		}
		
		parent::display($tpl);
	}
}
