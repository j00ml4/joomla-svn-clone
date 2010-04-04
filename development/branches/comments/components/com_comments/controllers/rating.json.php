<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	com_comments
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');

/**
 * The JXtended Comments rating JSON controller
 *
 * @package		JXtended.Comments
 * @subpackage	com_comments
 * @version		1.2
 */
class CommentsControllerRating extends JController
{
	/**
	 * The display method should never be requested from the extended
	 * controller.  Throw an error page and exit gracefully.
	 *
	 * @return	void
	 * @since	1.3
	 */
	public function display()
	{
		JError::raiseError(404, 'Resource Not Found');
	}

	/**
	 * Method to add a rating via JSON.
	 *
	 * @return	void
	 * @since	1.0
	 */
	public function add()
	{
		// Check for a valid token. If invalid, send a 403 with the error message.
		JRequest::checkToken('request') or $this->sendResponse(new JException(JText::_('JX_Invalid_Token'), 403));

		$app	= &JFactory::getApplication();
		$config	= &JComponentHelper::getParams('com_comments');
		$user	= &JFactory::getUser();
		$uId	= (int)$user->get('id');
		$tId	= JRequest::getInt('thread_id');

		// Load the language file for the comment module.
		$lang = &JFactory::getLanguage();
		$lang->load('mod_comments_rating');

		// Get the thread and rating models.
		$tModel	= &$this->getModel('Thread', 'CommentsModel');
		$rModel	= &$this->getModel('Rating', 'CommentsModel');

		// Check if the user is authorized to add a rating.
		if (!$rModel->canRate()) {
			JError::raiseError(403, $rModel->getError());
			return false;
		}

		// Load the thread data.
		$thread	= &$tModel->getThread($tId);

		// Check the thread data.
		if ($thread === false) {
			JError::raiseError(500, JText::_('Comments_Comment_Invalid_Thread'));
			return false;
		}

		// Prepare the rating data.
		$rating					= array();
		$rating['thread_id']	= $tId;
		$rating['user_id']		= $uId;
		$rating['score']		= JRequest::getFloat('score', 0, 'post');
		$rating['category_id']	= JRequest::getInt('category_id', 0);
		$rating['context']		= $thread->context;
		$rating['context_id']	= $thread->context_id;

		// Ensure the score is between 0 and 1.
		if (($rating['score'] < 0.0) || ($rating['score'] > 1.0)) {
			JError::raiseError(500, JText::_('Comments_Rating_Invalid_Score'));
			return false;
		}

		// Prepare the key to track the vote in the session.
		$key = 'com_comments;rating;'.$tId;

		// Check if the user has already voted on this thread.
		if ($app->getUserState($key)) {
			JError::raiseError(403, JText::_('Comments_Rating_Item_Already_Rated'));
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

		// Load the rating from the database.
		$response = &$rModel->getItem($tId);

		// Flush the cache for this context.
		$cache = &JFactory::getCache('com_'.$thread->context);
		$cache->clean();

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
	 * @since	1.2
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
		echo json_encode(new CommentsRatingResponse($body));

		// Close the application.
		JFactory::getApplication()->close();
	}
}

/**
 * Comments Rating JSON Response Class
 *
 * @package		JXtended.Comments
 * @subpackage	com_comments
 * @version		1.2
 */
class CommentsRatingResponse
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
			$this->header	= JText::_('Comments_Rating_Header_Error');
			$this->message	= $state->getMessage();
		}
		else
		{
			// Prepare the response data.
			$this->error		= false;
			$this->pscore_count	= (int) $state->pscore_count;
			$this->pscore		= (float) $state->pscore;
			$this->counter_text	= $this->pscore_count == 1 ? JText::_('Comments_Vote') : JText::_('Comments_Votes');
		}
	}
}

// This needs to be AFTER the class declaration because of PHP 5.1.
JError::setErrorHandling(E_ALL, 'callback', array('CommentsControllerRating', 'sendResponse'));