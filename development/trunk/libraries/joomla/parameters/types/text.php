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
 * Renders a text parameter
 *
 * @author 		Johan Janssens <johan@joomla.be>
 * @package 	Joomla.Framework
 * @subpackage 	Parameters
 * @abstract
 * @since 1.1
 */

class JParameter_Text extends JParameter
{
   /**
	* parameter type
	*
	* @access	protected
	* @var		string
	*/
	var	$_type = 'Text';
	
	function fetchElement($name, $value, &$node, $control_name) 
	{
		$size = $node->getAttribute('size');
		return '<input type="text" name="'.$control_name.'['.$name.']" value="'.$value.'" class="text_area" size="'.$size.'"/>';
	}
}
?>