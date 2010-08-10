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
		$bc 		= $app->getPathway();
	  
		// Get some data from the models
		$this->items		= $model->getItems();
		$this->pagination	= $model->getPagination();
		$this->params		= $app->getParams();
		$this->canDo		= ProjectsHelper::getActions();
			
		$layout = $this->getLayout();
		switch($layout){
			case 'default': 
				$c = count($this->items);
				for($i = 0; $i < $c;$i++) {
						$this->items[$i]->description = JHtml::_('content.prepare', $this->items[$i]->description);
				}
				
				// Get category
				$this->category	= &$model->getCategory();
				if(!empty($this->category)){
					$bc->addItem($this->category->title);
				}				
				break;
				
			default:
				$layout = 'default';
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			return JError::raiseError(500, implode("\n", $errors));
		}
		
	  	$this->setLayout($layout);
	  	parent::display($tpl);
	}
}
