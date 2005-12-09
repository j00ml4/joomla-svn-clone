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

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JPATH_BASE . '/includes/template.html.php' );

jimport('joomla.classes.object');

/**
 * Layout class, provides an easy interface to parse and display a layout file
 *
 * The class is closely coupled with the patTemplate placeholder function.
 *
 * @author Johan Janssens <johan@joomla.be>
 * @package Joomla
 * @subpackage JFramework
 * @since 1.1
 */

class JLayout extends JObject
{
	/**
     * Array of placholders
     *
     * @var       array
     * @access    private
     */
	var $_placeholders = array();

	/**
     * Array of published modules
     *
     * @var       array
     * @access    private
     */
	var $_modules      = array();

	/**
     * The patTemplate object
     *
     * @var       object
     * @access    private
     */
	var $_tmpl		   = null;

	/**
	 * Create a layout instance (Constructor).
	 */
	function __construct()
	{
		$this->_placeholders['module']		= array();
		$this->_placeholders['modules']		= array();
		$this->_placeholders['components']	= array();

		$this->_modules =& $this->_loadModules();
	}

	/**
	 * Returns a reference to the global JLayout object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		<pre>  $layout = &JLayout::getInstance();</pre>
	 *
	 * @access public
	 * @return JLayout  The Layout object.
	 */
	function &getInstance()
	{
		static $instances;

		if (!isset($instances)) {
			$instances = array();
		}

		if (empty($instances[0])) {
			$instances[0] = new JLayout();
		}

		return $instances[0];
	}

	/**
	 *  Set a component
	 *
	 * @access public
	 * @param string $name	The name of the component
	 */
	function setComponent($name) {
		$this->_placeholders['components'][] = $name;
	}

	/**
	 *  Set a module by name
	 *
	 * @access public
	 * @param string 	$name	The name of the module
	 * @param array  	$params	An associative array of attributes to add
	 */
	function setModule($name, $params = array())
	{
		$module =& $this->getModule($name);

		foreach($params as $param => $value) {
			$module->$param = $value;
		}
		$this->_placeholders['module'][] = $name;
	}

	/**
	 * Set modules by position
	 *
	 * @access public
	 * @param string 	$name	The position of the modules
	 * @param array  	$params	An associative array of attributes to add
	 */
	function setModules($position, $params = array())
	{
		$modules =& $this->getModules($position);

		$total = count($modules);
		for($i = 0; $i < $total; $i++) {
			foreach($params as $param => $value) {
				$modules[$i]->$param = $value;
			}
		}
		$this->_placeholders['modules'][] = $position;
	}

	/**
	 * Get module by name
	 *
	 * @access public
	 * @param string 	$name	The name of the module
	 * @return object	The Module object
	 */
	function &getModule($name) {

		$result = null;

		$total = count($this->_modules);
		for($i = 0; $i < $total; $i++) {
			if($this->_modules[$i]->name == $name) {
				$result =& $this->_modules[$i];
				break;
			}
		}

		return $result;
	}

	/**
	 * Get modules by position
	 *
	 * @access public
	 * @param string 	$position	The position of the module
	 * @return array	An array of module objects
	 */
	function &getModules($position)
	{
		$result = array();

		$total = count($this->_modules);
		for($i = 0; $i < $total; $i++) {
			if($this->_modules[$i]->position == $position) {
				$result[] =& $this->_modules[$i];
			}
		}

		return $result;
	}

	/**
	 * Executes a component script and returns the results as a string.
	 *
	 * @access public
	 * @param string 	$name		The name of the component to render
	 * @param string 	$message	A message to prepend
	 * @return string	The output of the script
	 */
	function fetchComponent($name, $msg = '')
	{
		global $mainframe, $my, $acl, $database;
		global $Itemid, $task, $option;
		global $mosConfig_offset;

		$gid = $my->gid;

		$content = '';
		ob_start();

		if (!empty($msg)) {
			echo "\n<div class=\"message\">$msg</div>";
		}

		if ($path = $mainframe->getPath( 'front', $name )) {
			$task 	= mosGetParam( $_REQUEST, 'task', '' );
			$ret 	= mosMenuCheck( $Itemid, $name, $task, $my->gid );
			if ($ret) {
				require_once( $path );
			} else {
				mosNotAuth();
			}
		}
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}

