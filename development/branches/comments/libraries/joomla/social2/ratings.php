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

	public function save()
	{
		// Filter/Validate input data.

		// Attempt to save rating -- updating the social_content table as well.
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
