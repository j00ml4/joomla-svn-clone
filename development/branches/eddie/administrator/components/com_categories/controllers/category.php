<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

defined('_JEXEC') or die;

/**
 * Category controller.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_categories
 * @version		1.0
 */
class CategoryControllerCategory extends JController
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// Map the save tasks.
		$this->registerTask('save2new',		'save');
		$this->registerTask('apply',		'save');

		// Map the publishing state tasks.
		$this->registerTask('unpublish',	'publish');
		$this->registerTask('trash',		'publish');

		// Map the ordering tasks.
		$this->registerTask('orderup',		'ordering');
		$this->registerTask('orderdown',	'ordering');
	}

	/**
	 * Dummy method to redirect back to standard controller
	 *
	 * @access	public
	 * @return	void
	 * @since	1.0
	 */
	function display()
	{
		$this->setRedirect(JRoute::_('index.php?option=com_categories', false));
	}

	/**
	 * Method to add a new category.
	 *
	 * @access	public
	 * @return	void
	 * @since	1.0
	 */
	function add()
	{
		// Initialize variables.
		$app = &JFactory::getApplication();

		// Clear the category id from the session.
		$app->setUserState('com_categories.edit.category.id', null);

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_categories&view=category&layout=edit', false));
	}

	/**
	 * Method to check out a category for editing and redirect to the edit form.
	 *
	 * If a different category was previously checked-out, the previous category will be checked
	 * in first.
	 *
	 * @access	public
	 * @return	void
	 * @since	1.0
	 */
	function edit()
	{
		// Initialize variables.
		$app	= &JFactory::getApplication();
		$cid	= JRequest::getVar('cid', array(), '', 'array');

		// Get the previous category id (if any) and the current category id.
		$previousId = (int) $app->getUserState('com_categories.edit.category.id');
		$catId		= (int) (count($cid) ? $cid[0] : JRequest::getInt('cat_id'));

		// Set the category id for the category to edit in the session.
		$app->setUserState('com_categories.edit.category.id', $catId);

		// Get the model.
		$model = &$this->getModel('Category', 'CategoryModel');

		// Check out the category.
		if ($catId) {
			$model->checkout($catId);
		}

		// Check in the previous category.
		if ($previousId) {
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_categories&view=category&layout=edit', false));
	}

	/**
	 * Method to cancel an edit
	 *
	 * Checks the item in, sets item id in the session to null, and then redirects to the list screen.
	 *
	 * @access	public
	 * @return	void
	 * @since	1.0
	 */
	function cancel()
	{
		// Initialize variables.
		$app = &JFactory::getApplication();

		// Get the previous category id.
		$previousId = (int) $app->getUserState('com_categories.edit.category.id');

		// Get the model.
		$model = &$this->getModel('Category', 'CategoryModel');

		// Check in the previous category.
		if ($previousId) {
			$model->checkin($previousId);
		}

		// Clear the category id from the session.
		$app->setUserState('com_categories.edit.category.id', null);

		// Redirect to the list screen.
		$this->setRedirect(JRoute::_('index.php?option=com_categories&view=categories', false));
	}

	/**
	 * Method to save a category.
	 *
	 * @access	public
	 * @return	void
	 * @since	1.0
	 */
	function save()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialize variables.
		$app = &JFactory::getApplication();

		// Get the posted values from the request.
		$data = JRequest::getVar('JForm', array(), 'post', 'array');

		// Populate the row id from the session.
		$data['id'] = (int) $app->getUserState('com_categories.edit.category.id');

		// Get the model and attempt to validate the posted data.
		$model = &$this->getModel('Category', 'CategoryModel');
		$return	= $model->validate($data);

		// Check for validation errors.
		if ($return === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'notice');
				} else {
					$app->enqueueMessage($errors[$i], 'notice');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_categories.edit.category.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=com_categories&view=category&layout=edit', false));
			return false;
		}

		// Attempt to save the data.
		$return	= $model->save($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_categories.edit.category.data', $data);

			// Redirect back to the edit screen.
			$this->setMessage(JText::sprintf('Category_SAVE FAILED', $model->getError()), 'notice');
			$this->setRedirect(JRoute::_('index.php?option=com_categories&view=category&layout=edit', false));
			return false;
		}

		// Redirect the user and adjust session state based on the chosen task.
		switch ($this->_task)
		{
			case 'apply':
				// Check out the category.
				$app->setUserState('com_categories.edit.category.id', $return);
				$model->checkout($return);

				// Redirect back to the edit screen.
				$this->setMessage(JText::_('Category_SAVE SUCCESS'));
				$this->setRedirect(JRoute::_('index.php?option=com_categories&view=category&layout=edit', false));
				break;

			case 'save2new':
				// Check in the category.
				$catId = (int) $app->getUserState('com_categories.edit.category.id');
				if ($catId) {
					$model->checkin($catId);
				}

				// Clear the category id from the session.
				$app->setUserState('com_categories.edit.category.id', null);

				// Redirect back to the edit screen.
				$this->setMessage(JText::_('Category_SAVE SUCCESS'));
				$this->setRedirect(JRoute::_('index.php?option=com_categories&view=category&layout=edit', false));
				break;

			default:
				// Check in the category.
				$catId = (int) $app->getUserState('com_categories.edit.category.id');
				if ($catId) {
					$model->checkin($catId);
				}

				// Clear the category id from the session.
				$app->setUserState('com_categories.edit.category.id', null);

				// Redirect to the list screen.
				$this->setMessage(JText::_('Category_SAVE SUCCESS'));
				$this->setRedirect(JRoute::_('index.php?option=com_categories&view=categories', false));
				break;
		}

		// Flush the data from the session.
		$app->setUserState('com_categories.edit.category.data', null);
	}

	/**
	 * Method to delete categories.
	 *
	 * @access	public
	 * @return	void
	 * @since	1.0
	 */
	function delete()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Get and sanitize the items to delete.
		$cid = JRequest::getVar('cid', null, 'post', 'array');
		JArrayHelper::toInteger($cid);

		// Get the model.
		$model = &$this->getModel('Category', 'CategoryModel');

		// Attempt to delete the item(s).
		if (!$model->delete($cid)) {
			$this->setMessage(JText::sprintf('Category_DELETE FAILED', $model->getError()), 'notice');
		}
		else {
			$this->setMessage(JText::sprintf('Category_DELETE SUCCESS', count($cid)));
		}

		// Redirect to the list screen.
		$this->setRedirect(JRoute::_('index.php?option=com_categories&view=categories', false));
	}

	/**
	 * Method to set the published state of categories.
	 *
	 * @access	public
	 * @return	void
	 * @since	1.0
	 */
	function publish()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Get and sanitize the items to delete.
		$cid = JRequest::getVar('cid', null, 'post', 'array');
		JArrayHelper::toInteger($cid);

		// Get the model.
		$model = &$this->getModel('Category', 'CategoryModel');

		// Attempt to set the publishing state for the categories.
		switch ($this->_task)
		{
			case 'publish':
				$result = $model->publish($cid);
				break;

			case 'unpublish':
				$result = $model->unpublish($cid);
				break;

			case 'trash':
				$result = $model->trash($cid);
				break;
		}

		// If there is a problem, set the error message.
		if (!$result) {
			$this->setMessage($model->getError());
		}

		// Redirect to the list screen.
		$this->setRedirect(JRoute::_('index.php?option=com_categories&view=categories', false));
	}

	/**
	 * Method to adjust the ordering of a category in the tree.
	 *
	 * @access	public
	 * @return	void
	 * @since	1.0
	 */
	function ordering()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Get the category id from the request.
		$cid	= JRequest::getVar('cid', array(), '', 'array');
		$catId	= (int) (count($cid) ? $cid[0] : JRequest::getInt('cat_id'));

		// Get the model.
		$model = &$this->getModel('Category', 'CategoryModel');

		// Attempt to adjust the ordering for the categort.
		switch ($this->_task)
		{
			case 'orderup':
				// Adjust the ordering up one.
				$result = $model->ordering($catId, -1);
				break;

			case 'orderdown':
				// Adjust the ordering down one.
				$result = $model->ordering($catId, 1);
				break;

			default:
				// Get the ordering adjustment from the request.
				$adjustment = JRequest::getInt('adjustment', 0);

				// Adjust the ordering based on the adjustment value.
				$result = $model->ordering($catId, $adjustment);
				break;
		}

		// If there is a problem, set the error message.
		if (!$result) {
			$this->setMessage($model->getError());
		}

		// Redirect to the list view.
		$this->setRedirect(JRoute::_('index.php?option=com_categories&view=categories', false));
	}

	/**
	 * Change or increment the access
	 */
	function access()
	{
		// Check for request forgeries
		JRequest::checkToken() or die('Invalid Token');

		$cid	= JRequest::getVar('cid',		null, 'post', 'array');
		$level	= JRequest::getVar('level',	null, 'post');

		$model = &$this->getModel('Category');
		$result	= $model->access($cid, $level);
		if (JError::isError($result)) {
			$this->setMessage($result->getMessage());
		}
		else {
			$this->setMessage(JText::sprintf('Items updated', $result));
		}

		// Redirect to the list screen.
		$this->setRedirect(JRoute::_('index.php?option=com_categories&view=categories', false));
	}

	//
	// Ancilliary methods
	//

	/**
	 * "Quick" methods for adding content
	 */
	function quick()
	{
		// Check for request forgeries
		JRequest::checkToken() or die('Invalid Token');

		$vars	= JRequest::getVar('quick', array(), 'post', 'array');
		$cid	= JRequest::getVar('cid', null, 'post', 'array');
		$result	= true;

		$model = &$this->getModel('Category');
		$model->setState('vars',	$vars);
		$model->setState('ids',	$cid);
		$result = $model->quick();
		if (JError::isError($result)) {
			JError::raiseNotice(500, $result->getMessage());
		}

		// Redirect to the list screen.
		$this->setRedirect(JRoute::_('index.php?option=com_categories&view=categories', false));
	}

	/**
	 * Method to run batch opterations.
	 *
	 * @access	public
	 * @return	void
	 * @since	1.0
	 */
	function batch()
	{
		// Get variables from the request.
		$vars	= JRequest::getVar('batch', array(), 'post', 'array');
		$cid	= JRequest::getVar('cid', null, 'post', 'array');

		$model = &$this->getModel('Category');
		$model->batch($vars, $cid);

		// Redirect to the list view.
		$this->setRedirect(JRoute::_('index.php?option=com_categories&view=categories', false));
	}

}