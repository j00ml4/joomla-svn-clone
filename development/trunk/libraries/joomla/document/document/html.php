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

/**
 * DocumentHTML class, provides an easy interface to parse and display an html document
 *
 * @author		Johan Janssens <johan.janssens@joomla.org>
 * @package		Joomla.Framework
 * @subpackage	Document
 * @since		1.5
 */

class JDocumentHTML extends JDocument
{
	/**
     * Contains the base url
     *
     * @var     string
     * @access  private
     */
    var $_base = '';

	 /**
     * Array of Header <link> tags
     *
     * @var     array
     * @access  private
     */
    var $_links = array();

	/**
     * Array of custom tags
     *
     * @var     string
     * @access  private
     */
    var $_custom = array();

	/**
     * Array of renderers
     *
     * @var       array
     * @access    private
     */
	var $_renderers = array();

	/**
	 * Class constructore
	 *
	 * @access protected
	 * @param	string	$type 		(either html or tex)
	 * @param	array	$attributes Associative array of attributes
	 */
	function __construct($attributes = array())
	{
		parent::__construct($attributes);

		if (isset($attributes['base'])) {
            $this->setBase($attributes['base']);
        }
		
		$this->_engine =& JTemplate::getInstance();
		
		//set the namespace
		$this->_engine->setNamespace( 'jdoc' );
		
			//add module directories
		$this->_engine->addModuleDir('Function'    , dirname(__FILE__). '/../module'. DS .'function');
		$this->_engine->addModuleDir('OutputFilter', dirname(__FILE__). '/../module'. DS .'filter'  );
		$this->_engine->addModuleDir('Renderer'    , dirname(__FILE__). '/../module'. DS .'renderer');

		//set mime type
		$this->_mime = 'text/html';

		//define renderer sequence
		$this->_renderers = array('component' => array(),
		                          'modules'   => array(),
		                          'module'    => array(),
		                          'head'      => array()
							);

		//set default document metadata
		 $this->setMetaData('Content-Type', $this->_mime . '; charset=' . $this->_charset , true );
		 $this->setMetaData('robots', 'index, follow' );
	}

	 /**
     * Adds <link> tags to the head of the document
     *
     * <p>$relType defaults to 'rel' as it is the most common relation type used.
     * ('rev' refers to reverse relation, 'rel' indicates normal, forward relation.)
     * Typical tag: <link href="index.php" rel="Start"></p>
     *
     * @access   public
     * @param    string  $href       The link that is being related.
     * @param    string  $relation   Relation of link.
     * @param    string  $relType    Relation type attribute.  Either rel or rev (default: 'rel').
     * @param    array   $attributes Associative array of remaining attributes.
     * @return   void
     */
    function addHeadLink($href, $relation, $relType = 'rel', $attribs = array())
	{
        $attribs = JDocumentHelper::implodeAttribs('=', ' ', $attribs);
        $generatedTag = "<link href=\"$href\" $relType=\"$relation\" ". $attribs;
        $this->_links[] = $generatedTag;
    }

	 /**
     * Adds a shortcut icon (favicon)
     *
     * <p>This adds a link to the icon shown in the favorites list or on
     * the left of the url in the address bar. Some browsers display
     * it on the tab, as well.</p>
     *
     * @param     string  $href        The link that is being related.
     * @param     string  $type        File type
     * @param     string  $relation    Relation of link
     * @access    public
     */
    function addFavicon($href, $type = 'image/x-icon', $relation = 'shortcut icon')
	{
        $this->_links[] = "<link href=\"$href\" rel=\"$relation\" type=\"$type\"";
    }

	/**
	 * Adds a custom html string to the head block
	 *
	 * @param string The html to add to the head
	 * @access   public
	 * @return   void
	 */

	function addCustomTag( $html )
	{
		$this->_custom[] = trim( $html );
	}

	 /**
     * Sets the document base tag
     *
     * @param   string   $url  The url used in the base tag
     * @access  public
     * @return  void
     */
    function setBase($url)
	{
        $this->_base = $url;
    }

	/**
     * Returns the document base url
     *
     * @access public
     * @return string
     */
    function getBase()
	{
        return $this->_base;
    }

