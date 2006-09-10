<?php
/**
 * @version $Id$
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

/**
 * Base class for a Joomla View
 * 
 * Class holding methods for displaying presentation data.
 *
 * @abstract
 * @package		Joomla.Framework
 * @subpackage	Application
 * @author		Louis Landry, Andrew Eddie
 * @since		1.5
 */
class JView extends JObject
{
	/**
	 * The name of the view
	 *
	 * @var		array
	 * @access protected
	 */
	var $_name = null;
	
	/**
	 * Registered models
	 *
	 * @var		array
	 * @access protected
	 */
	var $_models = array();
	
	/**
	 * Layout name
	 *
	 * @var		string
	 * @access 	protected
	 */
	var $_layout = null;
	
	/**
	* The set of search directories for resources (templates) 
	* and templates.
	* 
	* @var array
	* @access protected
	*/
	var $_path = array(
		'template' => array()
	);
	
   /**
	* The name of the default template source file.
	* 
	* @var string
	* @access private
	*/
	var $_template = null;
	
   /**
	* The output of the template script.
	* 
	* @var string
	* @access private
	*/
	var $_output = null;

	/**
	 * Constructor
	 *
	 * @access	protected
	 */
	function __construct($conf = array()) 
	{
		global $mainframe, $option, $Itemid;
		
		//set the view name based on the classname
		if ($this->_name === null)  {
			$r = null;
			if (!preg_match('/View(.*)/i', get_class($this), $r)) {
				JError::raiseError (500, "JView::__construct() : Can't get or parse my own class name, exiting.");
			}
			$this->_name = $r[1];
		}
		
		// set the default template search dirs
		if (isset($conf['template_path'])) {
			// user-defined dirs
			$this->setPath('template', $conf['template_path']);
		} else {
			// default directory only
			$this->setPath('template', null);
		}
		
		// set the layout
		if (isset($conf['layout'])) {
			$this->setLayout($conf['layout']);
		} else {
			$this->setLayout($this->_name);
		}
			
		$this->assignRef('mainframe', $mainframe);
		$this->assignRef('option'   , $option);
		$this->assignRef('Itemid'   , $Itemid);
	}
	
   /**
	* Execute and display a template script.
	* 
	* @param string $tpl The name of the template file to parse;
	* automatically searches through the template paths.
	* 
	* @throws object An JError object.
	* @see fetch()
	*/
	function display($tpl = null)
	{
		$result = $this->loadTemplate($tpl);
		if (JError::isError($result)) {
			return $result;
		} 
		
		echo $result;
	}
	
	/**
	* Sets variables for the view.
	* 
	* This method is overloaded; you can assign all the properties of
	* an object, an associative array, or a single value by name.
	* 
	* You are not allowed to set variables that begin with an underscore;
	* these are either private properties for JView or private variables
	* within the template script itself.
	* 
	* <code>
	* $view =& new JView();
	* 
	* // assign directly
	* $view->var1 = 'something';
	* $view->var2 = 'else';
	* 
	* // assign by name and value
	* $view->assign('var1', 'something');
	* $view->assign('var2', 'else');
	* 
	* // assign by assoc-array
	* $ary = array('var1' => 'something', 'var2' => 'else');
	* $view->assign($obj);
	* 
	* // assign by object
	* $obj = new stdClass;
	* $obj->var1 = 'something';
	* $obj->var2 = 'else';
	* $view->assign($obj);
	* 
	* </code>
	* 
	* @access public 
	* @return bool True on success, false on failure.
	*/
	function assign()
	{
		// get the arguments; there may be 1 or 2.
		$arg0 = @func_get_arg(0);
		$arg1 = @func_get_arg(1);
			
		// assign by object
		if (is_object($arg0)) 
		{
			// assign public properties
			foreach (get_object_vars($arg0) as $key => $val) {
				if (substr($key, 0, 1) != '_') {
					$this->$key = $val;
				}
			}
			return true;
		}
		
		// assign by associative array
		if (is_array($arg0)) {
			foreach ($arg0 as $key => $val) {
				if (substr($key, 0, 1) != '_') {
					$this->$key = $val;
				}
			}
			return true;
		}
		
		// assign by string name and mixed value.
		
		// we use array_key_exists() instead of isset() becuase isset()
		// fails if the value is set to null.
		if (is_string($arg0) && substr($arg0, 0, 1) != '_' && func_num_args() > 1) {
			$this->$arg0 = $arg1;
			return true;
		} 
		
		// $arg0 was not object, array, or string.
		return false;
	}
	
	
	/**
	* Sets variables for the view (by reference).
	* 
	* You are not allowed to set variables that begin with an underscore;
	* these are either private properties for JView or private variables
	* within the template script itself.
	* 
	* <code>
	* $view = new JView();
	* 
	* // assign by name and value
	* $view->assignRef('var1', $ref);
	* 
	* // assign directly
	* $view->ref =& $var1;
	* </code>
	* 
	* @access public
	* 
	* @param string $key The name for the reference in the view.
	* @param mixed &$val The referenced variable.
	* 
	* @return bool True on success, false on failure.
	*/
	
