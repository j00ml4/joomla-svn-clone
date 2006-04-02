<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

jimport('joomla.template.template');
jimport('joomla.application.extension.module');

/**
 * Document class, provides an easy interface to parse and display a document
 *
 * @abstract
 * @author		Johan Janssens <johan.janssens@joomla.org>
 * @package		Joomla.Framework
 * @subpackage	Document
 * @since		1.1
 * @see			patTemplate
 */

class JDocument extends JTemplate
{
	/**
     * Tab string
     *
     * @var       string
     * @access    private
     */
    var $_tab = "\11";

    /**
     * Contains the line end string
     *
     * @var       string
     * @access    private
     */
    var $_lineEnd = "\12";

	/**
     * Contains the character encoding string
     *
     * @var     string
     * @access  private
     */
    var $_charset = 'utf-8';

    /**
     * Contains the page language setting
     *
     * @var     string
     * @access  private
     */
    var $_language = 'en';

	/**
     * Document mime type
     *
     * @var      string
     * @access   private
     */
    var $_mime = '';

	/**
     * Document namespace
     *
     * @var      string
     * @access   private
     */
    var $_namespace = '';

    /**
     * Document profile
     *
     * @var      string
     * @access   private
     */
    var $_profile = '';

    /**
     * Array of linked scripts
     *
     * @var      array
     * @access   private
     */
    var $_scripts = array();

	/**
     * Array of scripts placed in the header
     *
     * @var  array
     * @access   private
     */
    var $_script = array();

	 /**
     * Array of linked style sheets
     *
     * @var     array
     * @access  private
     */
    var $_styleSheets = array();

	/**
     * Array of included style declarations
     *
     * @var     array
     * @access  private
     */
    var $_style = array();

	/**
     * Page title
     *
     * @var     string
     * @access  private
     */
    var $_title = '';
	
	/**
     * Array of renderers
     *
     * @var       array
     * @access    private
     */
	var $_renderers = array();
	

	/**
	* Class constructor
	* 
	* @access protected
	* @param  string	$type (either html or tex)
	* @param	array	$attributes Associative array of attributes
	* @see JDocument
	*/
	function __construct($type, $attributes = array())
	{
		parent::__construct($type);
		
		if (isset($attributes['lineend'])) {
            $this->setLineEnd($attributes['lineend']);
        }

        if (isset($attributes['charset'])) {
            $this->setCharset($attributes['charset']);
        }

        if (isset($attributes['language'])) {
            $this->setLang($attributes['language']);
        }

        if (isset($attributes['tab'])) {
            $this->setTab($attributes['tab']);
        }
		
		//set the namespace
		$this->setNamespace( 'jdoc' );
		
		//add module directories
		$this->addModuleDir('Function'    ,	dirname(__FILE__). DS. 'module'. DS .'function');
		$this->addModuleDir('OutputFilter', dirname(__FILE__). DS. 'module'. DS .'filter'  );
		$this->addModuleDir('Renderer'    , dirname(__FILE__). DS. 'module'. DS .'renderer');
		
	}

	/**
	 * Returns a reference to the global JDocument object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		<pre>  $document = &JDocument::getInstance();</pre>
	 *
	 * @param type $type The document type to instantiate
	 * @access public
	 * @return jdocument  The document object.
	 */
	function &getInstance($type = 'html', $attributes = array())
	{
		static $instances;
		
		if (!isset( $instances )) {
			$instances = array();
		}
		
		$signature = serialize(array($type, $attributes));

		if (empty($instances[$signature])) {
			jimport('joomla.document.document.'.$type);
			$adapter = 'JDocument'.$type;
			$instances[$signature] = new $adapter($type, $attributes);
		}

		return $instances[$signature];
	}

	 /**
     * Adds a linked script to the page
     *
     * @param    string  $url        URL to the linked script
     * @param    string  $type       Type of script. Defaults to 'text/javascript'
     * @access   public
     */
    function addScript($url, $type="text/javascript")
    {
        $this->_scripts[$url] = $type;
    }

	/**
     * Adds a script to the page
     *
     * @access   public
     * @param    string  $content   Script
     * @param    string  $type      Scripting mime (defaults to 'text/javascript')
     * @return   void
     */
    function addScriptDeclaration($content, $type = 'text/javascript')
    {
        $this->_script[][strtolower($type)] =& $content;
    }

