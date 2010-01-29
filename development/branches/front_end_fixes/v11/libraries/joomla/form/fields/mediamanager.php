<?php
/**
 * @version		
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.form.formfield');
/**
 * Field to access the media manager from a modal list.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */

class JFormFieldMediamanager extends JFormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Mediamanager';
	protected function _getInput()
	{	
		static $init = false;
		$html = '';		

		// Initialise variables.
		$onchange	= (string)$this->_element->attributes()->onchange ? $this->_replacePrefix((string)$this->_element->attributes()->onchange) : '';
		if (!$init) {
			JHtml::_('behavior.modal');
			$js = "
			function jInsertFieldValue(value,id) {
				var old_id = document.getElementById(id).value;
				if (old_id != id)
				{
					document.getElementById(id).value = value;
					".$onchange."
				}
			}";
			$doc = &JFactory::getDocument();
			$doc->addScriptDeclaration($js);
			$init = true;
		}
	

		// Setup variables for display.
		$html = array();
		$link = 'index.php?option=com_media&amp;view=images&amp;tmpl=component';

		$html[] = '<div style="float: left;">';
		$html[] = '<input type="text" name="'.$this->inputName.'" id="'.$this->inputId.'" value="'.htmlspecialchars($this->value).'" />';
		$html[] = '</div>';
		
		// The image select button.
		$html[] = '<div class="button2-left">';
		$html[] = '  <div class="blank">';
		$html[] = '    <a class="modal_'.$this->inputId.'" title="'.JText::_('JForm_Change_Image').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">'.JText::_('JForm_Change_Image_button').'</a>';
		$html[] = '  </div>';
		$html[] = '</div>';

		// The active user id field.
		$html[] = '<input type="hidden" id="'.$this->inputId.'_id" name="'.$this->inputName.'" value="'.(int)$this->value.'" />';

		return implode("\n", $html);
	
	}	
}