	/**
	 * Get a renderer, executed the renderer and returns the result
	 *
	 * @access public
	 * @param string 	$type	The type of renderer
	 * @param string 	$name	The name of the element to render
	 * @param array 	$params	Associative array of values
	 * @return 	The output of the renderer
	 */
	function getRenderer($type, $name, $params = array())
	{
		jimport('joomla.document.module.renderer');

		$result = $this->_engine->getVar('document', $type.'_'.$name);;
		
		if($this->_engine->moduleExists('Renderer', ucfirst($type))) 
		{
			$module =& $this->_engine->loadModule( 'Renderer', ucfirst($type));

			if( patErrorManager::isError( $module ) ) {
				return false;
			}

			$result .=  $module->render($name, $params);
		}
		
		if(!$result) {
			$result = " ";
		}

		return $result;
	}
	
	/**
	 * Set a renderer
	 *
	 * @access public
	 * @param string 	$type	The type of renderer
	 * @param string 	$name	The name of the element to render
	 * @param array 	$params	Associative array of values
	 * @return 	The output of the renderer
	 */
	function setRenderer($type, $name, $contents)
	{
		$this->_engine->addVar('document', $type.'_'.$name, $contents);
	}

	/**
	 * Outputs the template to the browser.
	 *
	 * @access public
	 * @param string 	$template	The name of the template
	 * @param boolean 	$compress	If true, compress the output using Zlib compression
	 * @param boolean 	$compress	If true, will display information about the placeholders
	 */
	function display( $template, $file, $compress = false, $params = array())
	{
		// check
		$directory = isset($params['directory']) ? $params['directory'] : 'templates';

		if ( !file_exists( $directory.DS.$template.DS.$file) ) {
			$template = '_system';
		}
		
		//Add template variables
		$this->_engine->addVar('document', 'template', $template);
		
		$this->_engine->addVar( 'document', 'lang_tag', $this->getLanguage() );
		$this->_engine->addVar( 'document', 'lang_dir', $this->getDirection() );

		// parse
		$this->_parse($directory.DS.$template, $file);

		// render
		$this->_render($params);
	
		//output
		header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate' );
		header( 'Cache-Control: post-check=0, pre-check=0', false );		// HTTP/1.5
		header( 'Pragma: no-cache' );										// HTTP/1.0

		if($compress) {
			$this->_engine->applyOutputFilter('Zlib');
		}
		
		parent::display( $template, $file, $compress, $params );
		
		$this->_engine->display('document');
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
			?><jdoc:tmpl name="document" autoclear="yes" unusedvars="ignore"><?php
				require_once($directory.DS.$filename );
			?></jdoc:tmpl><?php
			$contents = ob_get_contents();
			ob_end_clean();
		}

		// Add the option variable to the template
		$this->_engine->addVar('document', 'option', $option);

		return $contents;
	}
	
	/**
	 * Parse a document template
	 *
	 * @access public
	 * @param string 	$directory	The template directory
	 * @param string 	$file 		The actual template file
	 */
	function _parse($directory, $file = 'index.php')
	{
		global $mainframe;

		$contents = $this->_load( $directory, $file);
		$this->_engine->readTemplatesFromInput( $contents, 'String' );

		/*
		 * Parse the template INI file if it exists for parameters and insert
		 * them into the template.
		 */
		if (is_readable( $directory.DS.'params.ini' ) ) {
			$content = file_get_contents($directory.DS.'params.ini');
			$params = new JParameter($content);
			$this->_engine->addVars( 'document', $params->toArray(), 'param_');
		}

		/*
		 * Try to find a favicon by checking the template and root folder
		 */
		$path = $directory .'/';
		$dirs = array( $path, '' );
		foreach ($dirs as $dir ) {
			$icon =   $dir . 'favicon.ico';

			if(file_exists( JPATH_SITE .'/'. $icon )) {
				$this->addFavicon( $icon);
				break;
			}
		}
	}
	
	/**
	 * Render the document
	 *
	 * @access private
	 */
	function _render(&$params)
	{
		foreach($this->_renderers as $type => $names)
		{
			foreach($names as $name)
			{
				if($html = $this->getRenderer($type, $name, $params)) {
					$this->_engine->addVar('document', $type.'_'.$name, $html);
				} 
			}
		}
	}

	/**
	 * Adds a renderer to be called
	 *
	 * @param string 	$type	The renderer type
	 * @param string 	$name	The renderer name
	 * @return string The contents of the template
	 */
	function _addRenderer($type, $name)  {	
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
		$stat	=	&$this->_engine->loadModule( 'Stat', 'File' );
		$stat->setOptions( $options );

		/**
		 * get modification time
		 */
		$modTime   = $stat->getModificationTime( $this->_file );
		$templates = $this->_engine->_tmplCache->load( $key, $modTime );

		return $templates;
	}
}
?>