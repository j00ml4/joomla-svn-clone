<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	com_social
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');

/**
 * JHtml Helper class for JXtended Social
 *
 * @package		JXtended.Comments
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
