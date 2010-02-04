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
	 * Object constructor.
	 *
	 * @param	array	$options	An array of form options.
	 * @return	void
	 * @since	1.6
	 */
	public function __construct($name, $options = array())
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
			$data = JFactory::getXML($data);

			// Make sure the XML loaded correctly.
			if (!$data) {
				return false;
			}
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
}
