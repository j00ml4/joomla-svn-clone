<?php
/**
 * @version		$Id: article.php 17873 2010-06-25 18:24:21Z 3dentech $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

//jimport('joomla.application.component.controllerform');
require_once JPATH_SITE.DS.'components'.DS.'com_content'.DS.'controllers'.DS.'article.php';

/**
 * @package		Joomla.Site
 * @subpackage	com_project
 */
class ProjectsControllerDocument extends ContentControllerArticle
{
	protected $text_prefix = 'COM_PROJECTS_DOCUMENT';
	protected $_context = 'com_projects.edit.document';
	protected $view_item = 'document';
	protected $view_list = 'documents';

	
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param	string	The model name. Optional.
	 * @param	string	The class prefix. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	object	The model.
	 * @since	1.5
	 */
	public function &getModel($name = 'Document', $prefix = 'ProjectsModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	public function add(){
		parent::add();
		
		$model = $this->getModel();
		$this->setRedirect(ProjectsHelper::getLink('document.form'), false);
	}
	
	public function edit(){
		parent::edit();
		
		$model = $this->getModel();
		$this->setRedirect(ProjectsHelper::getLink('document.form', $model->getState('article.id')), false);
	}

	public function cancel(){
		parent::cancel();
		
		$model = $this->getModel();
		$this->setRedirect(ProjectsHelper::getLink('documents', $model->getState('project.id')), false);
	}
	/**
	 * Save the record
	 */
	public function save()
	{	
		parent::save();

		// Set the row data in the session.
		$app = JFactory::getApplication();		
		$model = $this->getModel();
		
		$this->setMessage(JText::_('COM_PROJECTS_DOCUMENT_SAVE_SUCCESS'));
		$this->setRedirect(ProjectsHelper::getLink('documents', $model->getState('project.id')));
		return true;
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

        return ProjectsHelperACL::canDo('document.create',
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

        return ProjectsHelperACL::canDo('document.edit',
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
        $action = $record->get('id', 0) ? 'document.edit' : 'document.create';

        return ProjectsHelperACL::canDo($action,
                $app->getUserState('portfolio.id'), 
				$app->getUserState('project.id'),
                $record);
    }
}