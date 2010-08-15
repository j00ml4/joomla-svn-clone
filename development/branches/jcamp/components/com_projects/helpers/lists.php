<?php 
/**
 * @version		$Id: media.php 15757 2010-04-01 11:06:27Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * @package		Joomla.Site
 * @subpackage	com_projects
 */
abstract class ListsHelper
{
	/**
	 * Method to list portfolios IDs
	 * 
	 * @param $params Parameters for this function
	 * 
	 * @return AN array consisting of a list of portfolios IDs
	 * @since	1.6
	 */
	public function getPortfoliosID($params)
	{
		$id = $params->get('portfolio.id',null); // get an ID to start the list from
		$db = JFactory::getDbo();
		$q = $db->getQuery(true);
		if(empty($id)) { // list of all portfolios IDs
			$q->select('c.id');
			$q->from('#__categories c');
			$q->where('c.extension = \'com.projects\'');
			$db->setQuery($q);
			$result = $db->loadResultArray(0);
			return $result[0]; // list of all portfolios IDs
		}
		else { // list all portfolios IDs which are nested from a parent ID
			$tbl = JFactory::getTable('Projects','');
		}
	}
}
?>