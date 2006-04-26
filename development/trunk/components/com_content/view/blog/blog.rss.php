<?php
/**
 * @version $Id$
 * @package Joomla
 * @subpackage Content
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the Content component
 *
 * @package Joomla
 * @subpackage Content
 * @since 1.5
 */
class JViewRSSBlog extends JView
{
	/**
	 * Name of the view.
	 *
	 * @access	private
	 * @var		string
	 */
	var $_viewName = 'Blog';

	/**
	 * Name of the view.
	 *
	 * @access	private
	 * @var		string
	 */
	function display()
	{
		global $mainframe;
		
		$document =& $mainframe->getDocument();

		// parameters
		$menu 	=& $this->get('Menu');
		$params =& $menu->parameters;
		$Itemid = $menu->id;

		$link       = $mainframe->getBaseURL() .'index.php?option=com_content&task=view&id=';
		$format		= 'RSS2.0';
		$limit		= '10';

		JRequest::setVar('limit', $limit);
		$rows = & $this->get('Content');

		$count = count( $rows );
		for ( $i=0; $i < $count; $i++ )
		{
			$Itemid = $mainframe->getItemid( $rows[$i]->id );
			$rows[$i]->link = $link .$rows[$i]->id .'&Itemid='. $Itemid;
			$rows[$i]->date = $rows[$i]->created;
			$rows[$i]->description = $rows[$i]->introtext;
		}

		$document->createFeed( $rows, $format, $menu->name, $params );

	}
}
?>