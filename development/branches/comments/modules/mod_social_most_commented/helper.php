<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_most_commented
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Add the appropriate include paths for models.
jimport('joomla.application.component.model');
JModel::addIncludePath(JPATH_SITE.'/components/com_social/models');

/**
 * @package		Joomla.Site
 * @subpackage	mod_social_most_commented
 * @since		1.6
 */
class modSocialMostCommentedHelper
{
	/**
	 * @param	JRegistry
	 * @since	1.6
	 */
	public static function getList($params)
	{
		// get the comments rating model
		$model = self::getModel($params);

		// verify its a model

		$list = $model->getItems();
		return $list;
	}

	/**
	 * @param	JRegistry
	 * @since	1.6
	 */
	protected static function getModel($params)
	{
		static $model;

		if (empty($model))
		{
			// get a comments comment model instance and set the context in the state
			jimport('joomla.social.threads');

			$model = new JThreads;
			// prime the list parameters.
			$model->setState('filter.context', $params->get('context'));
			$model->setState('list.ordering', 'comment_count DESC');
			$model->setState('list.limit', $params->get('limit', 5));
		}

		return $model;
	}
}