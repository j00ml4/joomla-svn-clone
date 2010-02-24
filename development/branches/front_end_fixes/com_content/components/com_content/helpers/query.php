<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Content Component Query Helper
 *
 * @static
 * @package		Joomla.Site
 * @subpackage	com_content
 * @since 1.5
 */
class ContentHelperQuery
{
	function orderbyPrimary($orderby)
	{
		switch ($orderby)
		{
			case 'alpha' :
				$orderby = 'c.title, ';
				break;

			case 'ralpha' :
				$orderby = 'c.title DESC, ';
				break;

			case 'order' :
				$orderby = 'c.ordering, ';
				break;

			default :
				$orderby = '';
				break;
		}

		return $orderby;
	}

	function orderbySecondary($orderby, $orderDate = 'created')
	{
		switch ($orderDate)
		{
			case 'modifed' :
				$queryDate = ' a.modified ';
				break;

			// use created if publish_up is not set
			case 'published' :
				$queryDate = ' CASE WHEN a.publish_up = 0 THEN a.created ELSE a.publish_up END ';
				break;

			case 'created' :
			default :
				$queryDate = ' a.created ';
				break;
		}

		switch ($orderby)
		{
			case 'date' :
				$orderby = $queryDate;
				break;

			case 'rdate' :
				$orderby = $queryDate . ' DESC ';
				break;

			case 'alpha' :
				$orderby = 'a.title';
				break;

			case 'ralpha' :
				$orderby = 'a.title DESC';
				break;

			case 'hits' :
				$orderby = 'a.hits DESC';
				break;

			case 'rhits' :
				$orderby = 'a.hits';
				break;

			case 'order' :
				$orderby = 'a.ordering';
				break;

			case 'author' :
				$orderby = 'author_name';
				break;

			case 'rauthor' :
				$orderby = 'author_name DESC';
				break;

			case 'front' :
				$orderby = 'f.ordering';
				break;

			default :
				$orderby = 'a.ordering';
				break;
		}

		return $orderby;
	}

	function buildVotingQuery($params=null)
	{
		if (!$params) {
			$params = &JComponentHelper::getParams('com_content');
		}
		$voting = $params->get('show_vote');

		if ($voting) {
			// calculate voting count
			$select = ' , ROUND(v.rating_sum / v.rating_count) AS rating, v.rating_count';
			$join = ' LEFT JOIN #__content_rating AS v ON a.id = v.content_id';
		} else {
			$select = '';
			$join = '';
		}

		$results = array ('select' => $select, 'join' => $join);

		return $results;
	}

	/**
	 * Method to order the intro articles array for ordering
	 * down the columns instead of across. 
	 * The layout always lays the introtext articles out across columns. 
	 * Array is reordered so that, when articles are displayed in index order
	 * across columns in the layout, the result is that the
	 * desired article ordering is achieved down the columns.
	 * 
	 * @access	public
	 * @param	array	$articles	Array of intro text articles
	 * @param	integer	$numColumns	Number of columns in the layout
	 * @return	array	Reordered array to achieve desired ordering down columns
	 * @since	1.6
	 */
	function orderDownColumns(&$articles, $numColumns = 1)
	{
		$count = count($articles);
		// just return the same array if there is nothing to change
		if ($numColumns == 1 || !is_array($articles) || $count  <= $numColumns) 
		{
			$return = $articles;
		}
		// we need to re-order the intro articles array
		else 
		{
			// layout the articles in column order
			$maxRows = ceil($count / $numColumns);
			$index = array();
			$i = 1;
			for ($col = 1; ($col <= $numColumns) && ($i <= $count); $col++) {
				for ($row = 1; ($row <= $maxRows) && ($i <= $count); $row++) {
					$index[$row][$col] = $i;
					$i++;
				}
			}
			// now read the $index back row by row to get articles in right row/col
			// so that they will actually be ordered down the columns
			$return = array();
			$i = 1;
			for ($row = 1; ($row <= $maxRows) && ($i <= $count); $row++) {
				for ($col = 1; ($col <= $numColumns) && ($i <= $count); $col++) {
					$return[$i] = &$articles[$index[$row][$col]];
					$i++;
				}
			}
		}
		return $return;
	}
}
