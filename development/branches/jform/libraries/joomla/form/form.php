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
JLoader::register('JFormFieldList', dirname(__FILE__).'/fields/list.php');

/**
 * Form Class for the Joomla Framework.
 *
 * This class implements a robust API for constructing, populating,
 * filtering, and validating forms. It uses XML definitions to
 * construct form fields and a variety of field and rule classes
 * to render and validate the form.
 *
 * @package		Joomla.Framework
 * @subpackage	Forms
 * @since		1.6
 */
class JForm
{
	/**
	 * The name of the form instance.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $name;

	/**
	 * The form XML definition.
	 *
	 * @var		object
	 * @since	1.6
	 */
	protected $xml;

	/**
	 * The data store for form fields during display.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected $data = array();

	/**
	 * The form object options for use in rendering and validation.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected $options = array();

	/**
	 * Static array of JFormField objects for re-use.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected static $fields = array();

	/**
	 * Search arrays of paths for loading JForm and JFormField class files.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected static $paths = array('forms' => array(), 'fields' => array());


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
		$this->options['control']  = isset($options['control']) ? $options['control'] : '';
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
				// Handle a JRegistry/JParameter object.
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

		$this->xml = $data;

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

	public function getFormControl()
	{
		return $this->options['control'];
	}

	public function getGroup($name)
	{
//		foreach ($fields as $field) {
//			$attrs = $field->xpath('ancestor::fields[@name]/@name');
//			var_dump((string) $field['name']);
//			foreach ($attrs as $attr) {
//				var_dump((string)$attr);
//			}
//		}
	}


	/**
	 * Method to get a form field.
	 *
	 * @param	string		$name			The name of the form field.
	 * @param	string		$group			The group the field is in.
	 * @param	mixed		$formControl	The optional form control. Set to false to disable.
	 * @param	mixed		$groupControl	The optional group control. Set to false to disable.
	 * @param	mixed		$value			The optional value to render as the default for the field.
	 * @return	object		Rendered Form Field object
	 */
	public function getField($name, $group = '_default', $formControl = '_default', $groupControl = '_default', $value = null)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return false;
		}

		// Get the XML node.
		$node = isset($this->_groups[$group][$name]) ? $this->_groups[$group][$name] : null;

		// If there is no XML node for the given field name, return false.
		if (empty($node)) {
			return false;
		}

		// Load the field type.
		$type	= $node->attributes()->type;
		$field	= & $this->loadFieldType($type);

		// If the field could not be loaded, get a text field.
		if ($field === false) {
			$field = & $this->loadFieldType('text');
		}

		// Get the value for the form field.
		if ($value === null) {
			$value = (array_key_exists($name, $this->_data[$group]) && ($this->_data[$group][$name] !== null)) ? $this->_data[$group][$name] : (string)$node->attributes()->default;
		}

		// Check the form control.
		if ($formControl == '_default') {
			$formControl = $this->_options['array'];
		}


		// Check the group control.
		if ($groupControl == '_default'&& isset($this->_fieldsets[$group])) {
			$array = $this->_fieldsets[$group]['array'];
			if ($array === true) {
				if(isset($this->_fieldsets[$group]['parent'])) {
					$groupControl = $this->_fieldsets[$group]['parent'];
				} else {
					$groupControl = $group;
				}
			} else {
				$groupControl = $array;
			}
		}

		// Set the prefix
		$prefix = $this->_options['prefix'];

		// Render the field.
		return $field->render($node, $value, $formControl, $groupControl, $prefix);
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
	protected function getFieldsByFieldset($name)
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
	 * @param	string	$name	The name of the control group.
	 *
	 * @return	mixed	Boolean false on error or array of JXMLElement objects.
	 * @since	1.6
	 */
	protected function getFieldsByGroup($name)
	{
		// Make sure there is a valid JForm XML document.
		if (!$this->xml instanceof JXMLElement) {
			return false;
		}

		/*
		 * Get an array of <field /> elements that are underneath a <fields /> element
		 * with the appropriate name attribute.
		 */
		$fields = $this->xml->xpath('//fields[@name="'.$name.'"]//field');

		return $fields;
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
	protected function loadFieldType($type, $new = true)
	{
		// Initialize variables.
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
				return false;
			}

			// Check once and for all if the class exists.
			if (!class_exists($class)) {
				return false;
			}
		}

		// Instantiate a new field object.
		self::$fields[$key] = new $class($this);

		return self::$fields[$key];
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
}
