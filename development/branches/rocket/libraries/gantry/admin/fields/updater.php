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
class JFormFieldUpdater extends JFormField {

	protected function getInput(){

		global $gantry;
		
		return "
		<div id='updater' class='right-panel'>
			<div id='updater-bar'>Gantry Framework <span>v2.6</span></div>
			<div id='updater-desc'>[ Gantry 2.7 Available - <a href='#'>Download now</a> ]</div>
		</div>";
		
	}
	
	protected function getLabel(){
		return "";
    }
}