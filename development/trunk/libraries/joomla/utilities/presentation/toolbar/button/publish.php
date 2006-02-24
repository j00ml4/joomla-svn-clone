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
 * Renders a publish button
 *
 * @author 		Louis Landry <louis@webimagery.net>
 * @package 	Joomla.Framework
 * @subpackage 	Utilities
 * @since		1.1
 */
class JButton_Publish extends JButton
{
	/**
	 * Button type
	 *
	 * @access	protected
	 * @var		string
	 */
	var $_name = 'Publish';

	function fetchButton( $type='Publish', $list = false, $text = 'Publish', $task = 'publish' )
	{
		$text	= JText::_($text);
		$class	= $this->fetchIconClass('publish');
		$doTask	= $this->_getCommand($task, $list);

		$html  = "<a onclick=\"$doTask\">\n";
		$html .= "<div class=\"$class\" title=\"$text\" type=\"$type\">\n";
		$html .= "</div>\n";
		$html .= "$text\n";
		$html .= "</a>\n";

		return $html;
	}
	
	/**
	 * Get the button CSS Id
	 * 
	 * @access	public
	 * @return	string	Button CSS Id
	 * @since	1.1
	 */
	function fetchId()
	{
		return $this->_parent->_name.'-publish';
	}

	/**
	 * Get the JavaScript command for the button
	 * 
	 * @access	private
	 * @param	object	$definition	Button definition
	 * @return	string	JavaScript command string
	 * @since	1.1
	 */
	function _getCommand($task, $list)
	{
		if ($list) {
			$cmd = "javascript:if(document.adminForm.boxchecked.value==0){alert('". JText::_( 'Please make a selection from the list to', true ) ." publish');}else{submitbutton('$task')}";
		} else {
			$cmd = "javascript:submitbutton('$task')";
		}
		return $cmd;
	}
}
?>