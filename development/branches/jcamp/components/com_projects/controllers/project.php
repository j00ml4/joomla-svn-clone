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
class ProjectsControllerProject extends JControllerForm {

    protected $view_item = 'project';
    protected $view_list = 'projects';

    /**
     * Constructor.
     *
     * @param	array An optional associative array of configuration settings.
     * @see		JController
     * @since	1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);

        $this->registerTask('unpublish', 'publish'); // value = 0
        $this->registerTask('archive', 'publish'); // value = 2	// finished
        $this->registerTask('trash', 'publish'); // value = -2
        $this->registerTask('report', 'publish'); // value = -3 	// pending
        $this->registerTask('back', 'back');

        $this->registerTask('assign', 'assignMembers');
        $this->registerTask('unassign', 'unassignMembers');

        //$this->registerTask('orderup',	'reorder');
        //$this->registerTask('orderdown',	'reorder');
    }

    /**
     * Method to get a model object, loading it if required.
     *
     * @param	string	The model name. Optional.
     * @param	string	The class prefix. Optional.
     * @param	array	Configuration array for model. Optional.
     *
     * @return	object	The model.
     */
    public function getModel($name = 'Project', $prefix = 'ProjectsModel', $config = null) {
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
        $record = new JObject($data);
        return ProjectsHelper::canDo('core.create',
                $record->get('catid'),
                $record->get('id'),
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
        $record = new JObject($data);
        return ProjectsHelper::canDo('core.create',
                $record->get('catid'),
                $record->get('id'),
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
        $record = new JObject($data);
        $action = $record->get('id', 0) ? 'core.edit' : 'core.create';

        return ProjectsHelper::canDo($action,
                $record->get('catid'),
                $record->get('id'),
                $record);
    }

    /**
     * Method to go back to list of projects of a portfolio
     *
     */
    public function back() {
    	$app = JFactory::getApplication();
        $this->setRedirect(JRoute::_('index.php?option=com_projects&view=projects&layout=gallery&id='.$app->getUserState('portfolio.id'), false));
    }

    /**
     * Save
     *
     * @see libraries/joomla/application/component/JControllerForm#save()
     */
    public function save() {
        // Check for request forgeries
        $app = JFactory::getApplication();
        $lang = JFactory::getLanguage();
        $model = $this->getModel();
        $table = $model->getTable();
        $data = JRequest::getVar('jform', array(), 'post', 'array');
        $context = $this->option.'.edit.'.$this->context;
        $checkin = property_exists($table, 'checked_out');
        $id = $model->getState('project.id', 0);
        
        // Populate the row id from the session.
        $data['id'] = $id;
        $isNew = !$id;

        // Access check.
        if (!$this->allowSave($data)) {
            $this->setRedirect(ProjectsHelper::getLink('projects', $data['catid']));
            return JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
        }

        // Validate the posted data.
        // Sometimes the form needs some posted data, such as for plugins and modules.
        $form = $model->getForm($data, false);

        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }
        

        // Test if the data is valid.
        $data = $model->validate($form, $data);

        // Check for validation errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if (JError::isError($errors[$i])) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'notice');
                } else {
                    $app->enqueueMessage($errors[$i], 'notice');
                }
            }

            // Save the data in the session.
            $app->setUserState($context.'.data', $data);

