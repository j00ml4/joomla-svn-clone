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
	 * Search arrays of paths for loading JForm and JFormField class files.
	 *
	 * @var		array
	 * @since	1.6
	 */
	protected static $paths = array('forms' => array(), 'fields' => array());


	/**
	 * Object constructor.
	 *
	 * @param	array	$options	An array of form options.
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
}
