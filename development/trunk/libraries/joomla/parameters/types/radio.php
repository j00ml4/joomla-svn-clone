<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * Renders a radio parameter
 *
 * @author 		Johan Janssens <johan@joomla.be>
 * @package 	Joomla.Framework
 * @subpackage 	Parameters
 * @abstract
 * @since 1.1
 */

class JParameter_Radio extends JParameter
{
   /**
	* parameter type
	*
	* @access	protected
	* @var		string
	*/
	var	$_type = 'Radio';
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		$options = array ();
		foreach ($node->childNodes as $option) {
			$val  = $option->getAttribute('value');
			$text = $option->gettext();
			$options[] = mosHTML::makeOption($val, JText::_($text));
		}

		return mosHTML::radioList($options, ''.$control_name.'['.$name.']', '', $value);
	}
}
?>