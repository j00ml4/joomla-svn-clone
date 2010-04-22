<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die();

/**
 * Social helper class
 *
 * @static
 * @package		Joomla.Framework
 * @subpackage	Social
 * @since		1.6
 */
class JSocialHelper
{
	protected static $contentIds = array();

	public static function getContentId($context, $route, $title)
	{
		if (!empty(self::$contentIds[$context])) {
			return self::$contentIds[$context];
		}

		// Get the database connection object.
		$db = JFactory::getDBO();

		// Get a new query object.
		$query = $db->getQuery(true);

		// Setup the base query parameters to select comment by id.
		$query->select('a.id, a.page_title, a.page_route');
		$query->from('#__social_content AS a');
		$query->where('a.context = '.$db->quote($context));


		// Execute the query and load the object from the database.
		$db->setQuery($query);
		$content = $db->loadObject();

		if (!$content) {
			// No record exists, insert one.

			// Get the extension from the context string.
			$parts		= explode('.', $context);
			$extension	= $parts[0];

			$query = $db->getQuery(true);
			$query->insert('#__social_content');
			$query->set('context = '.$db->quote($context));
			$query->set('component = '.$db->quote($extension));
			$query->set('page_title = '.$db->quote($title));
			$query->set('page_route = '.$db->quote($route));
			$query->set('created_date = '.$db->quote(JFactory::getDate()->toMySQL(), false));
			$query->set('state = 1');

			$db->setQuery($query);
			$db->query();

			$content = (object) array('id' => $db->insertid());
		}
		else {
			// Check to make sure the route and title match.
			if (($content->page_route != $route) || ($content->page_title != $title)) {

				$query = $db->getQuery(true);
				$query->update('#__social_content');
				$query->set('page_title = '.$db->quote($title));
				$query->set('page_route = '.$db->quote($route));
				$query->where('context = '.$db->quote($context));

				$db->setQuery($query);
				$db->query();
			}
		}

		self::$contentIds[$context] = $content->id;

		return $content->id;
	}
}