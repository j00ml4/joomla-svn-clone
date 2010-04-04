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
 * Comment AJAX controller class for Comments.
 *
 * @package		JXtended.Comments
 * @subpackage	com_comments
 * @version		1.2
 */
class CommentsControllerComment extends JController
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
	 * Method to get the Comments for a content item.
	 *
	 * @return	void
	 * @since	1.2
	 */
	public function getComments()
	{
		// Get the thread information.
		$model	= $this->getModel('Thread', 'CommentsModel');
		$tId	= JRequest::getInt('thread_id');
		$thread	= $model->getThread($tId);

		// Extract the thread information.
		$context	= $thread->context;
		$contextId	= (int) $thread->context_id;
		$pageUrl	= $thread->page_url;
		$pageRoute	= $thread->page_route;
		$pageTitle	= $thread->page_title;
		$options	= array('style'=>'raw');

		// Add the Comments component JHtml helpers.
		JHtml::addIncludePath(JPATH_SITE.'/components/com_comments/helpers/html');

		// Render the comments.
		echo JHtml::_('comments.comments', $context, $contextId, $pageUrl, $pageRoute, $pageTitle, $options);

		// Close the application.
		JFactory::getApplication()->close();
	}

	/**
	 * Method to get the Comment form.
	 *
	 * @return	void
	 * @since	1.2
	 */
	public function getForm()
	{
		// Get the thread information.
		$model	= $this->getModel('Thread', 'CommentsModel');
		$tId	= JRequest::getInt('thread_id');
		$thread	= $model->getThread($tId);

		// Extract the thread information.
		$context	= $thread->context;
		$contextId	= (int) $thread->context_id;
		$pageUrl	= $thread->page_url;
		$pageRoute	= $thread->page_route;
		$pageTitle	= $thread->page_title;
		$options	= array('style'=>'raw');

		// Add the Comments component JHtml helpers.
		JHtml::addIncludePath(JPATH_SITE.'/components/com_comments/helpers/html');

		// Render the form.
		echo JHtml::_('comments.form', $context, $contextId, $pageUrl, $pageRoute, $pageTitle, $options);

		// Close the application.
		JFactory::getApplication()->close();
	}
}