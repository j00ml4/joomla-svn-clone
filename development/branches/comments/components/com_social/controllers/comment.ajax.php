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
 * Comment AJAX controller class for Social.
 *
 * @package		Joomla.Site
 * @subpackage	com_social
 * @version		1.2
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
	 * Method to get the Social for a content item.
	 *
	 * @return	void
	 * @since	1.6
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

		// Add the Social component JHtml helpers.
		JHtml::addIncludePath(JPATH_SITE.'/components/com_social/helpers/html');

		// Render the comments.
		echo JHtml::_('comments.comments', $context, $contextId, $pageUrl, $pageRoute, $pageTitle, $options);

		// Close the application.
		JFactory::getApplication()->close();
	}

	/**
	 * Method to get the Comment form.
	 *
	 * @return	void
	 * @since	1.6
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

		// Add the Social component JHtml helpers.
		JHtml::addIncludePath(JPATH_SITE.'/components/com_social/helpers/html');

		// Render the form.
		echo JHtml::_('comments.form', $context, $contextId, $pageUrl, $pageRoute, $pageTitle, $options);

		// Close the application.
		JFactory::getApplication()->close();
	}
}