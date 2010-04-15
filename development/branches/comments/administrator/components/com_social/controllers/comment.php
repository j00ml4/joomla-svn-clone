<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Social controller class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_social
 * @since		1.6
 */
class SocialControllerComment extends JControllerForm
{
	/**
	 * Set the moderation state of a set of comments.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function moderate()
	{
		// Initialise variables.
		$c_id	= JRequest::getVar('moderate', array(), '', 'array');
		$delete	= false;

		if (!is_array($c_id) or (count($c_id) < 1)) {
			$this->setMessage(JText::_('COM_SOCIAL_SELECT_COMMENT_TO_MODERATE'), 'warning');
		} else {
			// Get the model.
			$model = $this->getModel('Comment', 'SocialModel', array('ignore_request' => true));

			/*
			 * We have to split the array because we need to sanitize the array keys.
			 * JArrayHelper::toInteger() will only sanitize the array values.
			 */
			$keys = array_keys($c_id);
			$vals = array_values($c_id);

			// clean the keys and values from the request array using JArrayHelper
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($keys);
			JArrayHelper::toInteger($vals);

			// re-initialize the array and build it with cleaned data
			$c_id = array();
			for ($i = 0, $n = count($keys); $i < $n; $i++) {
				$c_id[$keys[$i]] = $vals[$i];
			}

			// Get the number of comments each action is being performed on.
			$publish	= count(array_keys($c_id, 1, true));
			$defer		= count(array_keys($c_id, 0, true));
			$delete		= count(array_keys($c_id, -1, true));
			$spam		= count(array_keys($c_id, 2, true));

			// Publish the items.
			if(!$model->moderate($c_id)) {
				$this->setMessage( JText::_('COM_SOCIAL_UNABLE_TO_MODERATE_COMMENTS'), 'notice');
			} else {
				$messages = array();

				if ($defer) {
					$messages[] = JText::sprintf('COM_SOCIAL_MODERATE_NUM_DEFERRED', $defer);
				}
				if ($publish) {
					$messages[] = JText::sprintf('COM_SOCIAL_MODERATE_NUM_PUBLISHED', $publish);
				}
				if ($delete) {
					$messages[] = JText::sprintf('COM_SOCIAL_MODERATE_NUM_DELETED', $delete);
				}
				if ($spam) {
					$messages[] = JText::sprintf('COM_SOCIAL_MODERATE_NUM_SPAMMED', $spam);
				}

				$this->setMessage(implode(' ', $messages));
			}
		}

		// Flush the cache.
		$cache = JFactory::getCache();
		$cache->clean(null, 'group');
		$cache->clean(null, 'notgroup');

		if (!$delete && ($return = JRequest::getVar('return', '', 'post', 'base64'))) {
			// Check for the chance of being on the comment page and deleting the comment.
			$this->setRedirect(base64_decode($return));
		} else {
			$this->setRedirect('index.php?option=com_social&view=comments');
		}
	}
}