	function assignRef($key, &$val)
	{
		if (is_string($key) && substr($key, 0, 1) != '_') {
			$this->$key =& $val;
			return true;
		} 
		
		return false;
	}

	/**
	 * Method to get data from a registered model
	 *
	 * @access	public
	 * @param	string	The name of the method to call on the model
	 * @param	string	The name of the model to reference [optional]
	 * @return mixed	The return value of the method
	 */
	function &get( $method, $model = null )
	{
		$false = false;

		// If $model is null we use the default model
		if (is_null($model))
		{
			$model = $this->_defaultModel;
		}
		// First check to make sure the model requested exists
		if (isset( $this->_models[$model] ))
		{
			// Model exists, lets build the method name
			$method = 'get'.ucfirst($method);

			// Does the method exist?
			if (method_exists($this->_models[$model], $method))
			{
				// The method exists, lets call it and return what we get
				$data = $this->_models[$model]->$method();
				return $data;
			}
			else
			{
				// Method wasn't found... throw a warning and return false
				JError::raiseWarning( 0, "Unknown Method $model::$method() was not found");
				return $false;
			}
		}
		else
		{
			// Model wasn't found, return throw a warning and return false
			JError::raiseWarning( 0, 'Unknown Model', "$model model was not found");
			return $false;
		}
	}

	/**
	 * Method to add a model to the view.  We support a multiple model single
	 * view system by which models are referenced by classname.  A caveat to the
	 * classname referencing is that any classname prepended by JModel will be
	 * referenced by the name without JModel, eg. JModelCategory is just
	 * Category.
	 *
	 * @access	public
	 * @param	object	$model		The model to add to the view.
	 * @param	boolean	$default	Is this the default model?
	 * @return	object				The added model
	 */
	function &setModel( &$model, $default = false )
	{
		$name = strtolower(get_class($model));
		$this->_models[$name] = &$model;
		
		if ($default) {
			$this->_defaultModel = $name;
		}
		return $model;
	}

	/**
	 * Method to get the model object
	 *
	 * @access	public
	 * @param	string	$name	The name of the model (optional)
	 * @return	mixed			JModel object
	 */
	function &getModel( $name = null )
	{
		if ($name === null) {
			$name = $this->_defaultModel;
		}
		return $this->_models[strtolower( $name )];
	}
	
	/**
	* Sets the layout name to use
	*
	* @access public
	* @param string $template The template name.
	*/
	
	function setLayout($layout) {
		$this->_layout = $layout;
	}
	
	/**
	* Get the layout.
	*
	* @access public
	* @return string The layout name
	*/
	
	function getLayout() {
		return $this->_layout;
	}
	
	/**
	* Sets an entire array of search paths for templates or resources.
	*
	* @access public
	* @param string $type The type of path to set, typically 'template'.
	* @param string|array $path The new set of search paths.  If null or
	* false, resets to the current directory only.
	*/
	function setPath($type, $path)
	{
		// clear out the prior search dirs
		$this->_path[$type] = array();
			
		// actually add the user-specified directories
		$this->addPath($type, $path);
	}
	
