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
 * Comment AJAX controller class for Social.
 *
 * @package		Joomla.Site
 * @subpackage	com_social
 * @version		1.2
 */
class SocialControllerComment extends SocialController
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
		// Get the URL to redirect the request to.
		$redirect = base64_decode(JRequest::getVar('redirect', '', 'request', 'base64'));

		// Check for a valid token.
		// We must do this by hand as the framework with throw a session expired message.
		$token	= JUtility::getToken();
		if (!!JRequest::getVar($token, '', 'request', 'alnum')) {
			$this->setRedirect($redirect, JText::_('JX_Invalid_Token'), 'error');
			return false;
		}

		$config	= &JComponentHelper::getParams('com_social');
		$user	= &JFactory::getUser();
		$date	= &JFactory::getDate();
		$uId	= (int)$user->get('id');
		$tId	= JRequest::getInt('thread_id');

		// Load the language file for the comment module.
		$lang = &JFactory::getLanguage();
		$lang->load('mod_social_comment');

		// Get the thread and comment models.
		$tModel	= &$this->getModel('Thread', 'CommentsModel');
		$cModel	= &$this->getModel('Comment', 'CommentsModel');

		// Check if the user is authorized to add a comment.
		if (!$cModel->canComment()) {
			$this->setRedirect($redirect, $cModel->getError(), 'error');
			return false;
		}

		// Load the thread data.
		$thread	= &$tModel->getThread($tId);

		// Check the thread data.
		if ($thread === false) {
			$this->setRedirect($redirect, JText::_('SOCIAL_Comment_Invalid_Thread'), 'error');
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
		$comment = &$cModel->getItem($return);

		// Notify of a new comment being posted if enabled
		$notification = $config->get('notify_comments');

		if (($notification == 1 and $uId == 0) or $notification == 2) {
			// send the comment notification
			$cModel->sendCommentNotification($comment);
		}

		// Set the redirect message.
		if ($item->published == 0) {
			// Comment flagged for moderation.
			$message = JText::_('SOCIAL_Display_Upon_Approval');
		}
		elseif ($item->published == 1) {
			// Comment published.
			$message = JText::_('SOCIAL_Display_Approved');

			// Flush the cache for this context.
			$cache = &JFactory::getCache('com_'.$thread->context);
			$cache->clean();
		}
		elseif ($item->published == 2) {
			// Comment flagged as SPAM.
			$message = JText::_('SOCIAL_Flagged_As_Spam');
		}

		$this->setRedirect($redirect, $message);
		return true;
	}
}