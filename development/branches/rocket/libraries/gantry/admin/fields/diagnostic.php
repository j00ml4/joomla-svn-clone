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
class JFormFieldDiagnostic extends JFormField {

	protected function getInput(){

		global $gantry;
		
		gantry_import('core.gantrydiagnostic');
		$diagnose = new GantryDiagnostic();
		$errors = $diagnose->checks();
		
		$output = "";
		
		if (count($errors) > 0) {
			$klass = "errors";
			$title = "Something Wrong :(";
			$output = implode("", $errors);
		} else {
			$klass = "success";
			$title = "All Good!";
			$output = "Congratulations! All the diagnostic test have passed successfully.  There are no show-stopper issues.  You can however still download the ";
		}
        $output .= '<a href="'.JURI::base(true).'?option=com_admin&tmpl=gantry-ajax-admin&model=diagnostics&template='.$gantry->templateName.'">Diagnostic Status Information archive</a>.';
		
		
		return "
		<div id='diagnostic' class='right-panel ".$klass."'>
			<div id='diagnostic-bar'>Diagnostics: <span>". $title ."</span></div>
			<div id='diagnostic-desc' class='g-inner'>
			".$output."
			</div>
		</div>";
		
	}
	
	protected function getLabel(){
		return "";
    }
}