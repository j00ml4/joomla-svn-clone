<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	mod_social_summary
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper class for the Comments Summary Module.
 *
 * @static
 * @package		Joomla.Administrator
 * @subpackage	mod_social_summary
 * @version		1.2
 */
class modSocialSummaryHelper
{
	function getList($params)
	{
		// Get the list data.
		$model	= &modSocialSummaryHelper::getModel($params);
		$list	= &$model->getItems();
		return $list;
	}

	function truncateBody($text)
	{
		$text = strip_tags($text);
		return JXHTMLString::truncate($text, 50);
	}

	function &getModel($params)
	{
		static $model;

		if (empty($model))
		{
			// get a comments comments model instance
			jimport('joomla.application.component.model');
			JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_social'.DS.'models');
			$model = &JModel::getInstance('Comments', 'CommentsModel');
			$model->getState();

			// prime the list parameters.
			$model->setState('list.ordering', 'a.created_date');
			$model->setState('list.direction', 'desc');
			$model->setState('list.limit', $params->get('limit', 5));
			$model->setState('filter.state', '*');
			$model->setState('check.state', false);
		}

		return $model;
	}
}