	/**
     * Adds a linked stylesheet to the page
     *
     * @param    string  $url    URL to the linked style sheet
     * @param    string  $type   Mime encoding type
     * @param    string  $media  Media type that this stylesheet applies to
     * @access   public
     */
    function addStyleSheet($url, $type = 'text/css', $media = null, $attribs = array())
    {
        $this->_styleSheets[$url]['mime']    = $type;
        $this->_styleSheets[$url]['media']   = $media;
		$this->_styleSheets[$url]['attribs'] = $attribs;
    }

	 /**
     * Adds a stylesheet declaration to the page
     *
     * @param    string  $content   Style declarations
     * @param    string  $type      Type of stylesheet (defaults to 'text/css')
     * @access   public
     * @return   void
     */
    function addStyleDeclaration($content, $type = 'text/css')
    {
        $this->_style[][strtolower($type)] = $content;
    }

	 /**
     * Sets the document charset
     *
     * @param   string   $type  Charset encoding string
     * @access  public
     * @return  void
     */
    function setCharset($type = 'utf-8')
	{
        $this->_charset = $type;
    }

	/**
     * Returns the document charset encoding.
     *
     * @access public
     * @return string
     */
    function getCharset()
	{
        return $this->_charset;
    }

	/**
     * Sets the global document language declaration. Default is English.
     *
     * @access public
     * @param   string   $lang
     */
    function setLang($lang = "en-GB")
	{
        $this->_language = strtolower($lang);
    }

	/**
     * Returns the document language.
     *
     * @return string
     * @access public
     */
    function getLang()
	{
        return $this->_language;
    }

	/**
     * Sets the title of the page
     *
     * @param    string    $title
     * @access   public
     */
    function setTitle($title) {
		$this->_title = $title;
    }

	/**
     * Return the title of the page.
     *
     * @return   string
     * @access   public
     */
    function getTitle() {
        return $this->_title;
    }

	 /**
     * Sets the document MIME encoding that is sent to the browser.
     *
     * <p>This usually will be text/html because most browsers cannot yet
     * accept the proper mime settings for XHTML: application/xhtml+xml
     * and to a lesser extent application/xml and text/xml. See the W3C note
     * ({@link http://www.w3.org/TR/xhtml-media-types/
     * http://www.w3.org/TR/xhtml-media-types/}) for more details.</p>
     *
     * @param    string    $type
     * @access   public
     * @return   void
     */
    function setMimeEncoding($type = 'text/html') {
        $this->_mime = strtolower($type);
    }

	 /**
     * Sets the line end style to Windows, Mac, Unix or a custom string.
     *
     * @param   string  $style  "win", "mac", "unix" or custom string.
     * @access  public
     * @return  void
     */
    function setLineEnd($style)
    {
        switch ($style) {
            case 'win':
                $this->_lineEnd = "\15\12";
                break;
            case 'unix':
                $this->_lineEnd = "\12";
                break;
            case 'mac':
                $this->_lineEnd = "\15";
                break;
            default:
                $this->_lineEnd = $style;
        }
    }

	/**
     * Returns the lineEnd
     *
     * @access    private
     * @return    string
     */
    function _getLineEnd()
    {
        return $this->_lineEnd;
    }

	/**
     * Sets the string used to indent HTML
     *
     * @param     string    $string     String used to indent ("\11", "\t", '  ', etc.).
     * @access    public
     * @return    void
     */
    function setTab($string)
    {
        $this->_tab = $string;
    }

	 /**
     * Returns a string containing the unit for indenting HTML
     *
     * @access    private
     * @return    string
     */
    function _getTab()
    {
        return $this->_tab;
    }
	
	/**
	 * Execute a renderer
	 *
	 * @access public
	 * @param string 	$type	The type of renderer
	 * @param string 	$name	The name of the element to render
	 * @param array 	$params	Associative array of values
	 * @return 	The output of the renderer
	 */
	function execRenderer($type, $name, $params = array()) 
	{
		jimport('joomla.document.module.renderer');
		
		if(!$this->moduleExists('Renderer', ucfirst($type))) {
			return false;
		}
		
		$module =& $this->loadModule( 'Renderer', ucfirst($type));
		
		if( patErrorManager::isError( $module ) ) {
			return false;
		}
		
		return $module->render($name, $params);
	}