	/**
	 * Executes multiple modules scripts and returns the results as a string.
	 *
	 * @access public
	 * @param string 	$name	The position of the modules to render
	 * @return string	The output of the scripts
	 */
	function fetchModules($position)
	{
		$contents = '';
		foreach ($this->getModules($position) as $module)  {
			$contents .= $this->fetchModule($module);
		}
		return $contents;
	}

	/**
	 * Executes a single module script and returns the results as a string.
	 *
	 * @access public
	 * @param  mixed 	$name	The name of the module to render or a module object
	 * @return string	The output of the script
	 */
	function fetchModule($module)
	{
		global $mosConfig_live_site, $mosConfig_sitename, $mosConfig_lang, $mosConfig_absolute_path;
		global $mainframe, $database, $my, $Itemid;

		$contents = '';

		if(!is_object($module)) {
			$module = $this->getModule($module);
		}

		//get module parameters
		$params = new mosParameters( $module->params );

		//get module path
		$path = JPATH_SITE . '/modules/'.$module->module.'.php';

		//load the module
		if (!$module->user && file_exists( $path ))
		{
			$lang =& $mainframe->getLanguage();
			$lang->load($module->module);

			ob_start();
			require $path;
			$module->content = ob_get_contents();
			ob_end_clean();
		}

		ob_start();
			if ($params->get('cache') == 1 && $mainframe->getCfg('caching') == 1) {
				$cache =& JFactory::getCache( 'com_content' );
				$cache->call('modules_html::module', $module, $params, $module->style );
			} else {
				modules_html::module( $module, $params, $module->style );
			}
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}

	/**
	 * Renders the page head and returns the results as a string.
	 *
	 * @access public
	 * @return string	The document head
	 */
	function fetchHead()
	{
		global $database, $my, $mainframe, $_VERSION;

		$page =& $mainframe->getPage();

		$page->setMetaContentType();
		$page->setMetaData( 'description', $mainframe->getCfg('MetaDesc' ));
		$page->setMetaData( 'keywords', $mainframe->getCfg('MetaKeys' ));

		$page->setMetaData( 'Generator', $_VERSION->PRODUCT . " - " . $_VERSION->COPYRIGHT);
		$page->setMetaData( 'robots', 'index, follow' );

		if ( $mainframe->getCfg('sef') ) {
			$page->addCustomTag( '<base href="'. JURL_SITE. '" />' );
		}

		if ( $my->id ) {
			$page->addScript( JURL_SITE.'/includes/js/joomla.javascript.js');
		}

		// support for Firefox Live Bookmarks ability for site syndication
		$query = "SELECT a.id"
		. "\n FROM #__components AS a"
		. "\n WHERE a.name = 'Syndicate'"
		;
		$database->setQuery( $query );
		$id = $database->loadResult();

		// load the row from the db table
		$row = new mosComponent( $database );
		$row->load( $id );

		// get params definitions
		$params = new mosParameters( $row->params, $mainframe->getPath( 'com_xml', $row->option ), 'component' );

		$live_bookmark = $params->get( 'live_bookmark', 0 );

		// support for Live Bookmarks ability for site syndication
		if ($live_bookmark) {
			$show = 1;

			$link_file 	= JURL_SITE . '/index2.php?option=com_rss&feed='. $live_bookmark .'&no_html=1';

			// xhtml check
			$link_file = ampReplace( $link_file );

			// outputs link tag for page
			if ($show) {
				$page->addHeadLink( $link_file, 'alternate', array('type' => 'application/rss+xml'));
			}
		}

		$dirs = array(
			'/templates/'.@$template.'/',
			'/',
		);

		foreach ($dirs as $dir ) {
			$icon =   $dir . 'favicon.ico';

			if(file_exists( JPATH_SITE . $icon )) {
				$page->addFavicon(JURL_SITE . '/administrator'. $icon);
				break;
			}
		}

		ob_start();
		echo $page->renderHead();

		//load editor
		initEditor();

		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}

	/**
	 * Parse a file and create an internal patTemplate object
	 *
	 * @access public
	 * @param string 	$directory	The directory to look for the file
	 * @param string 	$filename	The actual filename
	 */
	function parse($directory, $filename = 'index.php')
	{
		if ( !file_exists( 'templates'.DS.$directory.DS.$filename) ) {
			$directory = '_system';
		}

		$this->_tmpl =& $this->_loadTemplate($directory, $filename);
	}

