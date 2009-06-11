<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.controller' );

/**
 * The Menu Item Controller
 *
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @since		1.6
 */
class MenusControllerItem extends JController
{
	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * @see		JController
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		// Register proxy tasks.
		$this->registerTask('save2copy',	'save');
		$this->registerTask('save2new',		'save');
		$this->registerTask('apply',		'save');
	}

	/**
	 * Dummy method to redirect back to standard controller
	 *
	 * @return	void
	 */
	public function display()
	{
		$this->setRedirect(JRoute::_('index.php?option=com_menus', false));
	}

	/**
	 * Method to add a new menu item.
	 *
	 * @return	void
	 */
	public function add()
	{
		// Initialize variables.
		$app = &JFactory::getApplication();

		// Clear the row edit information from the session.
		$app->setUserState('com_menus.edit.item.id',	null);
		$app->setUserState('com_menus.edit.item.data',	null);

		// Check if we are adding for a particular menutype
		$menuType = $app->getUserStateFromRequest($this->_context.'.filter.menutype', 'menutype', 'mainmenu');

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_menus&view=item&layout=edit&menutype='.$menuType, false));
	}

	/**
	 * Method to edit an existing menu item.
	 *
	 * @return	void
	 */
	public function edit()
	{
		// Initialize variables.
		$app	= &JFactory::getApplication();
		$ids	= JRequest::getVar('cid', array(), '', 'array');

		// Get the id of the group to edit.
		$id		=  (empty($ids) ? JRequest::getInt('item_id') : (int) array_pop($ids));

		// Get the menu item model.
		$model	= &$this->getModel('Item');

		// Check that this is not a new item.
		if ($id > 0)
		{
			$item = $model->getItem($id);

			// If not already checked out, do so.
			if ($item->checked_out == 0)
			{
				if (!$model->checkout($id))
				{
					// Check-out failed, go back to the list and display a notice.
					$message = JText::sprintf('JError_Checkout_failed', $model->getError());
					$this->setRedirect('index.php?option=com_menus&view=item&item_id='.$id, $message, 'error');
					return false;
				}
			}
		}

		// Push the new row id into the session.
		$app->setUserState('com_menus.edit.item.id',	$id);
		$app->setUserState('com_menus.edit.item.data',	null);

		$this->setRedirect('index.php?option=com_menus&view=item&layout=edit');

		return true;
	}

	/**
	 * Method to cancel an edit
	 *
	 * Checks the item in, sets item ID in the session to null, and then redirects to the list page.
	 *
	 * @return	void
	 */
	public function cancel()
	{
		JRequest::checkToken() or jExit(JText::_('JInvalid_Token'));

		// Initialize variables.
		$app	= &JFactory::getApplication();
		$model	= &$this->getModel('Item');

		// Get the previous row id.
		$previousId	= (int) $app->getUserState('com_menus.edit.item.id');

		// If rows ids do not match, checkin previous row.
		if ($model->checkin($previousId))
		{
			// Redirect to the list screen.
			$this->setRedirect(JRoute::_('index.php?option=com_menus&view=items', false));
		}
		else
		{
			// Check-in failed
			$message = JText::sprintf('JError_Checkin_failed', $model->getError());
			$this->setRedirect('index.php?option=com_menus&view=items', $message, 'error');
		}

		// Clear the row edit information from the session.
		$app->setUserState('com_menus.edit.item.id',	null);
		$app->setUserState('com_menus.edit.item.data',	null);
	}

	/**
	 * Method to save a menu item.
	 *
	 * @return	void
	 */
	public function save()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialize variables.
		$app	= &JFactory::getApplication();
		$model	= &$this->getModel('Item');
		$task	= $this->getTask();

		// Get the posted values from the request.
		$data	= JRequest::getVar('jform', array(), 'post', 'array');
		$map	= JRequest::getVar('menuid', array(), 'post', 'array');

		// Populate the row id from the session.
		$data['id'] = (int) $app->getUserState('com_menus.edit.item.id');

		// The save2copy task needs to be handled slightly differently.
		if ($task == 'save2copy')
		{
			// Check-in the original row.
			if (!$model->checkin())
			{
				// Check-in failed, go back to the item and display a notice.
				$message = JText::sprintf('JError_Checkin_saved', $model->getError());
				$this->setRedirect('index.php?option=com_menus&view=item&layout=edit', $message, 'error');
				return false;
			}

			// Reset the ID and then treat the request as for Apply.
			$data['id']	= 0;
			$task		= 'apply';
		}

		// Validate the posted data.
		$form	= &$model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}
		$data	= $model->validate($form, $data);

		// Push the menu id map back into the array
		$data['map'] = &$map;

		// Check for validation errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'notice');
				}
				else {
					$app->enqueueMessage($errors[$i], 'notice');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_menus.edit.item.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=com_menus&view=item&layout=edit', false));
			return false;
		}

		// Attempt to save the data.
		if (!$model->save($data))
		{
			// Save the data in the session.
			$app->setUserState('com_menus.edit.item.data', $data);

			// Redirect back to the edit screen.
			$this->setMessage(JText::sprintf('JError_Save_failed', $model->getError()), 'notice');
			$this->setRedirect(JRoute::_('index.php?option=com_menus&view=item&layout=edit', false));
			return false;
		}

		// Save succeeded, check-in the row.
		if (!$model->checkin())
		{
			// Check-in failed, go back to the row and display a notice.
			$message = JText::sprintf('JError_Checkin_saved', $model->getError());
			$this->setRedirect('index.php?option=com_menus&view=item&layout=edit', $message, 'error');
			return false;
		}

		$this->setMessage(JText::_('JController_Save_success'));

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				// Set the row data in the session.
				$app->setUserState('com_menus.edit.item.id',	$model->getState('item.id'));
				$app->setUserState('com_menus.edit.item.data',	null);

				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option=com_menus&view=item&layout=edit', false));
				break;

			case 'save2new':
				// Clear the row id and data in the session.
				$app->setUserState('com_menus.edit.item.id',	null);
				$app->setUserState('com_menus.edit.item.data',	null);

				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option=com_menus&view=item&layout=edit', false));
				break;

			default:
				// Clear the row id and data in the session.
				$app->setUserState('com_menus.edit.item.id',	null);
				$app->setUserState('com_menus.edit.item.data',	null);

				// Redirect to the list screen.
				$this->setRedirect(JRoute::_('index.php?option=com_menus&view=items', false));
				break;
		}
	}

	function setType()
	{
		// Initialize variables.
		$app	= &JFactory::getApplication();
		$ids	= JRequest::getVar('cid', array(), '', 'array');
		$model	= &$this->getModel('Item');

		// Get the id of the group to edit.
		$id =  (empty($ids) ? JRequest::getInt('item_id') : (int) array_pop($ids));

		$type = JRequest::getVar('type');
		$type = json_decode(base64_decode($type));

		var_dump($type);
	}

	/**
	 * Method to run batch opterations.
	 *
	 * @return	void
	 */
	function batch()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialize variables.
		$app	= &JFactory::getApplication();
		$model	= &$this->getModel('Item');
		$vars	= JRequest::getVar('batch', array(), 'post', 'array');
		$cid	= JRequest::getVar('cid', array(), 'post', 'array');

		// Preset the redirect
		$this->setRedirect('index.php?option=com_menus&view=items');

		// Attempt to run the batch operation.
		if ($model->batch($vars, $cid))
		{
			$this->setMessage(JText::_('Menus_Batch_success'));
			return true;
		}
		else
		{
			$this->setMessage(JText::_(JText::sprintf('Menus_Error_Batch_failed', $model->getError())));
			return false;
		}
	}
}
