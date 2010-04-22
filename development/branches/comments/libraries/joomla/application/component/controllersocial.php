<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

// @TODO Add ability to set redirect manually to better cope with frontend usage.

/**
 * Controller tailored to suit most form-based admin operations.
 *
 * @package		Joomla.Framework
 * @subpackage	Application
 * @since		1.6
 */
class JControllerSocial extends JController
{
	/**
	 * @var	string	The URL option for the component.
	 * @since	1.6
	 */
	protected $option;

	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		// Guess the option as com_NameOfController
		if (isset($config['option'])) {
			$this->option = $config['option'];
		} else if (empty($this->option)) {
			$this->option = 'com_'.strtolower($this->getName());
		}
	}

	/**
	 * Method to add a social comment to content.
	 *
	 * @return	boolean	True on success, false on error.
	 * @since	1.6
	 */
	public function addComment()
	{
		// Check for a valid token.
		// We must do this by hand as the framework will throw a session expired message.
		// This is important if robots accidentally index a URL an AJAX url to make a comment.
		$token	= JUtility::getToken();
		if (!JRequest::getVar($token, '', 'request', 'alnum')) {
			JError::raiseError(500, JText::_('JInvalid_Token'));
			return false;
		}

		jimport('joomla.social.comments');

		$user	= JFactory::getUser();
		$form	= JComments::getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}
		$control	= $form->getFormControl();

		// Get the raw data as the form provides for its own filtering.
		if ($control) {
			$data = JRequest::getVar($control, array(), 'post', 'array', JREQUEST_ALLOWRAW);
		} else {
			$data = JRequest::get('method', JREQUEST_ALLOWRAW);
		}

		$data = JComments::validate($form, $data);

		JComments::save($data);

		// Get the URL to redirect the request to.
		$redirect = base64_decode($data['redirect']);
die;

		$config	= JComponentHelper::getParams('com_social');
		$date	= JFactory::getDate();
		$uId	=
		$tId	= JRequest::getInt('thread_id');

		// Load the language file for the comment module.
		$lang = JFactory::getLanguage();
		$lang->load('mod_social_comment');

		// Get the thread and comment models.
		$tModel	= $this->getModel('Thread', 'SocialModel');
		$cModel	= $this->getModel('Comment', 'SocialModel');

		// Check if the user is authorized to add a comment.
		if (!$cModel->canComment()) {
			$this->setRedirect($redirect, $cModel->getError(), 'error');
			return false;
		}

		// Load the thread data.
		$thread	= $tModel->getThread($tId);

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
		$comment = $cModel->getItem($return);

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
			$cache = JFactory::getCache('com_'.$thread->context);
			$cache->clean();
		}
		elseif ($item->published == 2) {
			// Comment flagged as SPAM.
			$message = JText::_('SOCIAL_Flagged_As_Spam');
		}

		$this->setRedirect($redirect, $message);
		return true;
	}

	/**
	 * Method to add a rating.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function addRating()
	{
		// Get the URL to redirect the request to.
		$redirect = base64_decode(JRequest::getVar('redirect', '', 'request', 'base64'));

		// Check for a valid token.
		// We must do this by hand as the framework with throw a session expired message.
		$token	= JUtility::getToken();
		if (!JRequest::getVar($token, '', 'request', 'alnum')) {
			$this->setRedirect($redirect, JText::_('JX_Invalid_Token'), 'error');
			return false;
		}

		$app	= JFactory::getApplication();
		$config	= JComponentHelper::getParams('com_social');
		$user	= JFactory::getUser();
		$uId	= (int)$user->get('id');
		$tId	= JRequest::getInt('thread_id');

		// Load the language file for the comment module.
		$lang = JFactory::getLanguage();
		$lang->load('mod_social_rating');

		// Get the thread and rating models.
		$tModel	= $this->getModel('Thread', 'SocialModel');
		$rModel	= $this->getModel('Rating', 'SocialModel');

		// Check if the user is authorized to add a rating.
		if (!$rModel->canRate()) {
			$this->setRedirect($redirect, $cModel->getError(), 'error');
			return false;
		}

		// Load the thread data.
		$thread	= $tModel->getThread($tId);

		// Check the thread data.
		if ($thread === false) {
			$this->setRedirect($redirect, JText::_('SOCIAL_Comment_Invalid_Thread'), 'error');
			return false;
		}

		// Prepare the rating data.
		$rating					= array();
		$rating['thread_id']	= $tId;
		$rating['user_id']		= $uId;
		$rating['score']		= JRequest::getFloat('score', 0, 'request');
		$rating['context']		= $thread->context;
		$rating['context_id']	= $thread->context_id;

		// Ensure the score is between 0 and 1.
		if (($rating['score'] < 0.0) || ($rating['score'] > 1.0)) {
			$this->setRedirect($redirect, JText::_('SOCIAL_Rating_Invalid_Score'), 'error');
			return false;
		}

		// Prepare the key to track the vote in the session.
		$key = 'com_social;rating;'.$tId;

		// Check if the user has already voted on this thread.
		if ($app->getUserState($key)) {
			$this->setRedirect($redirect, JText::_('SOCIAL_Rating_Item_Already_Rated'), 'notice');
			return false;
		}
		else {
			// Record the vote for this thread in the session.
			$app->setUserState($key, true);
		}

		// Attempt to add the rating.
		$return = $rModel->add($rating);

		// Check if the comment was added successfully.
		if (JError::isError($return)) {
			JError::raiseError(500, $return->getMessage());
			return false;
		}

		// Flush the cache for this context.
		$cache = JFactory::getCache('com_'.$thread->context);
		$cache->clean();

		$this->setRedirect($redirect, JText::_('SOCIAL_Rating_Submitted'));
		return true;
	}

	/**
	 * Method to add a trackback to a thread.
	 *
	 * @since	1.6
	 */
	public function addTrackback()
	{
		// Get the current application object.
		$application = JFactory::getApplication();

		// Get a Trackback object.
		jimport('joomla.webservices.trackback');
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
		$tModel	= $this->getModel('Thread', 'SocialModel');
		$cModel	= $this->getModel('Comment', 'SocialModel');

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

	/**
	 * This method is not supported by this class.
	 */
	public function display()
	{
		JError::raiseWarning('JLIB_CONTROLLER_DOES_NOT_SUPPORT_DISPLAY');
	}

}