	/**
	 * Execute and display a layout script.
	 *
	 * @access public
	 * @param string 	$name	The name of the template
	 */
	function display($name)
	{
		$msg = mosGetParam( $_REQUEST, 'mosmsg', '' );

		foreach($this->_placeholders['components'] as $component)
		{
			$html = $this->fetchComponent($component, $msg);
			$this->_tmpl->addGlobalVar('component_'.$component, $html);
		}

		foreach($this->_placeholders['modules'] as $module)
		{
			$html = $this->fetchModules($module);
			$this->_tmpl->addGlobalVar('modules_'.$module, $html);
		}

		foreach($this->_placeholders['module'] as $module)
		{
			$html = $this->fetchModule($module);
			$this->_tmpl->addGlobalVar('module_'.$module, $html);
		}

		$html = $this->fetchHead();
		$this->_tmpl->addGlobalVar('head', $html);

		$this->_tmpl->displayParsedTemplate( $name );
	}

	/**
	 * Load published modules
	 *
	 * @access private
	 * @return array
	 */
	function &_loadModules() {
		global $database, $my, $Itemid;

		$modules = array();

		$query = "SELECT id, title, module, position, content, showtitle, params"
			. "\n FROM #__modules AS m, #__modules_menu AS mm"
			. "\n WHERE m.published = 1"
			. "\n AND m.access <= '". $my->gid ."'"
			. "\n AND m.client_id != 1"
			. "\n AND mm.moduleid = m.id"
			. "\n AND ( mm.menuid = '". $Itemid ."' OR mm.menuid = 0 )"
			. "\n ORDER BY position, ordering";

		$database->setQuery( $query );
		$modules = $database->loadObjectList();

		$total = count($modules);
		for($i = 0; $i < $total; $i++) {
			//determine if this is a user module
			$file = $modules[$i]->module;
			$modules[$i]->user = substr( $file, 0, 4 )  == 'mod_' ?  0 : 1;
			$modules[$i]->name = substr( $file, 4 );
		}

		return $modules;
	}

	/**
	 * Create a patTemplate object
	 *
	 * @param string 	$directory	The directory to look for the file
	 * @param string 	$filename	The actual filename
	 * @return patTemplate
	 */
	function &_loadTemplate($directory, $file) {

		global $mainframe, $my, $acl, $database;
		global $Itemid, $task;

		$tmpl = null;
		if ( file_exists( 'templates'.DS.$directory.DS.$file ) ) {

			jimport('pattemplate.patTemplate');

			$tmpl = new patTemplate;
			$tmpl->setNamespace( 'jos' );

			ob_start();
			?><jos:tmpl name="<?php echo $file ?>" autoclear="yes"><?php
				require_once( 'templates'.DS.$directory.DS.$file );
			?></jos:tmpl><?php
			$contents = ob_get_contents();
			ob_end_clean();

			$tmpl->readTemplatesFromInput( $contents, 'String' );
		}

		return $tmpl;
	}
}

/**
 * Get the number of modules loaded for a particular template position
 *
 * @param 	string 	The mdoule position
 * @return 	integer The number of modules loaded for that position
 */
function mosCountModules( $position='left' ) {

	global $layout;
	return count($layout->getModules($position));
}

/**
 * Insert a component placeholder (uses the option request parameter)
 */
function mosMainBody()
{
	global $option;

	?>
	<jos:placeholder type="component" name="<?php echo $option ?>"/>
	<?php
}
/**
 * Insert a component placeholder
 */
function mosLoadComponent( $name )
{
	?>
	<jos:placeholder type="component" name="<?php echo $name ?>" />
	<?php
}

/**
 * Insert a modules placholder
 *
 * @param string 	The position of the modules
 * @param integer 	The style.  0=normal, 1=horiz, -1=no wrapper
 */
function mosLoadModules( $position='left', $style=0 )
{
	?>
	<jos:placeholder type="modules" position="<?php echo $position ?>" style="<?php echo $style ?>"/>
	<?php
}

/**
 * Insert a module placholder
 *
 * @param string 	The name of the module
 * @param integer 	The style.  0=normal, 1=horiz, -1=no wrapper
 */
function mosLoadModule( $name, $style=-1 )
{
	?>
	<jos:placeholder type="module" name="<?php echo $name ?>" style="<?php echo $style ?>" />
	<?php
}

/**
* Insert a head placeholder
*/
function mosShowHead()
{
	?>
	<jos:placeholder type="head" />
	<?php
}
?>