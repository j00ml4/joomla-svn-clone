<?php
/**
 * @version		$Id: media.php 15757 2010-04-01 11:06:27Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR.'/includes/toolbar.php';

/**
 * Toolbar
 * Enter description here ...
 * @author eden
 *
 */
abstract class ToolBar extends JToolBarHelper
{
	/**
	 * Title cell.
	 * For the title and toolbar to be rendered correctly,
	 * this title fucntion must be called before the starttable function and the toolbars icons
	 * this is due to the nature of how the css has been used to postion the title in respect to the toolbar.
	 *
	 * @param	string	$title	The title.
	 * @param	string	$icon	The name of the image.
	 * @since	1.5
	 */
	public static function title($title, $icon = 'generic')
	{
		// Strip the extension.
		$bar = JToolBar::getInstance('toolbar');
		$icon = preg_replace('#\.[^.]*$#', '', $icon);

		$html = '<div class="pagetitle icon-48-'.$icon.'"><h2>';
		$html .= $title;
		$html .= '</h2></div>';

		$bar->set('title', $html);
	}
	
	
	public static function render() 
	{
		$bar = JToolBar::getInstance('toolbar');
		
		$html = '<div id="toolbar-box">';
		$html .= 	'<div class="t"><div class="t"><div class="t"></div></div></div>';
		$html .= 	'<div class="m">';
		$html .= 		$bar->render();
		$html .=		$bar->get('title');
		$html .= 		'<div class="clear"></div>';
		$html .= 	'</div>';
		$html .= 	'<div class="b"><div class="b"><div class="b"></div></div></div>';
		$html .= '</div>';
		return $html;
	}
	
	
	/**
	 * Writes a cancel button that will go back to the previous page without doing
	 * any other operation.
	 *
	 * @param	string	$alt	Alternative text.
	 * @param	string	$href	URL of the href attribute.
	 * @since	1.0
	 */
	public static function back($href, $alt = 'JTOOLBAR_BACK')
	{
		parent::back($alt, $href);
	}
}
?>