<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_rating
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');

// Add the appropriate include paths for models.
jimport('joomla.application.component.model');
JModel::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_social'.DS.'models');

class modSocialRatingHelper
{
	function getThread(&$params)
	{
		// Get and configure the thread model.
		$model = &JModel::getInstance('Thread', 'CommentsModel');
		$model->getState();
		$model->setState('thread.context',		$params->get('context'));
		$model->setState('thread.context_id',	(int) $params->get('context_id'));
		$model->setState('thread.url',			$params->get('url'));
		$model->setState('thread.route',		$params->get('route'));
		$model->setState('thread.title',		$params->get('title'));

		// Get the thread data.
		$thread = &$model->getThread();

		if ($thread) {
			$params->set('thread.id', (int)$thread->id);
		}

		return $thread;
	}

	function getRating($params)
	{
		$model = &JModel::getInstance('Rating', 'CommentsModel');
		$model->getState();
		$model->setState('thread.id', $params->get('thread.id'));

		$rating = &$model->getItem();

		return $rating;
	}

	function isBlocked($params)
	{
		// import library dependencies
		require_once(JPATH_SITE.DS.'components'.DS.'com_social'.DS.'helpers'.DS.'blocked.php');

		// run some tests to see if the comment submission should be blocked
		$blocked = (CommentHelper::isBlockedUser($params) or CommentHelper::isBlockedIP($params));

		return $blocked;
	}
}
