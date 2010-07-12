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
	//protected $text_prefix = 'COM_PROJECTS';
	
	
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param	string	The model name. Optional.
	 * @param	string	The class prefix. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	object	The model.
	 */
	public function getModel($name = 'Task', $prefix = 'ProjectsModel', $config = null)
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
		return true;
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
		return true;
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
		return true;
	}
	
	
	/**
	 * Save a record
	 * 
	 * @see libraries/joomla/application/component/JControllerForm#save()
	 */
	public function save(){
	//	$model		= &$this->getModel();
	//	$id 		= &$model->getState('task.id', 0);

//		dump(JRequest::getVar('jform', array(), 'post', 'array'));
//		if(!parent::save()){
	
//			return false;
//		}
			parent::save();
//			echo $this->getModel()->getTable()->getError().'###';
			echo $this->getDbo()->getErrorMsg();
			dump($this->getModel()->getTable());
		
//		return true;
	}
	
}