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
	protected $id;

	/**
	 * The name of the form field.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $name;

	/**
	 * The value of the form field.
	 *
	 * @var		mixed
	 * @since	1.6
	 */
	protected $value;

	/**
	 * The label for the form field.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $label;

	/**
	 * The input for the form field.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $input;

	/**
	 * The description text for the form field.  Usually used in tooltips.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $description;

	/**
	 * The required state for the form field.  If true then there must be a value for the field to
	 * be considered valid.
	 *
	 * @var		boolean
	 * @since	1.6
	 */
	protected $required = false;

	/**
	 * The validation method for the form field.  This value will determine which method is used
	 * to validate the value for a field.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $validate;

	/**
	 * The multiple state for the form field.  If true then multiple values are allowed for the
	 * field.  Most often used for list field types.
	 *
	 * @var		boolean
	 * @since	1.6
	 */
	protected $multiple = false;

	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type;

	/**
	 * The JXMLElement object of the <field /> XML element that describes the form field.
	 *
	 * @var		object
	 * @since	1.6
	 */
	protected $element;

	/**
	 * The JForm object of the form attached to the form field.
	 *
	 * @var		object
	 * @since	1.6
	 */
	protected $form;

	/**
	 * The form control prefix for field names from the JForm object attached to the form field.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $formControl;

	/**
	 * Method to instantiate the form field object.
	 *
	 * @param	object	$form	The form to attach to the form field object.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function __construct($form = null)
	{
		// If there is a form passed into the constructor set the form and form control properties.
		if ($form instanceof JForm) {
			$this->form = $form;
			$this->formControl = $form->getFormControl();
		}
	}

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param	string	$name	The property name for which to the the value.
	 *
	 * @return	mixed	The property value or null.
	 * @since	1.6
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'id':
				return $this->id;
				break;

			case 'name':
				return $this->name;
				break;

			case 'value':
				return $this->value;
				break;

			case 'description':
				return $this->description;
				break;

			case 'required':
				return $this->required;
				break;

			case 'validate':
				return $this->validate;
				break;

			case 'multiple':
				return $this->multiple;
				break;

			case 'type':
				return $this->type;
				break;

			case 'formControl':
				return $this->formControl;
				break;

			case 'input':
				// If the input hasn't yet been generated, generate it.
				if (empty($this->input)) {
					$this->input = $this->_getInput();
				}

				return $this->input;
				break;

			case 'label':
				// If the label hasn't yet been generated, generate it.
				if (empty($this->label)) {
					$this->label = $this->_getLabel();
				}

				return $this->label;
				break;
		}

		return null;
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
	public function setForm(JForm $form)
	{
		$this->form = $form;
		$this->formControl = $form->getFormControl();

		return $this;
	}

	/**
	 * Method to get the form field type.
	 *
	 * @return	string		The field type.
	 */
	public function setup(&$element, $value, $control)
	{
		// Make sure there is a valid JFormField XML element.
		if ((!$element instanceof JXMLElement) && ((string) $element->getName() == 'field')) {
			return false;
		}

		// Reset the input and label values.
		$this->input = null;
		$this->label = null;

		// Set the xml element object.
		$this->element = $element;

		// Get some important attributes from the form field element.
		$class		= (string) $element['class'];
		$id			= (string) $element['id'];
		$multiple	= (string) $element['multiple'];
		$name		= (string) $element['name'];
		$required	= (string) $element['required'];

		// Set the label and description text.
		$this->labelText	= (string) $element['label'] ? (string) $element['label'] : $this->name;
		$this->description	= (string) $element['description'];

		// Set the required and validate options.
		$this->required		= ($required == 'true' || $required == 'required');
		$this->validate		= (string) $element['validate'];

		// Add the required class if the field is required.
		if ($this->required) {
			if($class) {
				if (strpos($class, 'required') === false) {
					$this->element['class'] = $class.' required';
				}
			} else {
				$this->element->addAttribute('class', 'required');
			}
		}

		// Set the visibility.
		$this->hidden = ((string) $element['type'] == 'hidden' || (string) $element['hidden']);

		// Set the multiple values option.
		$this->multiple = ($multiple == 'true' || $multiple == 'multiple');

		// Set the id, name, and value.
		// Set the input name and id.
		$this->name	= $this->_getInputName($name, $this->formControl, $control, $this->multiple);
		$this->id	= $this->_getInputId($id, $name, $this->formControl, $control);

		$this->value	= $value;

		/*
		 * RESET INTERNAL DATA
		 */
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
