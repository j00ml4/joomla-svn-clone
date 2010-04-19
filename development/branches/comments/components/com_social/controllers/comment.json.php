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
 * Comment JSON controller class for Social.
 *
 * @package		Joomla.Site
 * @subpackage	com_social
 * @since		1.6
 */
class SocialControllerComment extends JController
{
	/**
	 * The display method should never be requested from the extended
	 * controller.  Throw an error page and exit gracefully.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function display()
	{
		JError::raiseError(404, 'Resource Not Found');
	}

	/**
	 * Method to add a comment via JSON.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function add()
	{
		// Check for a valid token. If invalid, send a 403 with the error message.
		JRequest::checkToken('request') or $this->sendResponse(new JException(JText::_('JX_Invalid_Token'), 403));

		$config	= JComponentHelper::getParams('com_social');
		$user	= JFactory::getUser();
		$date	= JFactory::getDate();
		$uId	= (int)$user->get('id');
		$tId	= JRequest::getInt('thread_id');

		// Load the language file for the comment module.
		$lang = JFactory::getLanguage();
		$lang->load('mod_social_comment');

		// Get the thread and comment models.
		$tModel	= $this->getModel('Thread', 'SocialModel');
		$cModel	= $this->getModel('Comment', 'SocialModel');

		// Check if the user is authorized to add a comment.
		if (!$cModel->canComment()) {
			JError::raiseError(403, $cModel->getError());
			return false;
		}

		// Load the thread data.
		$thread	= $tModel->getThread($tId);

		// Check the thread data.
		if ($thread === false) {
			JError::raiseError(500, JText::_('SOCIAL_Comment_Invalid_Thread'));
			return false;
		}

		// Prepare the comment data.
		$comment					= array();
		$comment['thread_id']		= $tId;
		$comment['user_id']			= $uId;
		$comment['name']			= $uId ? $user->get('name') : JRequest::getString('name', '', 'post');
		$comment['url']				= JRequest::getString('url', '', 'post');
		$comment['subject']			= JRequest::getString('subject', '', 'post');
		$comment['email']			= $uId ? $user->get('email') : JRequest::getString('email', '', 'post');
		$comment['body']			= JRequest::getVar('body', '', 'post', 'string', JREQUEST_ALLOWHTML);
		$comment['created_date']	= $date->toMySQL();
		$comment['address']			= $_SERVER['REMOTE_ADDR'];

		// If html support is not enabled, disable all tags.
		if (!$config->get('enable_html', 0)) {
			$comment['body'] = htmlspecialchars($comment['body']);
		}

		// Attempt to add the comment.
		$return = $cModel->add($comment);

		// Check if the comment was added successfully.
		if (JError::isError($return)) {
			JError::raiseError(500, $return->getMessage());
			return false;
		}

		// Load the comment from the database.
		$comment = $cModel->getItem($return);

		// Add the thread information to the comment for the e-mail template.
		$comment->route = JRoute::_($thread->page_route, false, -1);

		// Configure the Social model so we can get the total number of comments.
		$mModel = $this->getModel('Comments', 'SocialModel');
		$mModel->getState();
		$mModel->setState('filter.thread_id', $tId);
		$mModel->setState('filter.state', 1);

		// Create a response body.
		$response			= new JObject();
		$response->id		= (int)$comment->id;
		$response->total	= (int)$mModel->getTotal();
		$response->position	= strtolower($config->get('list_order')) == 'desc' ? 'top' : 'bottom';

		// Populate the response object message.
		if ($comment->published == 0)
		{
			// Comment flagged for moderation.
			$response->body = JText::_('SOCIAL_Display_Upon_Approval');
		}
		elseif ($comment->published == 1)
		{
			// Set the user name and user login name if applicable.
			$comment->user_name = $uId ? $user->get('name') : '';
			$comment->user_login_name = $uId ? $user->get('username') : '';

			// Comment published.
			$response->body = $cModel->getRenderedComment($comment);

			// Flush the cache for this context.
			$cache = JFactory::getCache('com_'.$thread->context);
			$cache->clean();
		}
		elseif ($comment->published == 2)
		{
			// Comment flagged as SPAM.
			$response->body = JText::_('SOCIAL_Flagged_As_Spam');
		}

		// Notify of a new comment being posted if enabled
		$notification = $config->get('notify_comments');

		if (($notification == 1 and $uId == 0) or $notification == 2) {
			// send the comment notification
			$cModel->sendCommentNotification($comment);
		}

		// Send the response.
		$this->sendResponse($response);
	}

	/**
	 * Method to handle a send a JSON response. The body parameter
	 * can be a JException object for when an error has occurred or
	 * a JObject for a good response.
	 *
	 * @param	object	JObject on success, JException on failure.
	 * @return	void
	 * @since	1.6
	 */
	public function sendResponse($body)
	{
		// Check if we need to send an error code.
		if (JError::isError($body)) {
			JResponse::setHeader('status', $body->getCode());
		}

		// Send the response headers.
		JResponse::setHeader('Content-Type', 'application/json');
		JResponse::sendHeaders();

		// Send the JSON response.
		echo json_encode(new SocialCommentResponse($body));

		// Close the application.
		JFactory::getApplication()->close();
	}
}

/**
 * Social Comment JSON Response Class
 *
 * @package		Joomla.Site
 * @subpackage	com_social
 * @since		1.6
 */
class SocialCommentResponse
{
	public function __construct($state)
	{
		// The old token is invalid so send a new one.
		$this->token = JUtility::getToken(true);

		// Check if we are dealing with an error.
		if (JError::isError($state))
		{
			// Prepare the error response.
			$this->error	= true;
			$this->header	= JText::_('SOCIAL_Comment_Header_Error');
			$this->message	= $state->getMessage();
		}
		else
		{
			// Prepare the response data.
			$this->error	= false;
			$this->id		= (int) $state->id;
			$this->total	= (int) $state->total;
			$this->body		= $state->body;
			$this->position	= $state->position;
			$this->num_text	= $this->total == 1 ? JText::sprintf('Comment_Num', $this->total) : JText::sprintf('SOCIAL_Num', $this->total);
		}
	}
}

// This needs to be AFTER the class declaration because of PHP 5.1.
JError::setErrorHandling(E_ALL, 'callback', array('CommentsControllerComment', 'sendResponse'));