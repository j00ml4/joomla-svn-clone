<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Trackback controller class for Social.
 *
 * @package		Joomla.Site
 * @subpackage	com_social
 * @since		1.2
 */
class SocialControllerTrackback extends SocialController
{
	/**
	 * Method to add a trackback to a thread.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function add()
	{
		// Get the current application object.
		$application = JFactory::getApplication();

		// Get a JXTrackback object.
		jx('jx.webservices.trackback');
		$trackback = JTrackback::getInstance();

		$config = JFactory::getConfig();
		$config->getValue('config.sitename');

		// Setup the response object.
		JResponse::allowCache(false);
		JResponse::setHeader('Content-type', 'text/xml');

		// Get some essential data.
		$config	= JComponentHelper::getParams('com_social');
		$date	= JFactory::getDate();
		$tId	= JRequest::getInt('thread_id');

		// Get the thread and comment models.
		$tModel	= $this->getModel('Thread', 'CommentsModel');
		$cModel	= $this->getModel('Comment', 'CommentsModel');

		// Load the thread data.
		$thread	= $tModel->getThread($tId);

		// Check the thread data.
		if ($thread === false)
		{
			// Set the error response.
			JResponse::setBody($trackback->getResponseXml(false, JText::_('SOCIAL_Invalid_Thread')));

			// Echo the response.
			echo JResponse::toString();

			// Close the application.
			$application->close();

			return false;
		}

		// Verify the trackback.
		$base = JURI::getInstance();
		if (!$data = $trackback->getVerifiedData($base->toString( array('scheme', 'host', 'port')).JRoute::_($thread->page_route)))
		{
			// Set the error response.
			JResponse::setBody($trackback->getResponseXml(false, JText::_('SOCIAL_Invalid_Trackback')));

			// Echo the response.
			echo JResponse::toString();

			// Close the application.
			$application->close();

			return false;
		}

		// Prepare the trackback data.
		$comment = array();
		$comment['thread_id']		= $tId;
		$comment['user_id']			= 0;
		$comment['trackback']		= 1;
		$comment['url']				= $data['url'];
		$comment['subject']			= $data['title'];
		$comment['body']			= $data['excerpt'];
		$comment['created_date']	= $date->toMySQL();
		$comment['address']			= $_SERVER['REMOTE_ADDR'];

		// Attempt to add the trackback.
		$return = $cModel->add($comment);

		// Check if the trackback was added successfully.
		if (JError::isError($return))
		{
			// Set the response message.
			JResponse::setBody($trackback->getResponseXml(false, $return->getMessage()));

			// Echo the response.
			echo JResponse::toString();

			// Close the application.
			$application->close();
			return false;
		}

		// Load the comment from the database.
		$comment = $cModel->getItem($return);

		// Notify of a new comment being posted if enabled
		$notification = $config->get('notify_comments');

		if (($notification == 1) or ($notification == 2)) {
			// send the comment notification
			$cModel->sendCommentNotification($comment);
		}

		// Set the response message.
		JResponse::setBody($trackback->getResponseXml(true));

		// Echo the response.
		echo JResponse::toString();

		// Close the application.
		$application->close();
		return true;
	}
}