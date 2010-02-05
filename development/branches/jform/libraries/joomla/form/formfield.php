<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Form
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.utilities.simplexml');

/**
 * Abstract Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
abstract class JFormField
{
	/**
	 * The document id for the form field.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $id;

	/**
	 * The name of the form field.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $name;

	/**
	 * The value of the form field.
	 *
	 * @var		mixed
	 * @since	1.6
	 */
	public $value;

	/**
	 * The label text for the form field.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $label;

	/**
	 * The description text for the form field.  Usually used in tooltips.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $description;

	/**
	 * The required state for the form field.  If true then there must be a value for the field to
	 * be considered valid.
	 *
	 * @var		boolean
	 * @since	1.6
	 */
	public $required;

	/**
	 * The validation method for the form field.  This value will determine which method is used
	 * to validate the value for a field.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $validate;

	/**
	 * The multiple state for the form field.  If true then multiple values are allowed for the
	 * field.  Most often used for list field types.
	 *
	 * @var		boolean
	 * @since	1.6
	 */
	public $multiple;

	public $hidden;

	public $decorator;

	protected $formNamePrefix;
	public $prefix;

	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type;

	/**
	 * The XML node
	 *
	 * @var		object
	 * @since	1.6
	 */
	protected $element;

	/**
	 * The field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $control;

	/**
	 * A reference to the form object that the field belongs to.
	 *
	 * @var		object
	 */
	protected $_form;

	/**
	 * Method to instantiate the form field.
	 *
	 * @param	object		$form		A reference to the form that the field belongs to.
	 * @return	void
	 */
	public function __construct($form = null)
	{
		$this->_form = $form;
	}

	/**
	 * Method to get the form field type.
	 *
	 * @return	string		The field type.
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Method to get the form field type.
	 *
	 * @return	string		The field type.
	 */
	public function setControlData(&$xml, $value, $formName, $groupName, $prefix)
	{
		// Set the xml element object.
		$this->element = $xml;

		// Set the id, name, and value.
		$this->id		= (string) $xml['id'];
		$this->name		= (string) $xml['name'];
		$this->value	= $value;

		// Set the label and description text.
		$this->labelText	= (string) $xml['label'] ? (string) $xml['label'] : $this->name;
		$this->descText		= (string) $xml['description'];

		// Set the required and validate options.
		$this->required		= ((string) $xml['required'] == 'true' || (string) $xml['required'] == 'required');
		$this->validate		= (string) $xml['validate'];

		// Add the required class if the field is required.
		if ($this->required) {
			if((string) $xml['class']) {
				if (strpos((string) $xml['class'], 'required') === false) {
					$xml['class'] = (string) $xml['class'].' required';
				}
			} else {
				$xml->addAttribute('class', 'required');
			}
		}

		// Set the field decorator.
		$this->decorator = (string) $xml['decorator'];

		// Set the visibility.
		$this->hidden = ((string) $xml['type'] == 'hidden' || (string) $xml['hidden']);

		// Set the multiple values option.
		$this->multiple = ((string) $xml['multiple'] == 'true' || (string) $xml['multiple'] == 'multiple');

		// Set the form and group names.
		$this->formName		= $formName;
		$this->groupName	= $groupName;

		// Set the prefix
		$this->prefix = $prefix;

		/*
		 * RESET INTERNAL DATA
		 */
	}

	public function render(&$xml, $value, $formName, $groupName, $prefix)
	{

		// Set the input name and id.
		$this->inputName	= $this->_getInputName($this->name, $formName, $groupName, $this->multiple);
		$this->inputId		= $this->_getInputId($this->id, $this->name, $formName, $groupName);

		// Set the actual label and input.
		$this->label		= $this->_getLabel();
		$this->input		= $this->_getInput();

		return $this;
	}

	/**
	 * Method to get the field label.
	 *
	 * @return	string		The field label.
	 */
	protected function _getLabel()
	{
		// Set the class for the label.
		$class = !empty($this->descText) ? 'hasTip' : '';
		$class = $this->required == true ? $class.' required' : $class;

		// If a description is specified, use it to build a tooltip.
		if (!empty($this->descText)) {
			$label = '<label id="'.$this->inputId.'-lbl" for="'.$this->inputId.'" class="'.$class.'" title="'.htmlspecialchars(trim(JText::_($this->labelText), ':').'::'.JText::_($this->descText), ENT_COMPAT, 'UTF-8').'">';
		} else {
			$label = '<label id="'.$this->inputId.'-lbl" for="'.$this->inputId.'" class="'.$class.'">';
		}

		$label .= JText::_($this->labelText);
		$label .= '</label>';

		return $label;
	}
	/**
	 * This function replaces the string identifier prefix with the
	 * string held is the <var>formName</var> class variable.
	 *
	 * @param	string	The javascript code
	 * @return	string	The replaced javascript code
	 */
	protected function _replacePrefix($javascript)
	{
		$formName = $this->formName;

		if ($formName === false) {
			// No form, just use the field name.
			return str_replace($this->prefix, '', $javascript);
		} else {
			// Use the form name
			return str_replace($this->prefix, preg_replace('#\W#', '_',$formName).'_', $javascript);
		}
	}

	/**
	 * Method to get the field input.
	 *
	 * @return	string		The field input.
	 */
	abstract protected function _getInput();

	/**
	 * Method to get the name of the input field.
	 *
	 * @param	string		$fieldName		The field name.
	 * @param	string		$formName		The form name.
	 * @param	string		$groupName		The group name.
	 * @param	boolean		$multiple		Whether the input should support multiple values.
	 * @return	string		The input field id.
	 */
	protected function _getInputName($fieldName, $formName = false, $groupName = false, $multiple = false)
	{
		if ($formName === false && $groupName === false) {
			// No form or group, just use the field name.
			$return = $fieldName;
		} elseif ($formName !== false && $groupName === false) {
			// No group, use the form and field name.
			$return = $formName.'['.$fieldName.']';
		} elseif ($formName === false && $groupName !== false) {
			// No form, use the group and field name.
			$return = $groupName.'['.$fieldName.']';
		} else {
			// Use the form, group, and field name.
			$return = $formName.'['.$groupName.']['.$fieldName.']';
		}

		// Check if the field should support multiple values.
		if ($multiple) {
			$return .= '[]';
		}

		return $return;
	}

	/**
	 * Method to get the id of the input field.
	 *
	 * @param	string		$fieldId		The field id.
	 * @param	string		$fieldName		The field name.
	 * @param	string		$formName		The form name.
	 * @param	string		$groupName		The group name.
	 * @return	string		The input field id.
	 */
	protected function _getInputId($fieldId, $fieldName, $formName = false, $groupName = false)
	{
		// Use the field name if no id is set.
		if (empty($fieldId)) {
			$fieldId = $fieldName;
		}

		if ($formName === false && $groupName === false) {
			// No form or group, just use the field name.
			$return = $fieldId;
		} elseif ($formName !== false && $groupName === false) {
			// No group, use the form and field name.
			$return = $formName.'_'.$fieldId;
		} elseif ($formName === false && $groupName !== false) {
			// No form, use the group and field name.
			$return = $groupName.'_'.$fieldId;
		} else {
			// Use the form, group, and field name.
			$return = $formName.'_'.$groupName.'_'.$fieldId;
		}

		// Clean up any invalid characters.
		$return = preg_replace('#\W#', '_', $return);

		return $return;
	}
}
