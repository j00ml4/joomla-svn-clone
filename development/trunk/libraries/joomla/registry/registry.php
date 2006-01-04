<?php
/**
 * @version $Id$
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
 
jimport( 'joomla.common.base.object' );
jimport( 'joomla.regsitry.format' );

/**
 * JRegistry class
 * 
 * @package 	Joomla.Framework
 * @subpackage 	Registry
 * @since 1.1
 */
class JRegistry extends JObject {

	/**
	 * Default NameSpace
	 * 
	 * @var string
	 */
	var $_defaultNameSpace = null;

	/**
	 * Registry Object
	 *  - actually an array of namespace objects
	 * 
	 * @var array
	 */
	var $_registry = array ();

	/**
	 * Constructor
	 * 
	 * @param string $defaultNamespace Default registry namespace
	 * @param boolean $readOnly Is the default namespace read only? [optional: default is false]
	 */
	function __construct($namespace, $readOnly = false) {

		$this->_defaultNameSpace = $namespace;
		$this->makeNameSpace($namespace, $readOnly);
	}

	/**
	 * Returns a reference to a global JRegistry object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		<pre>  $registry = &JRegistry::getInstance($id[, $namespace][, $readOnly]);</pre>
	 *
	 * @static
	 * @param string $id An ID for the registry instance
	 * @param string $namespace The default namespace for the registry object [optional]
	 * @param boolean $readOnly Is he default namespace read only? [optional: default is false]
	 * @return object  The JRegistry object.
	 */
	function & getInstance($id, $namespace = 'joomla', $readOnly = false) {
		static $instances;

		if (!isset ($instances)) {
			$instances = array ();
		}

		if (empty ($instances[$id])) {
			$instances[$id] = & new JRegistry($namespace, $readOnly);
		}

		return $instances[$id];
	}

	/**
	 * Create a namespace
	 * 
	 * @access public
	 * @param string $namespace Name of the namespace to create
	 * @param boolean $readOnly Is the namespace read only?
	 * @return boolean True on success
	 */
	function makeNameSpace($namespace, $readOnly = false) {
		
		$this->_registry[$namespace] = array('data' => new stdClass(), 'readOnly' => $readOnly);
		return true;
	}

	/**
	 * Get a registry value
	 * 
	 * @access public
	 * @param string Registry path (e.g. joomla.content.showauthor)
	 * @param int    User Id
	 * @return mixed Value of entry or boolean false
	 */
	function getValue($regpath) {

		// Explode the registry path into an array
		$nodes = explode('.', $regpath);

		// Get the namespace
		$namespace = array_shift($nodes);
		
		if (!isset($this->_registry[$namespace])) {
			return false;	
		}
		
		$ns = & $this->_registry[$namespace]['data'];
		$pathNodes = count($nodes) - 1;

		for ($i = 0; $i < $pathNodes; $i ++) {
			$ns =& $ns->$nodes[$i];
		}
		
		return $ns->$nodes[$i++];
	}

	/**
	 * Set a registry value
	 * 
	 * @access public
	 * @param string Registry Path (e.g. joomla.content.showauthor)	 
	 * @param mixed Value of entry
	 * @return mixed Value of old value or boolean false if operation failed
	 */
	function setValue($regpath, $value) {

		// Explode the registry path into an array
		$nodes = explode('.', $regpath);

		// Get the namespace
		$namespace = array_shift($nodes);
		if (!isset($this->_registry[$namespace])) {
			$this->makeNameSpace($namespace);	
		}
		
		// If the registry is read only, return a boolean false
		if ($this->_registry[$namespace]['readOnly']) {
			return false;
		}
		
		$ns = & $this->_registry[$namespace]['data'];

		$pathNodes = count($nodes) - 1;

		for ($i = 0; $i < $pathNodes; $i ++) {
			
			// If any node along the registry path does not exist, create it
			if (!isset($ns->$nodes[$i])) {
				$ns->$nodes[$i] = new stdClass();
			}
			$ns =& $ns->$nodes[$i];
		}
		
		// Get the old value if exists so we can return it
		$retval =& $ns->$nodes[$i];
		$ns->$nodes[$i] =& $value;
		
		return $retval;
	}