   /**
	* Gets the array of search directories for template sources.
	*
	* @access public
	* @return array The array of search directories for view sources.
	*/
	function getPath($type = null)
	{
		if (! $type) {
			return $this->_path;
		} else {
			return $this->_path[$type];
		}
	}
	
   /**
	* Adds to the search path for templates and resources.
	*
	* @access public
	* @param string|array $path The directory or stream to search.
	*/
	function addPath($type, $path)
	{
		// convert from path string to array of directories
		if (is_string($path) && ! strpos($path, '://')) 
		{
			// the path config is a string, and it's not a stream
			// identifier (the "://" piece). add it as a path string.
			$path = explode(PATH_SEPARATOR, $path);
			
			// typically in path strings, the first one is expected
			// to be searched first. however, JView uses a stack,
			// so the first would be last.  reverse the path string
			// so that it behaves as expected with path strings.
			$path = array_reverse($path);
		} 
		else 
		{
			// just force to array
			settype($path, 'array');	
		}
		
		// loop through the path directories
		foreach ($path as $dir) 
		{
			// no surrounding spaces allowed!
			$dir = trim($dir);
			
			// add trailing separators as needed
			if (strpos($dir, '://') && substr($dir, -1) != '/') {
				// stream
				$dir .= '/';
			} elseif (substr($dir, -1) != DIRECTORY_SEPARATOR) {
				// directory
				$dir .= DIRECTORY_SEPARATOR;
			}
			
			// add to the top of the search dirs
			array_unshift($this->_path[$type], $dir);
		}
	}
	
	/**
	 * Load a template file -- first look in the templates folder for an override
	 *
	 * @access	protected
	 * @param string $_tpl The name of the template source file ...
	 * automatically searches the template paths and compiles as needed.
	 * @return string The output of the the template script.
	 */
	function loadTemplate( $tpl = null)
	{
		// clear prior output
		$this->_output = null;
		
		//create the template file name based on the layout
		$file = isset($tpl) ? $this->_layout.'_'.$tpl : $this->_layout;
		
		// load the template script
		$this->_template = $this->_findFile('template', $file.'.php');
		
		// unset so as not to introduce into template scope
		unset($tpl);
		unset($file);
		
		// never allow a 'this' property
		if (isset($this->this)) {
			unset($this->this);
		}
		
		// extract references to this object's public properties.
		// this allows variables assigned by-reference to refer all
		// the way back to the model logic.  variables assigned
		// by-copy only refer back to the property.
		foreach (array_keys(get_object_vars($this)) as $_prop) {
			if (substr($_prop, 0, 1) != '_') {
				// set a variable-variable to an object property
				// reference
				$$_prop =& $this->$_prop;
			}
		}
		
		// unset private loop vars
		unset($_prop);
		
		// start capturing output into a buffer
		ob_start();
		// include the requested template filename in the local scope
		// (this will execute the view logic).
		include $this->_template;
		
		// done with the requested template; get the buffer and 
		// clear it.
		$this->_output = ob_get_contents();
		ob_end_clean();
		
		return $this->_output;
	}
	
   /**
	* Searches the directory paths for a given file.
	* 
	* @access protected
	* @param array $type The type of path to search (template or resource).
	* @param string $file The file name to look for.
	* 
	* @return string|bool The full path and file name for the target file,
	* or boolean false if the file is not found in any of the paths.
	*/
	function _findFile($type, $file)
	{
		// get the set of paths
		$set = $this->_path[$type];
		
		// start looping through the path set
		foreach ($set as $path) 
		{	
			// get the path to the file
			$fullname = $path . $file;
			
			// is the path based on a stream?
			if (strpos($path, '://') === false) {
				// not a stream, so do a realpath() to avoid directory 
				// traversal attempts on the local file system.
				$path = realpath($path); // needed for substr() later
				$fullname = realpath($fullname);
			}
			
			// the substr() check added to make sure that the realpath() 
			// results in a directory registered with Savant so that 
			// non-registered directores are not accessible via directory 
			// traversal attempts.
			if (file_exists($fullname) && is_readable($fullname) &&
				substr($fullname, 0, strlen($path)) == $path) {
				return $fullname;
			}
		}
		
		// could not find the file in the set of paths
		return false;
	}
}
?>