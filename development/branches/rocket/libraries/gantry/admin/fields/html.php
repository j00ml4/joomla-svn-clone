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
class JFormFieldHTML extends JFormField {
	

	protected function getInput(){
		global $gantry;
		$document =& JFactory::getDocument();
		
		$html = $this->element->html;
		
		// version
		$tmpl = $gantry->_templateDetails->xml->document->children();
		$version = $tmpl[1]->data();
		$html = str_replace("{template_version}", $version, $html);
				
		// preview
		$html = str_replace("{template_preview}", $gantry->templateUrl . '/template_thumbnail.png', $html);
		
		return $html;
	}
	
	protected function getLabel(){
        return "";
    }
	
}