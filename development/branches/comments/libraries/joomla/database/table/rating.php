<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Table
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Rating table object for Social
 *
 * @package		Joomla.Framework
 * @subpackage	Table
 * @since		1.6
 */
class SocialTableRating extends JTable
{
	/**
	 * Constructor
	 *
	 * @param	object	Database object
	 * @return	void
	 * @since	1.6
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__social_ratings', 'thread_id', $db);
	}

	/**
	 * Method to check the current record to save
	 *
	 * @return	boolean	True on success
	 * @since	1.6
	 */
	public function check()
	{
		// Get the Social configuration object.
		$config = JComponentHelper::getParams('com_social');

		// Validate the rating data.
		$result	= false;
		if (empty($this->thread_id)) {
			$this->setError('SOCIAL_Rating_Thread_Empty');
		} else {
			$result = true;
		}

		return $result;
	}

	/**
	 * Method to store the current record
	 *
	 * @return	boolean	True on success
	 * @since	1.6
	 */
	public function store()
	{
		// Get the database connection object.
		$db = $this->_db;

		// Create the query string for formatting.
		$query = 'REPLACE INTO '.$db->NameQuote($this->_tbl) .
				' ( %s ) VALUES ( %s )';

		// Populate the fields and values arrays.
		$fields = array();
		foreach (get_object_vars($this) as $k => $v)
		{
			// Skip over invalid values.
			if (is_array($v) or is_object($v) or $v === NULL) {
				continue;
			}

			// Skip over internal/private values.
			if ($k[0] == '_') {
				continue;
			}

			// Quote and add the fields.
			$fields[] = $db->nameQuote($k);
			$values[] = $db->isQuoted($k) ? $db->Quote($v) : (int) $v;
		}

		// Set and execute the store query.
		$db->setQuery(sprintf($query, implode(',', $fields), implode(',', $values)));
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		return true;
	}
}