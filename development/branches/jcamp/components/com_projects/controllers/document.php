<?php
/**
 * @version		$Id: article.php 17873 2010-06-25 18:24:21Z 3dentech $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * @package		Joomla.Site
 * @subpackage	com_project
 */
class ProjectsControllerDocument extends JControllerForm
{
	protected $_context = 'com_projects.edit.document';
	

	/**
	 * Constructor
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		$this->registerTask('apply',		'save');
		$this->registerTask('save2new',		'save');
		$this->registerTask('save2copy',	'save');
	}
	
	
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


	/**
	 * Save the record
	 */
	public function save()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app		= JFactory::getApplication();
		$context	= $this->_context.'.';
		$model		= $this->getModel();
		$task		= $this->getTask();
		$id 		= (int)$model->getState('document.id', 0);
	    $menu = $app->getMenu();
		
		// Get posted form variables.
		$data		= JRequest::getVar('jform', array(), 'post', 'array');

		// Populate the row id from the session.
		$data['id'] = $id;
		$isNew = !$id;

		// Split introtext and fulltext
		$pattern    = '#<hr\s+id=(["\'])system-readmore\1\s*/?>#i';
		$text		= $data['text'];
		$tagPos		= preg_match($pattern, $text);

		if ($tagPos == 0) {
			$data['introtext'] = $text;
		}
		else {
			list($data['introtext'], $data['fulltext']) = preg_split($pattern, $text, 2);
		}

		// Validate the posted data.
		$form	= $model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}
		$data	= $model->validate($form, $data);

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
			$app->setUserState($context.'data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(ProjectsHelper::getLink('document', $data['id'].'&layout=edit'));
			return false;
		}

		// Attempt to save the data.
		$append= '&Itemid='.$menu->getActive()->id;
		if (!$model->save($data))
		{
			// Save the data in the session.
			$app->setUserState($context.'data', $data);
			
			// Redirect back to the edit screen.
			$this->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()), 'notice');
			$this->setRedirect(JRoute::_('index.php?option=com_projects&view=document&layout=edit'.$append));
			return false;
		}
		
		// Save succeeded, check-in the row.
		if (!$model->checkin())
		{
			// Check-in failed, go back to the row and display a notice.
			$message = JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError());
			$this->setRedirect('index.php?option=com_projects&view=document&layout=edit'.$append, $message, 'error');
			return false;
		}
	
		// Set the row data in the session.
		$app->setUserState($context.'id', $model->getState('document.id'));
		$app->setUserState($context.'data',	null);
		
		// Redirect back to the edit screen.
		$this->setMessage(JText::_('COM_PROJECTS_DOCUMENT_SAVE_SUCCESS'));
		$this->setRedirect(ProjectsHelper::getLink('document', $model->getState('document.id')), false);
		return true;
	}
}