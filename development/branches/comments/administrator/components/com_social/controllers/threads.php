<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Thread list controller.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_social
 * @since		1.3
 */
class SocialControllerThreads extends SocialController
{
	/**
	 * Proxy for getModel.
	 */
	public function &getModel($name = 'Thread', $prefix = 'SocialModel')
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}

	/**
	 * Method to delete a list of threads.
	 */
	public function delete()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise variables.
		$pks	= JRequest::getVar('cid', array(), '', 'array');
		$model	= $this->getModel();

		// Delete the records.
		if (!$model->delete($pks)) {
			JError::raiseWarning(500, $model->getError());
		}
		else
		{
			$this->setMessage(JText::sprintf('COM_SOCIAL_N_Records_Deleted', count($pks)));
		}

		$this->setRedirect('index.php?option=com_social&view=threads');
	}

	/**
	 * Method to reset the comments on a thread.
	 */
	public function resetcomments()
	{
		// Check for request forgeries.
		JRequest::checkToken('request') or jexit(JText::_('JInvalid_Token'));

		// Initialise variables.
		$pk		= JRequest::getInt('id', 0, '', 'array');
		$model	= $this->getModel();

		// Delete the records.
		if (!$model->resetComments($pk)) {
			JError::raiseWarning(500, $model->getError());
		}
		else
		{
			$this->setMessage(JText::_('COM_SOCIAL_Reset_Comments_Success'));
		}

		$this->setRedirect('index.php?option=com_social&view=threads');
	}

	/**
	 * Method to reset the comments on a thread.
	 */
	public function resetratings()
	{
		// Check for request forgeries.
		JRequest::checkToken('request') or jexit(JText::_('JInvalid_Token'));

		// Initialise variables.
		$pk		= JRequest::getInt('id', 0, '', 'array');
		$model	= $this->getModel();

		// Delete the records.
		if (!$model->resetRatings($pk)) {
			JError::raiseWarning(500, $model->getError());
		}
		else
		{
			$this->setMessage(JText::_('COM_SOCIAL_Reset_Ratings_Success'));
		}

		$this->setRedirect('index.php?option=com_social&view=threads');
	}

}