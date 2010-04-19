<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * JHtml Helper class for Social
 *
 * @package		Joomla.Site
 * @version	1.0
 */
class JHtmlShare
{
	/**
	 * Generate a link to a mailto popup for
	 *
	 * @static
	 * @param	string	$title		Title for the link
	 * @param	string	$link		The full link
	 * @param	array	$attribs	Link attributes
	 * @return	string	The mailto link
	 * @since	1.6
	 */
	function email($title, $link, $attribs = array())
	{
		$url	= 'index.php?option=com_mailto&tmpl=component&link='.base64_encode($link);
		$status = 'width=400,height=300,menubar=yes,resizable=yes';

		$attribs['title']	= JText::_('SOCIAL_Email');
		$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
		$attribs['rel']		= 'nofollow';

		$result	= JHtml::_('link', JRoute::_($url), $title, $attribs);
		return $result;
	}
}
