<?php
/**
 * @version		$Id$
 * @package		Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU General Public License
 */

/**
 * Simple general mock of JText
 */
class JText
{
	/**
	 * Translates a string into the current language
	 *
	 * @access  public
	 * @param   string $string The string to translate
	 * @param   boolean $jsSafe     Make the result javascript safe
	 * @since   1.5
	 *
	 */
	function _($string, $jsSafe = false)
	{
		return $string;
	}

	/**
	 * Passes a string thru an sprintf
	 *
	 * @access  public
	 * @param   format The format string
	 * @param   mixed Mixed number of arguments for the sprintf function
	 * @since   1.5
	 */
	function sprintf($string)
	{
		$args = func_get_args();
		if (count($args) > 0) {
			return call_user_func_array('sprintf', $args);
		}
		return '';
	}

	/**
	 * Passes a string thru an printf
	 *
	 * @access  public
	 * @param   format The format string
	 * @param   mixed Mixed number of arguments for the sprintf function
	 * @since   1.5
	 */
	function printf($string)
	{
		$args = func_get_args();
		if (count($args) > 0) {
			return call_user_func_array('printf', $args);
		}
		return '';
	}

}