	/**
	 * Parse a file and create an internal patTemplate object
	 *
	 * @access public
	 * @param string 	$template	The template to look for the file
	 * @param string 	$filename	The actual filename
	 * @param string 	$directory	The directory to look for the template
	 */
	function parse($template, $filename = 'index.php', $directory = 'templates')
	{
		$contents = $this->_load($directory.DS.$template, $filename);
		$this->readTemplatesFromInput( $contents, 'String' );
		
		/*
		 * Parse the template INI file if it exists for parameters and insert
		 * them into the template.
		 */
		if (is_readable( $directory.DS.$template.DS.'params.ini' ) ) {
			$content = file_get_contents($directory.DS.$template.DS.'params.ini');
			$params = new JParameter($content);
			$this->addVars( $filename, $params->toArray(), 'param_');
		}
	}
	
	/**
	 * Outputs the template to the browser.
	 *
	 * @access public
	 * @param string 	$template	The name of the template
	 * @param boolean 	$compress	If true, compress the output using Zlib compression
	 * @param boolean 	$compress	If true, will display information about the placeholders
	 */
	function display($template, $compress = false, $outline = false)
	{
		foreach($this->_renderers as $type => $names) 
		{
			foreach($names as $name) 
			{
				if($html = $this->execRenderer($type, $name, array('outline' => $outline))) {
					$this->addGlobalVar($type.'_'.$name, $html);
				}
			}
		}

		if($compress) {
			$this->applyOutputFilter('Zlib');
		}
		
		// Set mime type and character encoding
        header('Content-Type: ' . $this->_mime .  '; charset=' . $this->_charset);

		parent::display( $template );
	}

	/**
     * Return the document head
     *
     * @abstract
     * @access public
     * @return string
     */
    function fetchHead() {
		return '';
    }

	/**
     * Return the document body
     *
     * @abstract
     * @access public
     * @return string
     */
    function fetchBody() {
		return '';
    }

	/**
	 * Load a template file
	 *
	 * @param string 	$template	The name of the template
	 * @param string 	$filename	The actual filename
	 * @return string The contents of the template 
	 */
	function _load($directory, $filename)
	{
		global $mainframe, $my, $acl, $database;
		global $Itemid, $task, $option, $_VERSION;
		
		//For backwards compatibility extract the config vars as globals
		foreach (get_object_vars($mainframe->_registry->toObject()) as $k => $v) {
			$name = 'mosConfig_'.$k;
			$$name = $v;
		}
		
		$contents = '';
		//Check to see if we have a valid template file
		if ( file_exists( $directory.DS.$filename ) ) 
		{
			//store the file path 
			$this->_file = $directory.DS.$filename;
			
			//get the file content
			ob_start();
			?><jdoc:tmpl name="<?php echo $filename ?>" autoclear="yes"><?php
				require_once( $directory.DS.$filename );
			?></jdoc:tmpl><?php
			$contents = ob_get_contents();
			ob_end_clean();
		}
		
		return $contents;
	}
	
	/**
	 * Adds a renderer to be called 
	 *
	 * @param string 	$type	The renderer type
	 * @param string 	$name	The renderer name
	 * @return string The contents of the template 
	 */
	function _addRenderer($type, $name) {
		$this->_renderers[$type][] = $name;
	}
	
	 /**
	* load from template cache
	*
	* @access	private
	* @param	string	name of the input (filename, shm segment, etc.)
	* @param	string	driver that is used as reader, you may also pass a Reader object
	* @param	array	options for the reader
	* @param	string	cache key
	* @return	array|boolean	either an array containing the templates, or false
	*/
	function _loadTemplatesFromCache( $input, &$reader, $options, $key )
	{
		$stat	=	&$this->loadModule( 'Stat', 'File' );
		$stat->setOptions( $options );

		/**
		 * get modification time
		 */
		$modTime   = $stat->getModificationTime( $this->_file );
		$templates = $this->_tmplCache->load( $key, $modTime );
		
		return $templates;
	}
}

/**
 * Document helper functions
 * 
 * @static
 * @author		Johan Janssens <johan.janssens@joomla.org>
 * @package		Joomla.Framework
 * @subpackage	Document
 * @since		1.1
 */
 class JDocumentHelper 
 {
	function implodeAttribs($inner_glue = "=", $outer_glue = "\n", $array = null, $keepOuterKey = false)
    {
        $output = array();

        foreach($array as $key => $item)
        if (is_array ($item)) {
            if ($keepOuterKey)
                $output[] = $key;
            // This is value is an array, go and do it again!
            $output[] = implode_assoc($inner_glue, $outer_glue, $item, $keepOuterKey);
        } else
            $output[] = $key . $inner_glue . '"'.$item.'"';

        return implode($outer_glue, $output);
    }
 }
?>