<?php
/**
 * @package     gantry
 * @subpackage  admin.elements
 * @version		${project.version} ${build_date}
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - ${copyright_year} RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();
/**
 * @package     gantry
 * @subpackage  admin.elements
 */
class JElementText extends JElement
{
	var	$_name = 'Text';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$size = ( $node->attributes('size') ? 'size="'.$node->attributes('size').'"' : '' );
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="text_area"' );
        $value = htmlspecialchars_decode($value, ENT_QUOTES);
		
		return '
		<div class="wrapper">
			<input type="text" name="'.$control_name.'['.$name.']" id="'.$control_name.$name.'" value="'.$value.'" '.$class.' '.$size.' />
		</div>
		';
	}
}