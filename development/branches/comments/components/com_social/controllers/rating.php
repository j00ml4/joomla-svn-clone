<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');

/**
 * The Social rating controller
 *
 * @package		Joomla.Site
 * @subpackage	com_social
 * @version		1.2
 */
class SocialControllerRating extends SocialController
{
	/**
	 * Method to add a rating.
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

		$app	= &JFactory::getApplication();
		$config	= &JComponentHelper::getParams('com_social');
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

		// Prepare the rating data.
		$rating					= array();
		$rating['thread_id']	= $tId;
		$rating['user_id']		= $uId;
		$rating['score']		= JRequest::getFloat('score', 0, 'request');
		$rating['category_id']	= JRequest::getInt('category_id', 0, 'request');
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
		$cache = &JFactory::getCache('com_'.$thread->context);
		$cache->clean();

		$this->setRedirect($redirect, JText::_('SOCIAL_Rating_Submitted'));
		return true;
	}
}