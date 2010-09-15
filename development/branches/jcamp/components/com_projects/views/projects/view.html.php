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
	protected $state;
	protected $items;
	protected $portfolio;
	protected $children;
	protected $parent;
	protected $maxLevel;
	protected $params;
	protected $pagination;
	protected $canDo;
	
	/**
	 * Display View
	 * @param $tpl
	 */
	function display($tpl = null)
	{
		$app		= JFactory::getApplication();
		$model		= $this->getModel();
	  
		// Get some data from the models
		$this->state		= $this->get('State');
		$this->items		= $model->getItems();
		$this->portfolio	= $model->getPortfolio();
		$this->pagination	= $model->getPagination();
		$this->params		= $app->getParams();
		$this->canDo		= ProjectsHelperACL::getActions(
			$app->getUserState('portfolio.id'),
			0,
			$this->portfolio);
			
		$this->params->set('is.root', ($this->portfolio->level == 0));	
		$layout = $this->getLayout();
		switch($layout){
			// Projects default List
			default:
				$layout = 'default';
		
				if($this->params->get('use_content_plugins_portfolios',0)){
					$c = count($this->items);
					for($i = 0; $i < $c;$i++) {
		               	$this->items[$i]->text = & $this->items[$i]->description;
						ProjectsHelper::triggerContentEvents($this->items[$i], $this->params, $this->state->get('list.offset'));
					}
					
					$this->portfolio->text = &$this->portfolio->description;
		            ProjectsHelper::triggerContentEvents($this->portfolio, $this->params, $this->state->get('list.offset'));
				}
				
	            break;
			}						
				
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			return JError::raiseError(500, implode("\n", $errors));
		}
		
	  	$this->setLayout($layout);
	  	$this->addToolbar();
	  	parent::display($tpl);
	}
	
	protected function addToolbar() 
	{
		$this->loadHelper('toolbar');
		
		if($this->canDo->get('core.create')){
			ToolBar::addNew('project.add');
		}
		
		if(!$this->params->get('is.root')){
			$title = $this->portfolio->get('title');
		}else {
			$title = JText::_('COM_PROJECTS_PROJECTS_VIEW_DEFAULT_TITLE');
		}
		ToolBar::title($title, 'categories');
		if ($this->params->get('show_back_button')) {
            ToolBar::spacer();
            ToolBar::back(ProjectsHelper::getLink('portfolios', $this->portfolio->get('id')));
		}
		
		$app = JFactory::getApplication();
		$bc = $app->getPathway();
		$bc->addItem($title, ProjectsHelper::getLink('portfolios', $this->portfolio->id));
		
		echo ToolBar::render();
	}
}
