<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Form
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.filesystem.path');
jimport('joomla.form.formfield');

/**
 * Form Class for the Joomla Framework.
 *
 * This class implements a robust API for constructing, populating,
 * filtering, and validating forms. It uses XML definitions to
 * construct form fields and a variety of field and rule classes
 * to render and validate the form.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JForm
{
	/**
	 * The data store for form fields during display.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected $data = array();

	/**
	 * The form object errors array.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected $errors = array();

	/**
	 * The name of the form instance.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $name;

	/**
	 * The form object options for use in rendering and validation.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected $options = array();

	/**
	 * The form XML definition.
	 *
	 * @var		object
	 * @since	1.6
	 */
	protected $xml;

	/**
	 * Static array of JFormField objects for re-use.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected static $fields = array();

	/**
	 * Search arrays of paths for loading JForm, JFormField, and JFormRule class files.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected static $paths = array('fields' => array(), 'forms' => array(), 'rules' => array());

	/**
	 * Static array of JFormRule objects for re-use.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected static $rules = array();


	/**
	 * Method to instantiate the form object.
	 *
	 * @param	string	$name		The name of the form.
	 * @param	array	$options	An array of form options.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function __construct($name, array $options = array())
	{
		// Set the name for the form.
		$this->name = $name;

		// Set the options if specified.
		$this->options['control']  = isset($options['control']) ? $options['control'] : false;
	}

	public function addField()
	{

	}

	public function addFields()
	{

	}

	public function getFieldAttribute()
	{

	}

	public function setField()
	{

	}

	public function setFields()
	{

	}

	public function setFieldAttribute()
	{

	}

	public function removeGroup()
	{

	}

	public function removeField()
	{

	}

	/**
	 * Method to bind data to the form.
	 *
	 * @param	mixed	An array or object of data to bind to the form.
	 *
	 * @return	mixed	Boolean false on error or array of JXMLElement objects.
	 * @since	1.6
	 */
	public function bind($data)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return false;
		}

		// The data must be an object or array.
		if (!is_object($data) && !is_array($data)) {
			return false;
		}

		// Convert objects to arrays.
		if (is_object($data)) {
			if ($data instanceof JRegistry) {
				// Handle a JRegistry object.
				$data = $data->toArray();
			} else if ($data instanceof JObject) {
				// Handle a JObject.
				$data = $data->getProperties();
			} else {
				// Handle other types of objects.
				$data = (array) $data;
			}
		}

		foreach ($data as $name => $value) {

			if ($fields = $this->xml->xpath('//field[@name="'.$name.'"]')) {

				// We have a field of that name and value.
				$this->data[$name] = $value;

			} else if ($this->xml->xpath('//fields[@name="'.$name.'"]') && is_array($value)) {

				// We have a fields of that name and the data value is also an array
				foreach ($value as $subName => $subValue) {

					// Validate the subfield name.
					if ($fields = $this->xml->xpath('//fields[@name="'.$name.'"]//field[@name="'.$subName.'"]')) {
						$this->data[$name][$subName] = $subValue;
					}
				}
			}
		}

		return true;
	}

	/**
	 * Method to filter the form data.
	 *
	 * @param	array	$data	An array of field values to filter.
	 * @param	string	$group	The optional name of a field group for which to filter fields.
	 *
	 * @return	mixed	boolean	True on sucess.
	 * @since	1.6
	 */
	public function filter($data, $group = null)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return false;
		}

		// Initialize variables.
		$raw		= (array) $data;
		$filtered	= array();

		// Get the fields for which to filter the data.
		$fields = $this->findFieldsByGroup($group);
		if (!$fields) {
			// PANIC!
			return false;
		}

		// Filter the fields.
		foreach ($fields as $field) {

			// Initialize variables.
			$groups		= array();
			$groupName	= null;
			$fieldName	= (string) $field['name'];

			// Get the field groups for the element.
			$names = $field->xpath('ancestor::fields[@name]/@name');

			// Build the group for the element.
			foreach ($names as $name) {
				$groups[] = (string) $name;
			}

			// Use only one level of group depth for now.
			$groupName = $groups[0];

			// Get the field value from the data input.
			if ($groupName) {
				// Filter the value if it exists.
				if (isset($raw[$groupName][$fieldName])) {
					$filtered[$groupName][$fieldName] = $this->filterField($field, $raw[$groupName][$fieldName]);
				}
			}
			else {
				// Filter the value if it exists.
				if (isset($raw[$fieldName])) {
					$filtered[$fieldName] = $this->filterField($field, $raw[$fieldName]);
				}
			}
		}

		return $filtered;
	}

	/**
	 * Return all errors, if any.
	 *
	 * @return	array	Array of error messages or JException objects.
	 * @since	1.6
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Method to get a form field represented as a JFormField object.
	 *
	 * @param	string	$name	The name of the form field.
	 * @param	string	$group	The optional form field group in which to find the field.
	 * @param	mixed	$value	The optional value to use as the default for the field.
	 *
	 * @return	mixed	The JFormField object for the field or boolean false on error.
	 * @since	1.6
	 */
	public function getField($name, $group = null, $value = null)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return false;
		}

		// Attempt to find the field by name and group.
		$element = $this->findField($name, $group);

		// If the field element was not found return false.
		if (!$element) {
			return false;
		}

		return $this->loadField($element, $group, $value);
	}

	/**
	 * Method to get an array of JFormField objects in a given fieldset by name.  If no name is
	 * given then all fields are returned.
	 *
	 * @param	string	$set	The optional name of the fieldset.
	 *
	 * @return	array	The array of JFormField objects in the fieldset.
	 * @since	1.6
	 */
	public function getFieldset($set = null)
	{
		// Initialize variables.
		$fields = array();

		// Get all of the field elements in the fieldset.
		if ($set) {
			$elements = $this->findFieldsByFieldset($set);
		}
		// Get all fields.
		else {
			$elements = $this->findFieldsByGroup();
		}

		// If no field elements were found return empty.
		if (empty($elements)) {
			return $fields;
		}

		// Build the result array from the found field elements.
		foreach ($elements as $element) {
			// Initialize variables.
			$groups = array();
			$group = null;

			// Get the field groups for the element.
			$names = $element->xpath('ancestor::fields[@name]/@name');

			// Build the group for the element.
			foreach ($names as $name) {
				$groups[] = (string) $name;
			}

			// Use only one level of group depth for now.
			$group = $groups[0];

			// If the field is successfully loaded add it to the result array.
			if ($field = $this->loadField($element, $group)) {
				$fields[$field->id] = $field;
			}
		}

		return $fields;
	}

	/**
	 * Method to get an array of fieldset objects optionally filtered over a given field group.
	 *
	 * @param	string	$name	The optional name of a field group on which to filter fieldsets.
	 *
	 * @return	array	The array of fieldset objects.
	 * @since	1.6
	 */
	public function getFieldsets($group = null)
	{
		// Initialize variables.
		$fieldsets = array();

		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return $fieldsets;
		}

		if ($group) {
			/*
			 * Get an array of <fieldset /> elements and fieldset attributes that are underneath a
			 * <fields /> element with the appropriate name attribute.
			 */
			$sets = $this->xml->xpath(
				'//fields[@name="'.$group.'"]//fieldset[@name] ' .
				'| //fields[@name="'.$group.'"]//field[@fieldset]/@fieldset'
			);
		}
		else {
			// Get an array of <fieldset /> elements and fieldset attributes.
			$sets = $this->xml->xpath('//fieldset[@name] | //field[@fieldset]/@fieldset');
		}

		// If no fieldsets are found return empty.
		if (empty($sets)) {
			return $fieldsets;
		}

		// Process each found fieldset.
		foreach ($sets as $set) {

			// Are we dealing with a fieldset element?
			if ((string) $set['name']) {

				// Only create it if it doesn't already exist.
				if (empty($fieldsets[(string) $set['name']])) {

					// Build the fieldset object.
					$fieldset = (object) array('name' => '', 'label' => '', 'description' => '');
					foreach ($set->attributes() as $name => $value) {
						$fieldset->$name = (string) $value;
					}

					// Add the fieldset object to the list.
					$fieldsets[$fieldset->name] = $fieldset;
				}
			}
			// Must be dealing with a fieldset attribute.
			else {

				// Only create it if it doesn't already exist.
				if (empty($fieldsets[(string) $set])) {

					// Attempt to get the fieldset element for data (throughout the entire form document).
					$tmp = $this->xml->xpath('//fieldset[@name="'.(string) $set.'"]');

					// If no element was found, build a very simple fieldset object.
					if (empty($tmp)) {
						$fieldset = (object) array('name' => (string) $set, 'label' => '', 'description' => '');
					}
					// Build the fieldset object from the element.
					else {
						$fieldset = (object) array('name' => '', 'label' => '', 'description' => '');
						foreach ($tmp[0]->attributes() as $name => $value) {
							$fieldset->$name = (string) $value;
						}
					}

					// Add the fieldset object to the list.
					$fieldsets[$fieldset->name] = $fieldset;
				}
			}
		}

		return $fieldsets;
	}

	/**
	 * Method to get the form control. This string serves as a container for all form fields. For
	 * example, if there is a field named 'foo' and a field named 'bar' and the form control is
	 * empty the fields will be rendered like: <input name="foo" /> and <input name="bar" />.  If
	 * the form control is set to 'joomla' however, the fields would be rendered like:
	 * <input name="joomla[foo]" /> and <input name="joomla[bar]" />.
	 *
	 * @return	string	The form control string.
	 * @since	1.6
	 */
	public function getFormControl()
	{
		return (string) $this->options['control'];
	}

	/**
	 * Method to get an array of JFormField objects in a given field group by name.
	 *
	 * @param	string	$name	The name of the field group.
	 *
	 * @return	array	The array of JFormField objects in the field group.
	 * @since	1.6
	 */
	public function getGroup($group)
	{
		// Initialize variables.
		$fields = array();

		// Get all of the field elements in the fieldset.
		$elements = $this->findFieldsByGroup($group);

		// If no field elements were found return empty.
		if (empty($elements)) {
			return $fields;
		}

		// Build the result array from the found field elements.
		foreach ($elements as $element) {
			// If the field is successfully loaded add it to the result array.
			if ($field = $this->loadField($element, $group)) {
				$fields[$field->id] = $field;
			}
		}

		return $fields;
	}

	/**
	 * Method to get a form field markup for the field input.
	 *
	 * @param	string	$name	The name of the form field.
	 * @param	string	$group	The optional form field group in which to find the field.
	 * @param	mixed	$value	The optional value to use as the default for the field.
	 *
	 * @return	string	The form field markup.
	 * @since	1.6
	 */
	public function getInput($name, $group = null, $value = null)
	{
		// Attempt to get the form field.
		if ($field = $this->getField($name, $group, $value)) {
			return $field->input;
		}

		return '';
	}

	/**
	 * Method to get a form field markup for the field input.
	 *
	 * @param	string	$name	The name of the form field.
	 * @param	string	$group	The optional form field group in which to find the field.
	 *
	 * @return	string	The form field markup.
	 * @since	1.6
	 */
	public function getLabel($name, $group = null)
	{
		// Attempt to get the form field.
		if ($field = $this->getField($name, $group)) {
			return $field->label;
		}

		return '';
	}

	/**
	 * Method to get the form name.
	 *
	 * @return	string	The name of the form.
	 * @since	1.6
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Method to get the value of a field.
	 *
	 * @param	string	$name		The name of the field for which to get the value.
	 * @param	string	$group		The group the field is in if any.
	 * @param	mixed	$default	The optional default value of the field value is empty.
	 *
	 * @return	mixed	The value of the field or the default value if empty.
	 * @since	1.6
	 */
	public function getValue($name, $group = null, $default = null)
	{
		// Initialize the return value to the default.
		$return = $default;

		if ($group) {
			// If the value exists for the field name in the group use it.
			if (isset($this->data[$group][$name])) {
				$return = $this->data[$group][$name];
			}
		}
		else {
			// If the value exists for the field name use it.
			if (isset($this->data[$name])) {
				$return = $this->data[$name];
			}
		}

		return $return;
	}

	/**
	 * Method to load the form description from an XML string or object.
	 *
	 * The reset option works on a group basis. If the XML references groups
	 * that have already been created they will be replaced with the fields
	 * in the new XML unless the $reset parameter has been set to false.
	 *
	 * @param	string	$data	The name of an XML string or object.
	 * @param	string	$reset	Flag to toggle whether the form description should be reset.
	 *
	 * @return	boolean	True on success, false otherwise.
	 * @since	1.6
	 */
	public function load($data, $reset = true)
	{
		// If the data to load isn't already an XML element or string return false.
		if ((!$data instanceof JXMLElement) && (!is_string($data))) {
			return false;
		}

		// Attempt to load the XML if a string.
		if (is_string($data)) {
			$data = JFactory::getXML($data, false);

			// Make sure the XML loaded correctly.
			if (!$data) {
				return false;
			}
		}

		// Verify that the XML document is designed for JForm.
		if ($data->getName() != 'form') {
			return false;
		}

		if (count($data->fields) !== 1) {
			return false;
		}

		// Handle the easy cases first.
		if (empty($this->xml) || $reset) {
			$this->xml = $data;
			return true;
		}

		// Merge the new fields into the existing XML document.
		self::mergeNodes($this->xml->fields, $data->fields);

		return true;
	}

	/**
	 * Method to load the form description from an XML file.
	 *
	 * The reset option works on a group basis. If the XML file references
	 * groups that have already been created they will be replaced with the
	 * fields in the new XML file unless the $reset parameter has been set
	 * to false.
	 *
	 * @param	string	$file	The filesystem path of an XML file.
	 * @param	string	$reset	Flag to toggle whether the form description should be reset.
	 *
	 * @return	boolean	True on success, false otherwise.
	 * @since	1.6
	 */
	public function loadFile($file, $reset = true)
	{
		// Check to see if the path is an absolute path.
		if (!is_file($file)) {

			// Not an absolute path so let's attempt to find one using JPath.
			$file = JPath::find(self::addFormPath(), strtolower($file).'.xml');

			// If unable to find the file return false.
			if (!$file) {
				return false;
			}
		}

		// Attempt to load the XML file.
		$xml = JFactory::getXML($file, true);

		return $this->load($xml);
	}

	/**
	 * Method to set the value of a field. If the field does not exist in the form then the method
	 * will return false.
	 *
	 * @param	string	$name	The name of the field for which to set the value.
	 * @param	string	$group	The group the field is in if any.
	 * @param	mixed	$value	The value to set for the field.
	 *
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function setValue($name, $group = null, $value = null)
	{
		// If the field does not exist return false.
		if (!$this->findField($name, $group)) {
			return false;
		}

		// If a group is set use it.
		if ($group) {
			$this->data[$group][$name] = $value;
		}
		else {
			$this->data[$name] = $value;
		}

		return true;
	}

	/**
	 * Method to validate form data.
	 *
	 * Validation warnings will be pushed into JForm::errors and should be
	 * retrieved with JForm::getErrors() when validate returns boolean false.
	 *
	 * @param	array	$data	An array of field values to validate.
	 * @param	string	$group	The optional name of a field group on which to filter fields to validate.
	 *
	 * @return	mixed	boolean	True on sucess.
	 * @since	1.6
	 */
	public function validate($data, $group = null)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return false;
		}

		// Initialize variables.
		$data	= (array) $data;
		$return	= true;

		// Get the fields for which to validate the data.
		$fields = $this->findFieldsByGroup($group);
		if (!$fields) {
			// PANIC!
			return false;
		}

		// Validate the fields.
		foreach ($fields as $field) {

			// Initialize variables.
			$groups		= array();
			$groupName	= null;
			$value		= null;
			$fieldName	= (string) $field['name'];

			// Get the field groups for the element.
			$names = $field->xpath('ancestor::fields[@name]/@name');

			// Build the group for the element.
			foreach ($names as $name) {
				$groups[] = (string) $name;
			}

			// Use only one level of group depth for now.
			$groupName = $groups[0];

			// Get the field value from the data input.
			if ($groupName) {
				// If the value exists for the field name in the group use it.
				if (isset($data[$groupName][$fieldName])) {
					$value = $data[$groupName][$fieldName];
				}
			}
			else {
				// If the value exists for the field name use it.
				if (isset($data[$fieldName])) {
					$value = $data[$fieldName];
				}
			}

			// Validate the field.
			$valid = $this->validateField($field, $group, $value);

			// Check for an error.
			if (JError::isError($valid)) {
				switch ($valid->get('level'))
				{
					case E_ERROR:
						JError::raiseWarning(0, $valid->getMessage());
						return false;
						break;
					default:
						array_push($this->errors, $valid);
						$return = false;
						break;
				}
			}
		}

		return $return;
	}

	/**
	 * Method to apply an input filter to a value based on field data.
	 *
	 * @param	string	$element	The XML element object representation of the form field.
	 * @param	mixed	$value		The value to filter for the field.
	 *
	 * @return	mixed	The filtered value.
	 * @since	1.6
	 */
	protected function filterField($element, $value)
	{
		// Make sure there is a valid JXMLElement.
		if (!$element instanceof JXMLElement) {
			return false;
		}

		// Get the field filter type.
		$filter = (string) $element['filter'];

		// If no filter is set return the raw value.
		if (!$filter) {
			return $value;
		}

		// Process the input value based on the filter.
		$return = null;
		switch (strtoupper($filter))
		{
			// Access Control Rules.
			case 'RULES':
				$return = array();
				foreach ((array) $value as $action => $ids) {
					// Build the rules array.
					$return[$action] = array();
					foreach ($ids as $id => $p) {
						if ($p !== '') {
							$return[$action][$id] = ($p == '1' || $p == 'true') ? true : false;
						}
					}
				}
				break;

			// Do nothing, thus leaving the return value as null.
			case 'UNSET':
				break;

			// No Filter.
			case 'RAW':
				$return = $value;
				break;

			// Filter safe HTML.
			case 'SAFEHTML':
				$return = JFilterInput::getInstance(null, null, 1, 1)->clean($value, 'string');
				break;

			// Convert a date to UTC based on the server timezone offset.
			case 'SERVER_UTC':
				if (intval($value)) {
					// Get the server timezone setting.
					$offset	= $config->getValue('config.offset');

					// Return a MySQL formatted datetime string in UTC.
					$return = JFactory::getDate($value, $offset)->toMySQL();
				}
				break;

			// Convert a date to UTC based on the user timezone offset.
			case 'USER_UTC':
				if (intval($value)) {
					// Get the user timezone setting defaulting to the server timezone setting.
					$offset	= $user->getParam('timezone', $config->getValue('config.offset'));

					// Return a MySQL formatted datetime string in UTC.
					$return = JFactory::getDate($value, $offset)->toMySQL();
				}
				break;

			default:
				// Check for a callback filter.
				if (strpos($filter, '::') !== false && is_callable(explode('::', $filter))) {
					$return = call_user_func(explode('::', $filter), $value);
				}
				// Filter using a callback function if specified.
				else if (function_exists($filter)) {
					$return = call_user_func($filter, $value);
				}
				// Filter using JFilterInput. All HTML code is filtered by default.
				else {
					$return = JFilterInput::getInstance()->clean($value, $filter);
				}
				break;
		}

		return $return;
	}

	/**
	 * Method to get a form field represented as an XML element object.
	 *
	 * @param	string	$name	The name of the form field.
	 * @param	string	$group	The optional form field group in which to find the field.
	 *
	 * @return	mixed	The XML element object for the field or boolean false on error.
	 * @since	1.6
	 */
	protected function findField($name, $group = null)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return false;
		}

		// Initialize variables.
		$element = false;

		// Let's get the appropriate field element based on the method arguments.
		if ($group) {
			//Get an array of fields with the correct name in a group with the correct name.
			$fields = $this->xml->xpath('//fields[@name="'.$group.'"]//field[@name="'.$name.'"]');

			// Make sure something was found.
			if (!$fields) {
				return false;
			}

			// Assume the first one is the right one.
			$element = $fields[0];
		}
		else {
			// Get an array of fields with the correct name.
			$fields = $this->xml->xpath('//field[@name="'.$name.'"]');

			// Make sure something was found.
			if (!$fields) {
				return false;
			}

			// Search through the fields for the right one.
			foreach ($fields as $field) {
				// If we find an anscestor fields element with a group name then it isn't what we want.
				if ($field->xpath('ancestor::fields[@name]')) {
					continue;
				}
				// Found it!
				else {
					$element = $field;
					break;
				}
			}
		}

		return $element;
	}

	/**
	 * Method to get an array of <field /> elements from the form XML document which are
	 * in a specified fieldset by name.
	 *
	 * @param	string	$name	The name of the fieldset.
	 *
	 * @return	mixed	Boolean false on error or array of JXMLElement objects.
	 * @since	1.6
	 */
	protected function findFieldsByFieldset($name)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return false;
		}

		/*
		 * Get an array of <field /> elements that are underneath a <fieldset /> element
		 * with the appropriate name attribute, and also any <field /> elements with
		 * the appropriate fieldset attribute.
		 */
		$fields = $this->xml->xpath('//fieldset[@name="'.$name.'"]//field | //field[@fieldset="'.$name.'"]');

		return $fields;
	}

	/**
	 * Method to get an array of <field /> elements from the form XML document which are
	 * in a control group by name.
	 *
	 * @param	string	$name	The optional name of the control group.
	 *
	 * @return	mixed	Boolean false on error or array of JXMLElement objects.
	 * @since	1.6
	 */
	protected function findFieldsByGroup($name = null)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return false;
		}

		// Get only fields in a specific group?
		if ($name) {
			// Check to see if the group does not exist.
			if ($this->xml->xpath('//fields[@name="'.$name.'"]')) {
				JError::raiseWarning(0, JText::sprintf('LIB_FORM_VALIDATE_GROUP_NOT_FOUND', $name));
				return false;
			}

			/*
			 * Get an array of <field /> elements that are underneath a <fields /> element
			 * with the appropriate name attribute.
			 */
			$fields = $this->xml->xpath('//fields[@name="'.$name.'"]//field');
		}
		else {
			// Get an array of all the <field /> elements.
			$fields = $this->xml->xpath('//field');
		}

		return $fields;
	}

	/**
	 * Method to load, setup and return a JFormField object based on field data.
	 *
	 * @param	string	$element	The XML element object representation of the form field.
	 * @param	string	$group		The optional form field group in which to find the field.
	 * @param	mixed	$value		The optional value to use as the default for the field.
	 *
	 * @return	mixed	The JFormField object for the field or boolean false on error.
	 * @since	1.6
	 */
	protected function loadField($element, $group = null, $value = null)
	{
		// Make sure there is a valid JXMLElement.
		if (!$element instanceof JXMLElement) {
			return false;
		}

		// Get the field type.
		$type = $element['type'] ? (string) $element['type'] : 'text';

		// Load the JFormField object for the field.
		$field = $this->loadFieldType($type);

		// If the object could not be loaded, get a text field object.
		if ($field === false) {
			$field = $this->loadFieldType('text');
		}

		// Get the value for the form field if not set. Default to the 'default' attribute for the field.
		if ($value === null) {
			$value = $this->getValue((string) $element['name'], $group, (string) $element['default']);
		}

		// Setup the JFormField object.
		$field->setForm($this);

		if ($field->setup($element, $value, $group)) {
			return $field;
		}
		else {
			return false;
		}
	}

	/**
	 * Method to load a form field object given a type.
	 *
	 * @param	string	$type	The field type.
	 * @param	boolean	$new	Flag to toggle whether we should get a new instance of the object.
	 *
	 * @return	mixed	JFormField object on success, false otherwise.
	 * @since	1.6
	 */
	protected function & loadFieldType($type, $new = true)
	{
		// Initialize variables.
		$false	= false;
		$key	= md5($type);
		$class	= 'JFormField'.ucfirst($type);

		// Return the JFormField object if it already exists and we don't need a new one.
		if (isset(self::$fields[$key]) && $new === false) {
			return self::$fields[$key];
		}

		// Attempt to import the JFormField class file if it isn't already imported.
		if (!class_exists($class)) {

			// Get the field search path array.
			$paths = self::addFieldPath();

			// If the type is complex, add the base type to the paths.
			if ($pos = strpos($type, '_')) {

				// Add the complex type prefix to the paths.
				for ($i = 0, $n = count($paths); $i < $n; $i++) {
					// Derive the new path.
					$path = $paths[$i].DS.strtolower(substr($type, 0, $pos));

					// If the path does not exist, add it.
					if (!in_array($path, $paths)) {
						array_unshift($paths, $path);
					}
				}

				// Break off the end of the complex type.
				$type = substr($type, $pos+1);
			}

			// Try to find the field file.
			if ($file = JPath::find($paths, strtolower($type).'.php')) {
				require_once $file;
			} else {
				return $false;
			}

			// Check once and for all if the class exists.
			if (!class_exists($class)) {
				return $false;
			}
		}

		// Instantiate a new field object.
		self::$fields[$key] = new $class();

		return self::$fields[$key];
	}

	/**
	 * Method to load a form rule object given a type.
	 *
	 * @param	string	$type	The rule type.
	 * @param	boolean	$new	Flag to toggle whether we should get a new instance of the object.
	 *
	 * @return	mixed	JFormRule object on success, false otherwise.
	 * @since	1.6
	 */
	protected function & loadRuleType($type, $new = true)
	{
		// Initialize variables.
		$false	= false;
		$key	= md5($type);
		$class	= 'JFormRule'.ucfirst($type);

		// Return the JFormRule object if it already exists and we don't need a new one.
		if (isset(self::$rules[$key]) && $new === false) {
			return self::$rules[$key];
		}

		// Attempt to import the JFormRule class file if it isn't already imported.
		if (!class_exists($class)) {

			// Get the field search path array.
			$paths = self::addRulePath();

			// If the type is complex, add the base type to the paths.
			if ($pos = strpos($type, '_')) {

				// Add the complex type prefix to the paths.
				for ($i = 0, $n = count($paths); $i < $n; $i++) {
					// Derive the new path.
					$path = $paths[$i].DS.strtolower(substr($type, 0, $pos));

					// If the path does not exist, add it.
					if (!in_array($path, $paths)) {
						array_unshift($paths, $path);
					}
				}

				// Break off the end of the complex type.
				$type = substr($type, $pos+1);
			}

			// Try to find the field file.
			if ($file = JPath::find($paths, strtolower($type).'.php')) {
				require_once $file;
			} else {
				return $false;
			}

			// Check once and for all if the class exists.
			if (!class_exists($class)) {
				return $false;
			}
		}

		// Instantiate a new field object.
		self::$rules[$key] = new $class();

		return self::$rules[$key];
	}

	/**
	 * Method to validate a JFormField object based on field data.
	 *
	 * @param	string	$element	The XML element object representation of the form field.
	 * @param	string	$group		The optional form field group in which to find the field.
	 * @param	mixed	$value		The optional value to use as the default for the field.
	 *
	 * @return	mixed	Boolean true if field value is valid, JException on failure.
	 * @since	1.6
	 */
	protected function validateField($element, $group = null, $value = null)
	{
		// Make sure there is a valid JXMLElement.
		if (!$element instanceof JXMLElement) {
			return new JException(JText::_('LIB_FORM_VALIDATE_FIELD_ERROR'), 0, E_ERROR);
		}

		// Check if the field is required.
		$required = ((string) $element['required'] == 'true' || (string) $element['required'] == 'required');
		if ($required) {

			// If the field is required and the value is empty return an error message.
			if (($value === '') || ($value === null)) {

				// Does the field have a defined error message?
				$message = (string) $element['message'];
				if ($message) {
					return new JException(JText::_($message), 0, E_WARNING);
				} else {
					return new JException(JText::sprintf('LIB_FORM_VALIDATE_FIELD_REQUIRED', JText::_((string) $element['name'])), 0, E_WARNING);
				}
			}
		}

		// Get the field validation rule.
		$type = (string) $element['validate'];
		if ($type) {
			// Load the JFormRule object for the field.
			$rule = $this->loadRuleType($type);

			// If the object could not be loaded return an error message.
			if ($rule === false) {
				return new JException(JText::sprintf('LIB_FORM_VALIDATE_FIELD_RULE_MISSING', $rule), 0, E_ERROR);
			}
		}

		// Run the field validation rule test.
		$valid = $rule->test($element, $value, $group, $this);

		// Check for an error in the validation test.
		if (JError::isError($valid)) {
			return $valid;
		}

		// Check if the field is valid.
		if ($valid === false) {

			// Does the field have a defined error message?
			$message = (string) $element['message'];
			if ($message) {
				return new JException(JText::_($message), 0, E_WARNING);
			} else {
				return new JException(JText::sprintf('LIB_FORM_VALIDATE_FIELD_INVALID', JText::_((string) $element['name'])), 0, E_WARNING);
			}
		}

		return true;
	}

	/**
	 * Method to add a path to the list of field include paths.
	 *
	 * @param	mixed	$new	A path or array of paths to add.
	 *
	 * @return	array	The list of paths that have been added.
	 * @since	1.6
	 */
	public static function addFieldPath($new = null)
	{
		// Add the default form search path if not set.
		if (empty(self::$paths['fields'])) {
			self::$paths['fields'][] = dirname(__FILE__).'/fields';
		}

		// Force the new path(s) to an array.
		settype($new, 'array');

		// Add the new paths to the stack if not already there.
		foreach ($new as $path) {
			if (!in_array($path, self::$paths['fields'])) {
				array_unshift(self::$paths['fields'], trim($path));
			}
		}

		return self::$paths['fields'];
	}

	/**
	 * Method to add a path to the list of form include paths.
	 *
	 * @param	mixed	$new	A path or array of paths to add.
	 *
	 * @return	array	The list of paths that have been added.
	 * @since	1.6
	 */
	public static function addFormPath($new = null)
	{
		// Add the default form search path if not set.
		if (empty(self::$paths['forms'])) {
			self::$paths['forms'][] = dirname(__FILE__).'/forms';
		}

		// Force the new path(s) to an array.
		settype($new, 'array');

		// Add the new paths to the stack if not already there.
		foreach ($new as $path) {
			if (!in_array($path, self::$paths['forms'])) {
				array_unshift(self::$paths['forms'], trim($path));
			}
		}

		return self::$paths['forms'];
	}

	/**
	 * Method to add a path to the list of rule include paths.
	 *
	 * @param	mixed	$new	A path or array of paths to add.
	 *
	 * @return	array	The list of paths that have been added.
	 * @since	1.6
	 */
	public static function addRulePath($new = null)
	{
		// Add the default form search path if not set.
		if (empty(self::$paths['rules'])) {
			self::$paths['rules'][] = dirname(__FILE__).'/rules';
		}

		// Force the new path(s) to an array.
		settype($new, 'array');

		// Add the new paths to the stack if not already there.
		foreach ($new as $path) {
			if (!in_array($path, self::$paths['rules'])) {
				array_unshift(self::$paths['rules'], trim($path));
			}
		}

		return self::$paths['rules'];
	}

	/**
	 * Adds a new child SimpleXMLElement node to the source.
	 *
	 * @param	SimpleXMLElement	The source element on which to append.
	 * @param	SimpleXMLElement	The new element to append.
	 */
	protected static function addNode(SimpleXMLElement $source, SimpleXMLElement $new)
	{
		// Add the new child node.
		$node = $source->addChild($new->getName(), trim($new));

		// Add the attributes of the child node.
		foreach ($new->attributes() as $name => $value) {
			$node->addAttribute($name, $value);
		}

		// Add any children of the new node.
		foreach ($new->children() as $child) {
			self::addNode($node, $child);
		}
	}

	protected static function mergeNode(SimpleXMLElement $source, SimpleXMLElement $new)
	{
		// Update the attributes of the child node.
		foreach ($new->attributes() as $name => $value) {
			if (isset($source[$name])) {
				$source[$name] = (string) $value;
			} else {
				$source->addAttribute($name, $value);
			}
		}

		// What to do with child elements?
	}

	/**
	 * Merges new elements into a source <fields> element.
	 *
	 * @param	SimpleXMLElement	The source element.
	 * @param	SimpleXMLElement	The new element to merge.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected static function mergeNodes(SimpleXMLElement $source, SimpleXMLElement $new)
	{
		// The assumption is that the inputs are at the same relative level.
		// So we just have to scan the children and deal with them.

		// Update the attributes of the child node.
		foreach ($new->attributes() as $name => $value) {
			if (isset($source[$name])) {
				$source[$name] = (string) $value;
			} else {
				$source->addAttribute($name, $value);
			}
		}

		foreach ($new->children() as $child) {
			$type = $child->getName();
			$name = $child['name'];

			// Does this node exist?
			$fields = $source->xpath($type.'[@name="'.$name.'"]');

			if (empty($fields)) {
				// This node does not exist, so add it.
				self::addNode($source, $child);
			} else {
				// This node does exist.
				switch ($type) {
					case 'field':
						self::mergeNode($fields[0], $child);
						break;

					default:
						self::mergeNodes($fields[0], $child);
						break;
				}
			}
		}
	}
}
