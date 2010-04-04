<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	com_comments
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');

/**
 * Rating table object for JXtended Comments
 *
 * @package		JXtended.Comments
 * @subpackage	com_comments
 * @version		1.0
 */
class CommentsTableRating extends JTable
{
	/** @var int */
	var $thread_id = null;
	/** @var varchar */
	var $context = null;
	/** @var int */
	var $context_id = null;
	/** @var double */
	var $pscore_total = null;
	/** @var int */
	var $pscore_count = null;
	/** @var double */
	var $pscore = null;
	/** @var double */
	var $mscore_total = null;
	/** @var int */
	var $mscore_count = null;
	/** @var double */
	var $mscore = null;
	/** @var longtext */
	var $used_ips = null;
	/** @var datetime */
	var $updated_date = null;

	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	object	Database object
	 * @return	void
	 * @since	1.0
	 */
	function __construct(&$db)
	{
		parent::__construct('#__social_ratings', 'thread_id', $db);
	}

	/**
	 * Method to check the current record to save
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function check()
	{
		// Get the JXtended Comments configuration object.
		$config = &JComponentHelper::getParams('com_comments');

		// Validate the rating data.
		$result	= false;
		if (empty($this->thread_id)) {
			$this->setError('Comments_Rating_Thread_Empty');
		} else {
			$result = true;
		}

		return $result;
	}

	/**
	 * Method to store the current record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function store()
	{
		// Get the database connection object.
		$db = &$this->_db;

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