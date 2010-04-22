<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_rating
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package		Joomla.Site
 * @subpackage	mod_social_rating
 * @since		1.6
 */
class modSocialRatingHelper
{
	/**
	 * @param	JRegistry
	 * @since	1.6
	 */
	public static function getRating($params)
	{
		jimport('joomla.social2.ratings');

		// Get and configure the thread model.
		$model = new JRatings;

		// Get the thread data.
		$rating = $model->getRatingsByContext($params->get('context'));

		return $rating;
	}

	/**
	 * @param	JRegistry
	 * @since	1.6
	 */
	public static function isBlocked($params)
	{
		// import library dependencies
		require_once JPATH_SITE.'/components/com_social/helpers/blocked.php';

		// run some tests to see if the comment submission should be blocked
		$blocked = (CommentHelper::isBlockedUser($params) or CommentHelper::isBlockedIP($params));

		return $blocked;
	}
}
