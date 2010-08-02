<?php
/**
 * @version		$Id: content.php 17085 2010-05-16 00:03:00Z severdia $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		Joomla.Site
 * @subpackage	com_projects
 */
abstract class JHtmlAction
{
	/**
	 * @param	int $value	The state value
	 * @param	int $i
	 */
	function button($text, $task, $id=null, $url='index.php?option=com_projects'){
		
		if($id){
			$url .= '&id='.$id;
		}
		
		return	'<form style="display:inline;" class="task-button" action="'. JRoute::_($url) .'" method="post">'.
					'<button type="submit">'. $text .'</button>'.	
					'<input type="hidden" name="task" value="'. $task .'" />'.
					'<input type="hidden" name="Itemid" value="'.JRequest::getInt('Itemid',0).'" />'.
					JHTML::_( 'form.token' ).
				'</form>';
	}
	
	function task($text, $task, $id=null, $url='index.php?option=com_projects'){
		return self::button($text, $task, $id, $url);
	}
	
	/**
	 * Create a link button to actions
	 * @param unknown_type $text
	 * @param unknown_type $task
	 * @param unknown_type $id
	 * @param unknown_type $url
	 * @return string
	 */
	public function link($text, $task, $id=null, $url='index.php?option=com_projects'){
		if($id){
			$url .= '&id='.$id;
		}
		if($task)
			$url .= '&task='.$task;
		
		return	'<a class="" href="'. JRoute::_($url) .'">'.
					'<button type="button">'. $text .'</button>'.
				'</a>';
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
	 * Delete button
	 * 
	 * @param unknown_type $text
	 * @param unknown_type $id
	 * @param unknown_type $controller
	 */
	function delete($text, $controller, $id){
		$task = $controller.'.delete';		
		return self::task($text, $task, $id);
	}

	/**
	 * Question button
	 * 
	 * @param unknown_type $text
	 * @param unknown_type $msg Message when no record is selected
	 * @param unknown_type $msg_confirm Message to confim the action
	 * @param unknown_type $msg_confirm_plural Message to confim the action for more than one item
	 * @param unknown_type $task Task to submit
	 */
	function question($text, $msg, $msg_confirm, $msg_confirm_plural, $task){	
	
		if($msg_confirm)
		{
			$action = 'javascript:if (document.adminForm.boxchecked.value==0){alert(\''.addslashes($msg).'\');}'.
														'else{ '.
														' if(document.adminForm.boxchecked.value==1) var msg=\''.addslashes($msg_confirm).'\';'.
														' else var msg = \''.addslashes($msg_confirm_plural).'\';'.
														'if(confirm(msg))submitbutton(\''.$task.'\');}';
		}
		else // no confirmation question
		{
			$action = 'javascript:if (document.adminForm.boxchecked.value==0){alert(\''.addslashes($msg).'\');}'.
														'else{ '.
														' submitbutton(\''.$task.'\');}';
		}		
		return	'<button type="button" onclick="'.$action.'">'. $text .'</button>';
	}
}