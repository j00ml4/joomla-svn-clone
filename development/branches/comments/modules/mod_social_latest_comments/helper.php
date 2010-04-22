<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_latest
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Add the appropriate include paths for models.
jimport('joomla.application.component.model');
JModel::addIncludePath(JPATH_SITE.'/components/com_social/models');

/**
 * @package		Joomla.Site
 * @subpackage	mod_social_latest
 * @since		1.6
 */
class modSocialLatestHelper
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

		$list = &$model->getItems();
		return $list;
	}

	/**
	 * @param	JRegistry
	 * @since	1.6
	 */
	protected static function &getModel($params)
	{
		static $model;

		if (empty($model))
		{
			// get a comments comments model instance
			jimport('joomla.social.comments');

			$model = new Comments;
			// prime the list parameters.
			$model->setState('filter.context', $params->get('context'));
			$model->setState('list.ordering', 'a.created_time DESC');
			$model->setState('list.limit', $params->get('limit', 5));
			$model->setState('check.trackback', $params->get('hide_trackbacks', 1));
		}

		return $model;
	}

	/**
	 * @param	string	The source text.
	 * @param	int		The length by which to truncate the text.
	 * @since	1.6
	 */
	public static function truncateBody($text, $length=50)
	{
		// TODO Shouldn't be use JString::truncate?
		$text = strip_tags($text);
		return JXHTMLString::truncate($text, $length);
	}
}
