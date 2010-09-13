<?php

/**
 * @version		$Id: article.php 17873 2010-06-25 18:24:21Z 3dentech $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * @package		Joomla.Site
 * @subpackage	com_project
 */
class ProjectsControllerTasks extends JControllerAdmin
{
	protected $view_item = 'task';
	protected $view_list = 'tasks';
	protected $text_prefix = 'COM_PROJECTS_TASKS';
    /**
     * Constructor
     */
    public function __construct($config = array()) {
        parent::__construct($config);

        // States
        $this->registerTask('publish',		'publish');	// value = 1 	APPROVED TASK
        $this->registerTask('unpublish',	'publish'); // value = 0	DENIED
        $this->registerTask('archive', 		'publish'); // value = 2 	FINISHED TASK
        $this->registerTask('trash', 		'publish'); // value = -2
        $this->registerTask('report', 		'publish'); // value = -3 	REPORTED TICKET

        $this->registerTask('orderup', 		'reorder');
        $this->registerTask('orderdown', 	'reorder');

    }

    /**
     * Method to get a model object, loading it if required.
     *
     * @param	string	The model name. Optional.
     * @param	string	The class prefix. Optional.
     * @param	array	Configuration array for model. Optional.
     *
     * @return	object	The model.
     * @since	1.6
     */
    public function getModel($name = 'Task', $prefix = 'ProjectsModel', $config = array()) {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * Method to go back to project overview
     *
     * @since	1.6
     */
    public function back() {
        $app = JFactory::getApplication();
        $this->setRedirect(ProjectsHelper::getLink('project',$app->getUserState('project.id')));
    }
	
    public function publish(){
    	parent::publish();
    	
    	$app = JFactory::getApplication();
        $this->setRedirect(ProjectsHelper::getLink('tasks', $app->getUserState('project.id')));
    }
    
    
    /**
	 * Check in of one or more records.
	 *
	 * @since	1.6
	 */
	public function checkout()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$user	= JFactory::getUser();
		$id	= JRequest::getInt('id', null);
		
		$model = $this->getModel();
		if (!$model->checkout($id)) {
			// Checkin failed.
			$message = $model->getError();
			$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false), $message, 'error');
			return false;
		}
		
		// Checkin succeeded.
		$message =  JText::_($this->text_prefix.'_N_ITEMS_CHECKED_OUT');
		$this->setRedirect(ProjectsHelper::getLink($key));
		return true;
	}
    
}