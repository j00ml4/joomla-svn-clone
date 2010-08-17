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
class ProjectsViewTask extends JView {

    protected $item;
    protected $form;
    protected $params;
    protected $canDo;
    protected $prefix;

    /**
     * Display project
     */
    public function display($tpl = null) {
        $app = &JFactory::getApplication();
        $model = &$this->getModel();

        //Get Model data
        $this->item = &$model->getItem();
        $this->params = &$app->getParams();
        $this->canDo = &ProjectsHelper::getActions();

        // Type
        $this->params->set('type', $model->getState('task.type'));

        // Layout
        $layout = $this->getLayout();
        switch ($layout) {
            case 'new':
            case 'edit':
            case 'form':
                $layout = 'edit';
                $this->form = &$model->getForm();
                if (empty($this->item)) {
                    $this->params->set('catid', $app->getUserState('task.category.id', 0));
                    $access = 'project.create';
                } else {
                    $access = 'project.edit';
                }

                // Access
                if (!$this->canDo->get($access)) {
                    return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
                }
                break;
            case 'view':
                $layout = 'view';
                if (!$this->canDo->get('task.view')) {
                    return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
                }
                if (empty($this->item->id)) {
                    return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
                }
                break;

            default:
                $layout = 'default';
                // Access
                if (!$this->canDo->get('task.view')) {
                    return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
                }
                if (empty($this->item->id)) {
                    return JError::raiseError(404, JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'));
                }
                break;
        }

        // set a correct prefix
        require_once JPATH_COMPONENT . '/helpers/tasks.php';
        $this->prefix = TasksHelper::getPrefix($app->getUserState('task.type'));

        // Display the view
        $this->setLayout($layout);
        parent::display($tpl);
    }

}
