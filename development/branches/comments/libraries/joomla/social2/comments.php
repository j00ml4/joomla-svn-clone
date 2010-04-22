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
 * Comments Class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Social
 * @since		1.6
 */
class JComments
{
	/**
	 * Method to get a list of comment objects by context.
	 *
	 * @param   string  $context    The content item context for which to get comment data.
	 * @param   int     $state      The publishing state to filter comment data over.
	 * @param   boolean $old2new    True to order the result list from oldest to newest, false for
	 *                              returning the results from newest to oldest.
	 * @param   int     $limit      The maximum number of comment objects to get.
	 * @param   int     $offset     The offset for which start building the list of comment objects.
	 *
	 * @return  array   A list of coment objects.
	 *
	 * @since   1.6
	 */
	public function getCommentsByContext($context, $state = 1, $old2new = true, $limit = 0, $offset = 0)
	{
		// Get the database connection object.
		$db = JFactory::getDBO();

		// Get a new query object.
		$query = $db->getQuery(true);

		// Setup the base query parameters to select comments by context.
		$query->select('a.*');
		$query->from('#__social_comments AS a');
		$query->where('a.context = '.$db->quote($context));

		// Get content item information.
		$query->select('b.page_route, b.page_title');
		$query->join('LEFT', '#__social_content AS b ON b.id = a.content_id');

		// Get registered user name information if it exists.
		$query->select('c.name AS user_full_name, c.username AS user_login_name');
		$query->join('LEFT', '#__users AS c ON c.id = a.user_id');

		// Set the ordering clause.
		$query->order('a.created_date '.$old2new ? 'ASC' : 'DESC');

		// If set to filter on state add the filter clause to the query.
		if ($state !== null) {
			$query->where('a.state = '.(int) $state);
		}

		//echo nl2br(str_replace('#__','jos_',$query)).'<hr/>';

		// Execute the query and load the list of objects from the database.
		$db->setQuery($query, $limit, $offset);
		$list = $db->loadObjectList();

		return $list;
	}

	/**
	 * Method to get the total number of comments by context.
	 *
	 * @param   string  $context    The content item context for which to get the number of comments.
	 * @param   int     $state      The publishing state to filter comment data over.
	 *
	 * @return  int     The total number of comments for the content item.
	 *
	 * @since   1.6
	 */
	public function getTotalByContext($context, $state = 1)
	{
		// Get the database connection object.
		$db = JFactory::getDBO();

		// Get a new query object.
		$query = $db->getQuery(true);

		// Setup the base query parameters to select comments by context.
		$query->select('COUNT(a.*)');
		$query->from('#__social_comments AS a');
		$query->where('a.context = '.$db->quote($context));

		// If set to filter on state add the filter clause to the query.
		if ($state !== null) {
			$query->where('a.state = '.(int) $state);
		}

		//echo nl2br(str_replace('#__','jos_',$query)).'<hr/>';

		// Execute the query and load the list of objects from the database.
		$db->setQuery($query);
		$total = (int) $db->loadResult();

		return $total;
	}

	public function save()
	{
		// Filter/Validate input data.

		// Check to see if we are inserting a new row.

		// If new, fire onContentSubmit event for external validation/verification/modification.

		// Attempt to save comment -- updating the social_content table as well.

		// Send out moderation queue email as necessary.

		// Send out notification emails as necessary.
	}

	public function delete()
	{
		// Attempt to delete comment -- updating the social_content table as well.
	}

	public function setState()
	{
		// Check to see if we are performing moderation (coming from defer state).

		// If moderating, fire onContentModerate event for external notification/verification.

		// Attempt to save comment state -- updating the social_content table as well.

		// Send out notification emails as necessary.
	}
}
