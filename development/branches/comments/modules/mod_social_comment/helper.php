<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_comment
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class modSocialCommentHelper
{
	function getThread(&$params)
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

	function &getComments(&$params)
	{
		jimport('joomla.social.comments');

		$model = new JComments;
		$model->setState('filter.thread_id', $params->get('thread.id'));
		$model->setState('filter.state', 1);
		if (strtolower($params->get('list_order')) == 'asc') {
			$model->setState('list.ordering', 'a.created_time ASC');
		} else {
			$model->setState('list.ordering', 'a.created_time DESC');
		}
		$model->setState('list.limit', $params->get('pagination', 10));

		$comments = $model->getItems();

		return $comments;
	}

	function getPagination(&$params)
	{
		jimport('joomla.social.comments');

		$model = new JComments;
		$model->setState('filter.thread_id', $params->get('thread.id'));
		$model->setState('filter.state', 1);
		if (strtolower($params->get('list_order')) == 'asc') {
			$model->setState('list.ordering', 'a.created_time ASC');
		} else {
			$model->setState('list.ordering', 'a.created_time DESC');
		}
		$model->setState('list.limit', $params->get('pagination', 10));

		$pagination = $model->getPagination();

		return $pagination;
	}

	function getForceFormURL($params)
	{
		$pageURI = JURI::getInstance($params->get('route'));
		$pageURI->setVar('scf',1);

		return $pageURI->toString(array('path', 'query'));
	}
}
