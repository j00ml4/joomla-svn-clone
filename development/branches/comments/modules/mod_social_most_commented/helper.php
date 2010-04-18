<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_most_commented
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');

// Add the appropriate include paths for models.
jimport('joomla.application.component.model');
JModel::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_social'.DS.'models');

class modSocialMostCommentedHelper
{
	function getList($params)
	{
		// get the comments rating model
		$model = &modSocialMostCommentedHelper::getModel($params);

		// verify its a model

		$list = &$model->getItems();
		return $list;
	}

	function &getModel($params)
	{
		static $model;

		if (empty($model))
		{
			// get a comments comment model instance and set the context in the state
			$model = &JModel::getInstance('Threads', 'CommentsModel');
			$model->getState();

			// prime the list parameters.
			$model->setState('filter.context', $params->get('context'));
			$model->setState('list.ordering', 'comment_count DESC');
			$model->setState('list.limit', $params->get('limit', 5));
		}

		return $model;
	}
}
