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
class ProjectsControllerProject extends JControllerForm
{
	protected $view_item = 'project';
	protected $view_list = 'projects';
	protected $text_prefix = 'COM_PROJECTS';
	
	
/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		//die();
		// Define standard task mappings.
		$this->registerTask('unpublish',	'publish');	// value = 0 
		$this->registerTask('archive',		'publish');	// value = 2	// finished
		$this->registerTask('trash',		'publish');	// value = -2
		$this->registerTask('report',		'publish');	// value = -3 	// pending
		//$this->registerTask('orderup',		'reorder');
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
	public function getModel($name = 'Project', $prefix = 'ProjectsModel', $config = null)
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
	protected function allowAdd($data = array())
	{
		return ProjectsHelper::can('project.create', $this->option, $record);
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
	protected function allowEdit($data = array(), $key = 'id')
	{
		return ProjectsHelper::can('project.edit', $this->option, $record);
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
	protected function allowSave($data, $key = 'id')
	{
		$recordId	= isset($data[$key]) ? $data[$key] : 0;
		if ($recordId) {
			return ProjectsHelper::can('project.edit', $this->option, $record);
		} else {
			return ProjectsHelper::can('project.create', $this->option, $record);
		}
	}
	
	
	/**
	 * Save
	 * 
	 * @see libraries/joomla/application/component/JControllerForm#save()
	 */
	public function save()
	{
		$model		= $this->getModel();
		$id 		= $model->getState('project.id', 0);
		if(!parent::save()){
			return false;
		}
		$db 		= $model->getDBO();
		$id 		= ($id)? $id: $db->insertid();
		$append 	= '&layout=default&id='.$id; 
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$append, false));
		return true;
	}
	
	/**
	 * Save
	 * 
	 * @see libraries/joomla/application/component/JControllerForm#save()
	 */
	public function cancel()
	{
		$model		= $this->getModel();
		$id 		= $model->getState('project.id', 0);
		if (!parent::cancel()) {
			//return false;
		}
		
		// if has id
		if ($id){
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
	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to publish from the request.
		$model = $this->getModel();
		$id		= $model->getState('project.id', JRequest::getInt('id', 0));
		if (empty($id)) {
			return JError::raiseWarning(500, JText::_($this->text_prefix.'_NO_ITEM_SELECTED'));
		} 
		$data	= array('publish' => 1, 'unpublish' => 0, 'archive'=> 2, 'trash' => -2, 'report'=>-3);
		$task 	= $this->getTask();
		$value	= JArrayHelper::getValue($data, $task, 0, 'int');
		$append = '&layout=default&id='.$id; 
		
		// Publish the items.
		if (!$model->publish($id, $value)) {
			JError::raiseWarning(500, $model->getError());
		} 
		else {
			switch ($value ) {
				case 2:
					$ntext = $this->text_prefix.'_N_ITEMS_ARCHIVED';
					break;
				
				case 1:
					$ntext = $this->text_prefix.'_N_ITEMS_PUBLISHED';
					break;
					
				case 0:
					$ntext = $this->text_prefix.'_N_ITEMS_UNPUBLISHED';
					break;
					
				case -2:
					die();
					$ntext = $this->text_prefix.'_N_ITEMS_TRASHED';
					break;
					
				case -3:
					$ntext = $this->text_prefix.'_N_ITEMS_REPORTED';
					break;	
			}
			$this->setMessage(JText::plural($ntext, count($id)));
		}
		
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$append, false));
		return true;
	}
	
	
	/**
	 * Removes an item.
	 *
	 * @since	1.6
	 */
	function delete()
	{
		// Check for request forgeries
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to publish from the request.
		$model = $this->getModel();
		$id		= $model->getState('project.id', JRequest::getInt('id', 0));
		if (empty($id)) {
			return JError::raiseWarning(500, JText::_($this->text_prefix.'_NO_ITEM_SELECTED'));
		}
		
		// Remove the items.
		if ($model->delete($id)) {
			$this->setMessage(JText::_($this->text_prefix.'_ITEM_DELETED', count($id)));
		} else {
			$this->setMessage($model->getError());
		}

		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));
		return true;
	}
	
}