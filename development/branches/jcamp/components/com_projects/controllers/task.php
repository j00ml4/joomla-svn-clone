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
		if(!parent::save()){
			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=com_projects&view=task&layout=edit&type='.$this->getModel()->getState('task.type').'&Itemid='.ProjectsHelper::getMenuItemId(), false));	
			return false;
		}
		// Redirect to list of tasks
		$this->setRedirect($this->_getReturnPage());	
	}
	
	/**
	 * Method to generate link to list of tasks
	 *
	 * @return	URI to list of tasks of this project
	 * @since	1.6
	 */
	protected function _getReturnPage()
	{
		$app = &JFactory::getApplication();
		return JRoute::_('index.php?option=com_projects&view=tasks&type='.$this->getModel()->getState('task.type').'id='.$app->getUserState('project.id').'&Itemid='.ProjectsHelper::getMenuItemId(),false);
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
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise variables.
		$app		= JFactory::getApplication();

		// Get the previous menu item id (if any) and the current menu item id.
		$previousId	= (int) $app->getUserState('task.id');

		// Get the menu item model.
		$model = $this->getModel();

		// If rows ids do not match, checkin previous row.
		if (!$model->checkin($previousId))
		{
			// Check-in failed, go back to the menu item and display a notice.
			$message = JText::sprintf('JError_Checkin_failed', $model->getError());
			$this->setRedirect(JRoute::_('index.php?option=com_projects&view=task&type='.$this->getModel()->getState('task.type').'&Itemid='.ProjectsHelper::getMenuItemId(),false), $message, 'error');
			return false;
		}

		// Clear the menu item edit information from the session.
		$app->setUserState('task.id',	null);
		$app->setUserState('task.data',	null);

		// Redirect to the list screen.
		$this->setRedirect($this->_getReturnPage());
	}
}