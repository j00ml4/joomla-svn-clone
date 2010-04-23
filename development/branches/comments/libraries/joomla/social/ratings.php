<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Ratings Class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Social
 * @since		1.6
 */
class JRatings
{
	/**
	 * Method to get the cumulative rating data for a content item by context.
	 *
	 * @param   string  $context    The content item context for which to get the rating data.
	 *
	 * @return  object  Object representation of the ratings values for the content item by context.
	 *
	 * @since   1.6
	 */
	public function getRatingsByContext($context)
	{
		// Get the database connection object.
		$db = JFactory::getDBO();

		// Get a new query object.
		$query = $db->getQuery(true);

		// Setup the base query parameters to select comments by context.
		$query->select('a.rating_score AS score, a.rating_total AS total, a.rating_count AS count');
		$query->from('#__social_content AS a');
		$query->where('a.context = '.$db->quote($context));

		//echo nl2br(str_replace('#__','jos_',$query)).'<hr/>';

		// Execute the query and load the list of objects from the database.
		$db->setQuery($query);
		$rating = $db->loadObject();

		// If no rating yet exists for the content item create an object with defaults.
		if (!$rating) {
			$rating = (object) array('score' => 0.0, 'total' => 0.0, 'count' => 0);
		}

		return $rating;

	}

	/**
	 * Method to save a rating against content.
	 *
	 * @param	int		The ID of the user making the rating.
	 * @param	string	The context of the content being rated.
	 * @param	double	The rating score, a double between 0 and 1.
	 */
	public function save($userId, $context, $score)
	{
		// Get the database connection object.
		$db = JFactory::getDBO();

		// Build a query to get data from the content item row.
		$query = $db->getQuery(true);
		$query->select('a.id, a.component');
		$query->from('#__social_content AS a');
		$query->where('a.context = '.$db->quote($context));

		// Execute the query and load the object from the database.
		$db->setQuery($query);
		$content = $db->loadObject();

		// Make sure a content item exists to rate.
		if (!$content) {
			// Throw error
			return false;
		}

		$userIp = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

		// Build a query to determine if the user has already rated the item.
		$query = $db->getQuery(true);
		$query->select('a.id');
		$query->from('#__social_ratings AS a');
		$query->where('a.context = '.$db->quote($context));
		$query->where('(a.user_id = '.(int) $userId.' OR a.user_ip = '.(int) ip2long($userIp).')');

		// Execute the query and load the object from the database.
		$db->setQuery($query);
		$ratingId = (int) $db->loadResult();

		// If a rating already exists, edit the existing rating.
		if ($ratingId) {

			$query = $db->getQuery(true);
			$query->update('#__social_ratings');
			$query->set('context = '.$db->quote($context));
			$query->set('modified_date = '.$db->quote(JFactory::getDate()->toMySQL(), false));
			$query->set('score = '.(float) $score);
			$query->set('user_id = '.(int) $userId);
			$query->set('user_ip = '.(int) ip2long($userIp));

			$db->setQuery($query);
			$db->query();

		}
		// Insert the new rating.
		else {

			$query = $db->getQuery(true);
			$query->insert('#__social_ratings');
			$query->set('context = '.$db->quote($context));
			$query->set('component = '.$db->quote($content->component));
			$query->set('content_id = '.(int) $content->id);
			$query->set('created_date = '.$db->quote(JFactory::getDate()->toMySQL(), false));
			$query->set('modified_date = '.$db->quote(JFactory::getDate()->toMySQL(), false));
			$query->set('state = 1');
			$query->set('score = '.(float) $score);
			$query->set('user_id = '.(int) $userId);
			$query->set('user_ip = '.(int) ip2long($userIp));

			$db->setQuery($query);
			$db->query();

			$ratingId = (int) $db->insertid();
		}

		// Build a query to get the cumulative data for the content item ratings.
		$query = $db->getQuery(true);
		$query->select('SUM(a.score) AS total, COUNT(a.id) AS count');
		$query->from('#__social_ratings AS a');
		$query->where('a.context = '.$db->quote($context));

		// Execute the query and load the object from the database.
		$db->setQuery($query);
		$cumulative = $db->loadObject();

		// Update the cumulative rating data in the content row.
		$query = $db->getQuery(true);
		$query->update('#__social_content');
		$query->set('rating_score = '.(float) ($cumulative->total / $cumulative->count));
		$query->set('rating_total = '.(int) $cumulative->total);
		$query->set('rating_count = '.(int) $cumulative->count);
		$query->where('context = '.$db->quote($context));

		$db->setQuery($query);
		$db->query();

		return true;
	}

	public function delete()
	{
		// Attempt to delete comment -- updating the social_content table as well.
	}

	public function setState()
	{
		// Attempt to save comment state -- updating the social_content table as well.
	}
}
