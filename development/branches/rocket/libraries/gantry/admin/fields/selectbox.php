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

gantry_import('core.gantryformfield');

class JFormFieldSelectBox extends GantryFormField {

	public function getGantryInput(){
        global $gantry;
		$document =& JFactory::getDocument();

		if (!defined('GANTRY_SELECTBOX')) {
			$this->template = end(explode(DS, $gantry->templatePath));
			$document->addScript($gantry->gantryUrl.'/admin/widgets/selectbox/js/selectbox.js');
			
			define('GANTRY_SELECTBOX', 1);
		}
		
		$lis = $activeElement = "";
		$xml = false;
        $node = $this->element;
        $translation = $this->element['translation']?$this->element['translation']:true;
		

		$options = $this->getOptions();

		$this->isPreset = ($this->element['preset']) ? $this->element['preset'] : false;
        $imapreset = ($this->isPreset) ? "im-a-preset" : "";
		
		foreach($options as $option) {
            $optionData = $option->text;
            $optionValue = $option->value;
            $optionDisabled = $option->disable;

			$disabled = ($optionDisabled == 'disable') ? "disabled='disabled'" : "";
			$selected = ($this->value == $optionValue) ? "selected='selected'" : "";
			$active = ($this->value == $optionValue) ? "class='active'" : "";
			if (strlen($active)) $activeElement = $optionData;

			if (strlen($disabled)) $active = "class='disabled'";

			$imapreset = ($this->isPreset) ? "im-a-preset" : "";

			$text = ($translation) ? JTEXT::_($optionData) : $optionData;

			$options .= "<option value='$optionValue' $selected $disabled>".$text."</option>\n";
			$lis .= "<li ".$active.">".$text."</li>";
		}
		
		$html  = "<div class='wrapper'>";
		$html .= "<div class='selectbox-wrapper'>";
		
		$html .= "	<div class='selectbox'>";

		$html .= "		<div class='selectbox-top'>";
		$html .= "			<div class='selected'><span>".JTEXT::_($activeElement)."</span></div>";
		$html .= "			<div class='arrow'></div>";
		$html .= "		</div>";
		$html .= "		<div class='selectbox-dropdown'>";
		$html .= "			<ul>".$lis."</ul>";
		$html .= "			<div class='selectbox-dropdown-bottom'><div class='selectbox-dropdown-bottomright'></div></div>";
		$html .= "		</div>";

		$html .= "	</div>";
		
		$html .= "	<select id='".$this->id."' name='".$this->name."' class='selectbox-real ".$imapreset."'>";
		$html .= 		$options;
		$html .= "	</select>";
		$html .= "</div>";
		$html .= "<div class='clr'></div>";
		$html .= "</div>";
		
		return $html;
	}

    	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();
        $translation = $this->element['translation']?$this->element['translation']:true;

		foreach ($this->element->children() as $option) {

			// Only add <option /> elements.
			if ($option->getName() != 'option') {
				continue;
			}

			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_('select.option', (string) $option['value'], JText::_(trim((string) $option)), 'value', 'text', ((string) $option['disabled']=='true'));

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}
		reset($options);

		return $options;
	}
		
}

?>