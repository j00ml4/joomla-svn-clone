<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright   Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

// Imports
jimport('joomla.application.component.view');

/**
 * Display project view
 * @author eden & elf
 */
class ProjectsViewDocuments extends JView
{
	protected $params;
	protected $canDo;
	protected $items;
	protected $user;
	protected $pagination;
	protected $article;
	
	/**
	 * Display project
	 */
	public function display($tpl = null) 
	{	
		$app					= JFactory::getApplication();
		$model				= $this->getModel();
		$this->params	= $app->getParams();
		$this->items	= $model->getItems();
		$this->state  = $model->get('state');
		$this->user 	=	JFactory::getUser();
		$this->pagination	= &$model->getPagination();
		
		$this->canDo = ProjectsHelper::getActions();
		// Compute the article slugs (runs content plugins).
		for ($i = 0, $n = count($this->items); $i < $n; $i++) {
			$item = &$this->items[$i];
			$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
		}
		
		// Display the view
		$this->setLayout('default');
                $this->addToolbar();
		parent::display($tpl);
	}
	
	
	protected function addToolbar() 
	{
		$this->loadHelper('toolbar');
		
		$title = JText::sprintf('COM_PROJECTS_DOCUMENTS_LIST_TITLE', $this->project->title);	
		$icon = 'article';
		
		if($this->canDo->get('document.create')){
			ToolBar::addNew('document.add');
		}
		if($this->canDo->get('document.edit')){
			ToolBar::editList('document.edit');
		}
		if($this->canDo->get('document.delete')){
			ToolBar::deleteList('documents.edit');
		}
		if($this->params->get('show_back_button')){
			ToolBar::spacer();
			ToolBar::back();
		}
		
		ToolBar::title($title, $icon);
		echo ToolBar::render();
	}
}