<?php
/**
* @version		$Id$
* @package		Joomla.Framework
* @subpackage	Parameter
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Renders a spacer element
 *
 * @author 		Johan Janssens <johan.janssens@joomla.org>
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JElementSpacer extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Spacer';

	function fetchTooltip($label, $description, &$node, $control_name, $name) {
		return '&nbsp;';
	}

	function fetchElement($name, $value, &$node, $control_name)
	{
		if ($value) {
			return $value;
		} else {
			return '<hr />';
		}
	}
}