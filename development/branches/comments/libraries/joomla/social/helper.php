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
	public static function getContentId($context, $title, $route)
	{
		// Get the database connection object.
		$db = JFactory::getDBO();

		// Get a new query object.
		$query = $db->getQuery(true);

		// Setup the base query parameters to select comment by id.
		$query->select('a.id, a.page_title, a.page_route');
		$query->from('#__social_content AS a');
		$query->where('a.context = '.$db->quote($context));

		//echo nl2br(str_replace('#__','jos_',$query)).'<hr/>';

		// Execute the query and load the object from the database.
		$db->setQuery($query);
		$content = $db->loadObject();

	}
}