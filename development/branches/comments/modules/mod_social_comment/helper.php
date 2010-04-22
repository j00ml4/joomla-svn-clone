<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_comment
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package		Joomla.Site
 * @subpackage	mod_social_comment
 * @since		1.6
 */
class modSocialCommentHelper
{
	/**
	 * @param	JRegistry
	 * @since	1.6
	 */
	public static function getComments($params)
	{
		// Add the appropriate include paths for models.
		jimport('joomla.social2.comments');

		// Get and configure the thread model.
		$model = new JComments;

		// Get the thread data.
		$comments = $model->getCommentsByContext($params->get('context'), 1, (strtolower($params->get('list_order')) == 'asc'));

		return $comments;
	}

	/**
	 * @param	JRegistry
	 * @since	1.6
	 */
	public static function getForceFormURL($params)
	{
		$pageURI = JURI::getInstance($params->get('route'));
		$pageURI->setVar('scf',1);

		return $pageURI->toString(array('path', 'query'));
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	JRegistry	The module parameters.
	 * @return	mixed		JForm object on success, false on failure.
	 * @since	1.6
	 */
	public static function getForm($params)
	{
		jimport('joomla.form.form');

		// Add the local form path.
		JForm::addFormPath(dirname(__FILE__).'/forms');
		//JForm::addFieldPath(dirname(__FILE__).'/fields');

		// Get the form.
		try {
			$form = JForm::getInstance('mod_social_comment.comment', 'comment', array('control' => 'jform'), false);

			// Allow for additional modification of the form, and events to be triggered.

			// Get the dispatcher.
			$dispatcher	= JDispatcher::getInstance();

			// Trigger the form preparation event.
			$results = $dispatcher->trigger('onPrepareForm', array($form->getName(), $form));

			// Check for errors encountered while preparing the form.
			if (count($results) && in_array(false, $results, true)) {
				// Get the last error.
				$error = $dispatcher->getError();

				// Convert to a JException if necessary.
				if (!JError::isError($error)) {
					throw new Exception($error);
				}
			}

			$uri = JFactory::getUri();
			$data = array(
				'context'	=> $params->get('context'),
				'redirect'	=> base64_encode($uri->toString(array('path', 'query', 'fragment'))),
				'subject'	=> $params->get('title')
			);
			$form->bind($data);

			return $form;

		} catch (Exception $e) {
			JError::raiseWarning(500, $e->getMessage());
			return false;
		}
	}

	/**
	 * @param	JRegistry
	 * @since	1.6
	 */
	public static function getPagination($params)
	{
		jimport('joomla.social2.comments');

		// Get and configure the thread model.
		$model = new JComments;

		// Get the thread data.
		$total = (int) $model->getTotalByContext($params->get('context'), 1);

		jimport('joomla.html.pagination');

		$pagination = new JPagination($total, JRequest::getInt('limitstart'), $params->get('pagination', 10));

		return $pagination;
	}

	/**
	 * @param	JRegistry
	 * @since	1.6
	 */
	public static function getThread($params)
	{
		jimport('joomla.social.thread');

		// Get and configure the thread model.
		$model = new JThread;
		$model->setState('thread.context',	$params->get('context'));
		$model->setState('thread.route',	$params->get('route'));
		$model->setState('thread.title',	$params->get('title'));

		// Get the thread data.
		$thread = $model->getThread();

		if ($thread) {
			$params->set('thread.id', (int)$thread->id);
		}

		return $thread;
	}
}