            // Redirect back to the edit screen.
            $this->setRedirect(ProjectsHelper::getLink('projects', $data['catid']));
            return false;
        }

        // Attempt to save the data.
        if (!$model->save($data)) {
            // Save the data in the session.
            $app->setUserState($context.'.data', $data);

            // Redirect back to the edit screen.
            $this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()), 'notice');
            $this->setRedirect(ProjectsHelper::getLink('projects', $data['catid']));
            return false;
        }
        print_r($data);
        
        // Save succeeded, check-in the record.
        if ($checkin && !$model->checkin($data['id'])) {
            // Save the data in the session.
            $app->setUserState($context.'.data', $data);

            // Check-in failed, go back to the record and display a notice.
            $message = JText::sprintf('JError_Checkin_saved', $model->getError());
            $this->setRedirect(ProjectsHelper::getLink('project', $data['id']), $message, 'error');
            return false;
        }

        // isNew
        if ($isNew) {
            $id = $data['id'] = $model->getDBO()->insertid();
            $user = JFactory::getUser();
            $model->addMembers($id, $user->id);
        }

        $app->setUserState($context.'.id', null);
        $app->setUserState($context.'.data', null);
        $this->setMessage(JText::_(($lang->hasKey($this->text_prefix.'_SAVE_SUCCESS') ? $this->text_prefix : 'JLIB_APPLICATION').'_SAVE_SUCCESS'));

        // redirect
        $this->setRedirect(ProjectsHelper::getLink('project', (int)$data['id']));
        return true;
    }

    /**
     * Cancel
     *
     * @see libraries/joomla/application/component/JControllerForm#Cancel()
     */
    public function cancel() {
        // Check for request forgeries
        JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $model = $this->getModel();
        $id = $model->getState('project.id', 0);
        if (!parent::cancel()) {
            //return false;
        }

        // if has id
        if ($id) {
            $append = '&layout=default&id='.$id;
            $this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$append, false));
        }

        return true;
    }

    /**
     * Method to publish a list
     *
     * @since	1.6
     */
    function publish() {
        // Check for request forgeries
        JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $id = $cid[0];
        if (empty($cid)) {
            return JError::raiseWarning(500, JText::_($this->text_prefix.'_NO_ITEM_SELECTED'));
        }
        $data = array('publish' => 1, 'unpublish' => 0, 'archive' => 2, 'trash' => -2, 'report' => -3);
        $task = $this->getTask();
        $value = JArrayHelper::getValue($data, $task, 0, 'int');
        $append = '&layout=default&id='.$id;

        $model = $this->getModel();
        // Publish the items.
        if (!$model->publish($cid, $value)) {
            JError::raiseWarning(500, $model->getError());
        } else {
            switch ($value) {
                case 2:
                    $ntext = 'COM_PROJECTS_PROJECT_ARCHIVED';
                    break;

                case 1:
                    $ntext = 'COM_PROJECTS_PROJECT_PUBLISHED';
                    break;

                case 0:
                    $ntext = 'COM_PROJECTS_PROJECT_UNPUBLISHED';
                    break;

                case -2:
                    $ntext = 'COM_PROJECTS_PROJECT_TRASHED';
                    break;

                case -3:
                    $ntext = 'COM_PROJECTS_PROJECT_REPORTED';
                    break;
            }
            $this->setMessage(JText::_($ntext));
        }

        $this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$append, false));
        return true;
    }

    /**
     * Removes an item.
     *
     * @since	1.6
     */
    function delete() {
        // Check for request forgeries
        JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // Get items to publish from the request.
        $model = $this->getModel();
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $id = $cid[0];
        if (empty($id)) {
            return JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
        }

        // Remove the items.
        if ($model->delete($id)) {
            $this->setMessage(JText::_($this->text_prefix.'_ITEM_DELETED', count($id)));
        } else {
            $this->setMessage($model->getError());
        }

        $append = '&id='.$app->getUserState('portfolio.id');
        $this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list.$append, false));
        return true;
    }

    /**
     * Assigns members to a project
     *
     * @since	1.6
     */
    public function assignMembers() {
        // Check for request forgeries
        JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

        $model = $this->getModel();
        $members = JRequest::getVar('cid', array(), '', 'array');

        $id = JRequest::getInt('id', 0);

        if (!$model->addMembers($id, $members)) {
            JError::raiseWarning(500, JText::_('JERROR_AN_ERROR_HAS_OCCURRED'));
            return false;
        }

        $append = '&type=assign&layout=default&id='.$id;
        $this->setRedirect(
                JRoute::_('index.php?option='.$this->option.'&view=members'.$append),
                JText::_('COM_PROJECTS_MEMBERS_ASSIGN_SUCCESSFUL'));

        return true;
    }

    /**
     * deletes members from a project
     *
     * @since	1.6
     */
    public function unassignMembers() {
        // Check for request forgeries
        JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

        $model = $this->getModel();
        $members = JRequest::getVar('cid', array(), '', 'array');
        $id = JRequest::getInt('id', 0);

        if (!$model->removeMembers($id, $members)) {
            JError::raiseWarning(500, JText::_('JERROR_AN_ERROR_HAS_OCCURRED'));
            return false;
        }

        $append = '&type=delete&layout=default&id='.$id;
        $this->setRedirect(
                JRoute::_('index.php?option='.$this->option.'&view=members'.$append),
                JText::_('COM_PROJECTS_MEMBERS_DELETE_SUCCESSFUL'));

        return true;
    }

}