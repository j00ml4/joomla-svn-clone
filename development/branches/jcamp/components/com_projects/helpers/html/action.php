<?php
/**
 * @version		$Id: content.php 17085 2010-05-16 00:03:00Z severdia $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	com_content
 */
abstract class JHtmlAction
{
	/**
	 * @param	int $value	The state value
	 * @param	int $i
	 */
	function task($text, $task, $id=null, $action='index.php?option=com_projects'){
		
		if($id){
			$action .= '&id='.$id;
		}
		
		$html =	'<form style="display:inline;" class="task-button" action="'. $action .'" method="post">'.
					'<button type="submit">'. $text .'</button>'.	
					'<input type="hidden" name="task" value="'. $task .'" />'.
					JHTML::_( 'form.token' ).
				'</form>';
		return $html; 
	}
	
	/**
	 * Edit button
	 * 
	 * @param unknown_type $text
	 * @param unknown_type $id
	 * @param unknown_type $controller
	 */
	function edit($text, $controller, $id){
		$task = $controller.'.edit';		
		return self::task($text, $task, $id);
	}
	
	/**
	 * Add button
	 * 
	 * @param unknown_type $text
	 * @param unknown_type $id
	 * @param unknown_type $controller
	 */
	function add($text, $controller){
		$task = $controller.'.add';		
		return self::task($text, $task);
	}
	
	/**
	 * Edit button
	 * 
	 * @param unknown_type $text
	 * @param unknown_type $id
	 * @param unknown_type $controller
	 */
	function delete($text, $controller, $id){
		$task = $controller.'.delete';		
		return self::task($text, $task, $id);
	}
	
}