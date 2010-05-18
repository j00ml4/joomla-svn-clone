<?php
/**
 * @version		$Id: util.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

/**
 * NoixFLAPP Exception object.
 *
 * @package	NoixFLAPP.Framework
 * @subpackage utilities
 * @since	1.0
 */
class Util
{
	/**
	 * Return a formated string
	 * 
	 * @param unknown_type $string
	 * @since 1.0
	 * @return string
	 */
	public static function fromCamelCase($string)
	{
		return trim(preg_replace('/(?<=[a-z])(?=[A-Z])/',' ',$string));
	}
}