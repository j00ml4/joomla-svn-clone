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
 * Helper class for JXtended Comments
 *
 * @package		JXtended.Comments
 * @version	1.0
 */
class CommentHelper
{
	/**
	 * Method to check to see if the logged in user is blocked by username
	 *
	 * @access	public
	 * @param	object	$config	A JXtended Comments configuration object
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function isBlockedUser(&$config)
	{
		$user	= &JFactory::getUser();
		$userId	= $user->get('id');

		// is the user logged in?
		if ($userId) {
			$name	= $user->get('username');
			$items	= explode(',', $config->get('blockusers'));
			foreach ($items as $item)
			{
				if (trim($item) == $name) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Method to check to see if the IP is blocked
	 *
	 * @access	public
	 * @param	object	$config	A JXtended Comments configuration object
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function isBlockedIP(&$config)
	{
		$address	= $_SERVER['REMOTE_ADDR'];
		$items		= explode(',', $config->get('blockips'));
		foreach ($items as $item)
		{
			if (trim($item) == $address) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Method to check to see if the host is blocked
	 *
	 * @access	public
	 * @param	object	$config	A JXtended Comments configuration object
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	function isBlockedHost(&$config)
	{
		$name	= gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$items	= explode(',', $config->get( 'blockhosts'));
		foreach ($items as $item)
		{
			if (trim($item) == $name) {
				return true;
			}
		}

		return false;
	}
}