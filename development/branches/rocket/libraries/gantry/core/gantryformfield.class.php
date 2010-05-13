<?php
/**
 * @package     gantry
 * @subpackage  core
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
 * Renders chained element
 *
 * @package gantry
 * @subpackage admin.elements
 */
jimport('joomla.form.formfield');

abstract class GantryFormField extends JFormField
{
    public abstract function getGantryInput();

    function getGantryLabel(){
        return parent::getLabel();
    }

    protected function getLabel()
	{
        $output = "<div class='gantry-field'>";
        $output .= $this->getGantryLabel();
		return $output;
	}

    protected function getInput(){
        $output = $this->getGantryInput();
        $output .= '</div>';
        return $output;
    }
}