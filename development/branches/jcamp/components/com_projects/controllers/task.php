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

jimport('joomla.application.component.controllerform');

/**
 * @package		Joomla.Site
 * @subpackage	Projects
 * @since		1.6
 */
class ProjectsControllerTask extends JControllerForm
{
	protected $view_item = 'task';
	protected $view_list = 'tasks';
	protected $text_prefix = 'COM_PROJECTS_TASK';
	
	
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param	string	The model name. Optional.
	 * @param	string	The class prefix. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	object	The model.
	 */
	public function getModel($name = 'Task', $prefix = 'ProjectsModel', $config = array())
	{
		return parent::getModel($name, $prefix, $config);
	}
	
    /**
     * Method to check if you can add a new record.
     *
     * Extended classes can override this if necessary.
     *
     * @param	array	An array of input data.
     *
     * @return	boolean
     */
    protected function allowAdd($data = array()) {
    	$app = JFactory::getApplication();
        $record = new JObject($data);
        
        return ProjectsHelperACL::canDo($this->getModel()->getType().'.create',
                $app->getUserState('portfolio.id'), 
				$app->getUserState('project.id'),
                $record);
    }

    /**
     * Method to check if you can add a new record.
     *
     * Extended classes can override this if necessary.
     *
     * @param	array	An array of input data.
     * @param	string	The name of the key for the primary key.
     *
     * @return	boolean
     */
    protected function allowEdit($data = array()) {
        $app = JFactory::getApplication();
    	$record = new JObject($data);

        return ProjectsHelperACL::canDo($this->getModel()->getType().'.edit',
                $app->getUserState('portfolio.id'), 
				$app->getUserState('project.id'),
                $record);
    }
  

    /**
     * Method to check if you can save a new or existing record.
     *
     * Extended classes can override this if necessary.
     *
     * @param	array	An array of input data.
     * @param	string	The name of the key for the primary key.
     *
     * @return	boolean
     */
    protected function allowSave($data) {
    	$app = JFactory::getApplication();
        $record = new JObject($data);
        $action = $record->get('id', 0) ? '.edit' : '.create';

        return ProjectsHelperACL::canDo($this->getModel()->getType() . $action,
                $app->getUserState('portfolio.id'), 
				$app->getUserState('project.id'),
                $record);
    }
	
	
	/**
	 * Method to cancel an edit
	 *
	 * Checks the item in, sets item ID in the session to null, and then redirects to the list page.
	 *
	 * @access	public
	 * @return	void
	 */
	public function cancel()
	{
		parent::cancel();
		
		$model	= $this->getModel();
		$id = $model->getState('task.id');
		
		if($id){
			$this->setRedirect(ProjectsHelper::getLink('task', $id));
		}else{
			$this->setRedirect(ProjectsHelper::getLink('tasks', $model->getState('project.id')));
		}
	}
	
	public function save(){
		parent::save();
		
		$model	= $this->getModel();
		$id = $model->getState('task.id');
		
		if($id){
			$this->setRedirect(ProjectsHelper::getLink('task', $id));
		}else{
			$this->setRedirect(ProjectsHelper::getLink('tasks', $model->getState('project.id')));
		}	
	}
	
}