	/**
	 * Load the contents of a file into the registry
	 * 
	 * @access public
	 * @param string $file Path to file to load
	 * @param string $format Format of the file [optional: defaults to INI]
	 * @param string $namespace Namespace to load the INI string into [optional]
	 * @param boolean $readOnly Should the namespace be read only after loading? [optional: default is false]
	 * @return boolean True on success
	 */
	function loadFile($file, $format = 'INI', $namespace = null, $readOnly = false) {
		// Load a file into the given namespace [or default namespace if not given]	
		$handler =& $this->_loadFormat($format);

		// If namespace is not set, get the default namespace
		if ($namespace == null) {
			$namespace = $this->_defaultNameSpace;
		}

		// Get the contents of the file
		$data =& JFile :: read($file);
		
		
		if (!isset($this->_registry[$namespace])) {
			// If namespace does not exist, make it and load the data
			$this->makeNameSpace($namespace, $readOnly);
			$this->_registry[$namespace]['data'] =& $handler->stringToObject($data);
		} else {
			// Get the data in object format
			$ns =& $handler->stringToObject($data);
			
			/*
			 * We want to leave groups that are already in the namespace and add the 
			 * groups loaded into the namespace.  This overwrites any existing group
			 * with the same name
			 */ 
			foreach (get_object_vars($ns) as $k => $v) {
				$this->_registry[$namespace]['data']->$k = $v;
			}
		}
	}

	/**
	 * Load an XML string into the registry into the given namespace [or default if a namespace is not given]
	 * 
	 * @access public
	 * @param string $data XML formatted string to load into the registry
	 * @param string $namespace Namespace to load the INI string into [optional]
	 * @param boolean $readOnly Should the namespace be read only after loading? [optional: default is false]
	 * @return boolean True on success
	 */
	function loadXML($data, $namespace = null, $readOnly = false) {
		// Load a string into the given namespace [or default namespace if not given]	
		$handler =& JRegistryFormat::getInstance('XML');

		// If namespace is not set, get the default namespace
		if ($namespace == null) {
			$namespace = $this->_defaultNameSpace;
		}

		if (!isset($this->_registry[$namespace])) {
			// If namespace does not exist, make it and load the data
			$this->makeNameSpace($namespace, $readOnly);
			$this->_registry[$namespace]['data'] =& $handler->stringToObject($data);
		} else {
			// Get the data in object format
			$ns =& $handler->stringToObject($data);
			
			/*
			 * We want to leave groups that are already in the namespace and add the 
			 * groups loaded into the namespace.  This overwrites any existing group
			 * with the same name
			 */ 
			foreach (get_object_vars($ns) as $k => $v) {
				$this->_registry[$namespace]['data']->$k = $v;
			}
		}
	}

	/**
	 * Load an INI string into the registry into the given namespace [or default if a namespace is not given]
	 * 
	 * @access public
	 * @param string $data INI formatted string to load into the registry
	 * @param string $namespace Namespace to load the INI string into [optional]
	 * @param boolean $readOnly Should the namespace be read only after loading? [optional: default is false]
	 * @return boolean True on success
	 */
	function loadINI($data, $namespace = null, $readOnly = false) {
		// Load a string into the given namespace [or default namespace if not given]
		$handler =& JRegistryFormat::getInstance('INI');

		// If namespace is not set, get the default namespace
		if ($namespace == null) {
			$namespace = $this->_defaultNameSpace;
		}
		
		if (!isset($this->_registry[$namespace])) {
			// If namespace does not exist, make it and load the data
			$this->makeNameSpace($namespace, $readOnly);
			$this->_registry[$namespace]['data'] =& $handler->stringToObject($data);
		} else {
			// Get the data in object format
			$ns =& $handler->stringToObject($data);
			
			/*
			 * We want to leave groups that are already in the namespace and add the 
			 * groups loaded into the namespace.  This overwrites any existing group
			 * with the same name
			 */ 
			foreach (get_object_vars($ns) as $k => $v) {
				$this->_registry[$namespace]['data']->$k = $v;
			}
		}
	}

	/**
	 * Get a namespace in a given string format
	 * 
	 * @access public
	 * @param string $format Format to return the string in
	 * @param string $namespace Namespace to return [optional: null returns the default namespace]
	 * @return string Namespace in string format
	 */
	function getNameSpaceString($format = 'INI', $namespace = null) {
		// Return a namespace in a given format
		$handler =& JRegistryFormat::getInstance($format);

		// If namespace is not set, get the default namespace
		if ($namespace == null) {
			$namespace = $this->_defaultNameSpace;
		}

		// Get the namespace
		$ns = & $this->_registry[$namespace]['data'];

		return $handler->objectToString($ns);
	}
}
?>