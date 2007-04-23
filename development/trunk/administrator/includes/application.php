<?php
/**
* @version		$Id$
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.helper');

/**
* Joomla! Application class
*
* Provide many supporting API functions
*
* @package		Joomla
* @final
*/
class JAdministrator extends JApplication
{
	/**
	 * The url of the site
	 *
	 * @var string
	 * @access protected
	 */
	var $_siteURL = null;

	/**
	* Class constructor
	*
	* @access protected
	* @param integer A client id
	*/
	function __construct() {
		parent::__construct(1);
	}

   /**
	* Initialise the application.
	*
	* @access public
	* @param array An optional associative array of configuration settings.
	*/
	function initialise($options = array())
	{
		// if a language was specified it has priority
		// otherwise use user or default language settings
		if (empty($options['language'])) {
			$user = & JFactory::getUser();
			$lang	= $user->getParam( 'admin_language' );

			// Make sure that the user's language exists
			if ( $lang && JLanguage::exists($lang) ) {
				$options['language'] = $lang;
			} else {
				$options['language'] = $this->getCfg('lang_administrator');
			}

		}

		// One last check to make sure we have something
		if ( ! JLanguage::exists($options['language']) ) {
			$options['language'] = 'en-GB';
		}

		parent::initialise($options);
	}

	/**
	* Route the application
	*
	* @access public
	*/
	function route()
	{

	}

	/**
	* Dispatch the application
	*
	* @access public
	*/
	function dispatch($component)
	{
		$document	=& JFactory::getDocument();
		$config		=& JFactory::getConfig();
		$user		=& JFactory::getUser();

		switch($document->getType())
		{
			case 'html' :
			{
				$document->setMetaData( 'keywords', 		$this->getCfg('MetaKeys') );
				// TODO NOTE: Here we are checking for Konqueror - If they fix thier issue with compressed, we will need to update this
				$konkcheck = phpversion() <= "4.2.1" ? getenv( "HTTP_USER_AGENT" ) : $_SERVER['HTTP_USER_AGENT'];
				$konkcheck = strpos (strtolower($konkcheck), "konqueror");
				if ($config->getValue('config.debug') || $konkcheck ) {
					$document->addScript( '../includes/js/mootools-uncompressed.js');
				} else {
					$document->addScript( '../includes/js/mootools.js');
				}

				if ( $user->get('id') ) {
					$document->addScript( '../includes/js/joomla.javascript.js');
				}
			} break;

			default : break;
		}

		$document->setTitle( $this->getCfg('sitename' ). ' - ' .JText::_( 'Administration' ));
		$document->setDescription( $this->getCfg('MetaDesc') );

		$contents = JComponentHelper::renderComponent($component);
		$document->setBuffer($contents, 'component');
	}

	/**
	* Display the application.
	*
	* @access public
	*/
	function render()
	{
		$component	= JRequest::getVar( 'option', null, '', 'word' );
		$template	= JRequest::getVar( 'template', $this->getTemplate(), '', 'cmd' );
		$file 		= JRequest::getVar( 'tmpl', 'index',  '', 'cmd' );

		if($component == 'com_login') {
			$file = 'login';
		}

		$params = array(
			'template' 	=> $template,
			'file'		=> $file.'.php',
			'directory'	=> JPATH_THEMES
		);

		$document =& JFactory::getDocument();
		$data = $document->render($this->getCfg('caching'), $params );
		JResponse::setBody($data);
	}

	/**
	* Login authentication function
	*
	* @param string The username
	* @param string The password
	* @access public
	* @see JApplication::login
	*/
	function login($username=null, $password=null, $remember = false)
	{
		$username = trim( JRequest::getVar( 'username', $username, 'post' ) );
		$password = trim( JRequest::getVar( 'passwd', $password, 'post' ) );
		$remember = JRequest::getVar( 'remember', $remember, 'post', 'word' );

		$result = parent::login($username, $password, $remember);

		if(!JError::isError($result))
		{
			$lang = JRequest::getVar( 'lang' );
			$lang = preg_replace( '/[^A-Z-]/i', '', $lang );
			$this->setUserState( 'application.lang', $lang  );

			JAdministrator::purgeMessages();
		}

		return $result;
	}

	/**
	* Logout authentication function
	*
	* @access public
	* @see JApplication::login
	*/
	function logout() {
		return parent::logout();
	}

	/**
	* Set Page Title
	*
	* @param string $title The title for the page
	* @since 1.5
	*/
	function setPageTitle( $title=null )
	{
		$document=& JFactory::getDocument();
		$document->setTitle($title);
	}

	/**
	* Get Page title
	*
	* @return string The page title
	* @since 1.5
	*/
	function getPageTitle()
	{
		$document=& JFactory::getDocument();
		return $document->getTitle();
	}

	/**
	 * Set the configuration
	 *
	 * @access public
	 * @param string	The path to the configuration file
	 * @param string	The type of the configuration file
	 * @since 1.5
	 */
	function loadConfiguration($file, $type = 'config')
	{
		parent::loadConfiguration($file, $type);

		$registry =& JFactory::getConfig();
		$registry->setValue('config.live_site', substr_replace($this->getSiteURL(), '', -1, 1));
		$registry->setValue('config.absolute_path', JPATH_SITE);

		// Create the JConfig object
		$config = new JConfig();

		if ( $config->legacy == 1 )
		{
			//Insert configuration values into global scope (for backwards compatibility)
			foreach (get_object_vars($config) as $k => $v) {
				$name = 'mosConfig_'.$k;
				$GLOBALS[$name] = $v;
			}

			$GLOBALS['mosConfig_live_site']		= substr_replace($this->getSiteURL(), '', -1, 1);
			$GLOBALS['mosConfig_absolute_path']	= JPATH_SITE;
		}
	}

	/**
	* Get the template
	*
	* @return string The template name
	* @since 1.0
	*/
	function getTemplate()
	{
		static $templates;

		if (!isset ($templates))
		{
			$templates = array();

			// Load template entries for each menuid
			$db =& JFactory::getDBO();
			$query = 'SELECT template'
				. ' FROM #__templates_menu'
				. ' WHERE client_id = 1'
				. ' AND menuid = 0'
				;
			$db->setQuery( $query );
			$templates[0] = $db->loadResult();
		}

		$template = preg_replace('/[^A-Z0-9_\.-]/i', '', $templates[0]);

		$path = JPATH_ADMINISTRATOR .'/templates/'.$template.'/index.php';

		if (!file_exists( $path )) {
			$cur_template = 'khepri';
		}

		return $template;
	}

	/**
	* Get the url of the site
	*
	* @return string The site URL
	* @since 1.5
	*/
	function getSiteURL()
	{
		if(isset($this->_siteURL)) {
			return $this->_siteURL;
		}

		$url = JURI::base();
		$url = str_replace('administrator/', '', $url);

		$this->_siteURL = $url;
		return $url;
	}

	/**
	* Purge the jos_messages table of old messages
	*
	* static method
	* @since 1.5
	*/
	function purgeMessages()
	{
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();

		$userid = $user->get('id');

		$query = 'SELECT *'
		. ' FROM #__messages_cfg'
		. ' WHERE user_id = ' . (int) $userid
		. ' AND cfg_name = "auto_purge"'
		;
		$db->setQuery( $query );
		$config = $db->loadObject( );

		// check if auto_purge value set
		if (is_object( $config ) and $config->cfg_name == 'auto_purge' )
		{
			$purge 	= $config->cfg_value;
		}
		else
		{
			// if no value set, default is 7 days
			$purge 	= 7;
		}
		// calculation of past date

		// if purge value is not 0, then allow purging of old messages
		if ($purge > 0)
		{
			// purge old messages at day set in message configuration

			$past = date( 'Y-m-d H:i:s', time() - $purge * 86400 );

			$query = 'DELETE FROM #__messages'
			. ' WHERE date_time < ' . $db->Quote( $past )
			. ' AND user_id_to = ' . (int) $userid
			;
			$db->setQuery( $query );
			$db->query();
		}
	}
}

/**
* Joomla! Administrator Application helper class
*
* Provide many supporting API functions
*
* @package		Joomla
* @final
*/
class JAdministratorHelper
{
	/**
	 * Return the application option string [main component]
	 *
	 * @access public
	 * @return string Option
	 * @since 1.5
	 */
	function findOption()
	{
		$option = strtolower(JRequest::getCmd('option', null, ''));

		$user =& JFactory::getUser();
		if ($user->get('guest')) {
			$option = 'com_login';
		}

		if(empty($option)) {
			$option = 'com_cpanel';
		}

		JRequest::setVar('option', $option);
		return $option;
	}
	
   /**
	* Build the select list for Menu Ordering
	*/
	/*
	 * Function is only used in administrator/component/com_menus/views/item/tmpl/form.php
	 */
	function Ordering( &$row, $id )
	{
		$db =& JFactory::getDBO();

		if ( $id ) {
			$query = 'SELECT ordering AS value, name AS text'
			. ' FROM #__menu'
			. ' WHERE menutype = \''.$row->menutype
			. '\' AND parent = '.$row->parent
			. ' AND published != -2'
			. ' ORDER BY ordering';
			$order = JAdministratorHelper::GenericOrdering( $query );
			$ordering = JHTMLSelect::genericList( $order, 'ordering', 'class="inputbox" size="1"', 'value', 'text', intval( $row->ordering ) );
		} else {
			$ordering = '<input type="hidden" name="ordering" value="'. $row->ordering .'" />'. JText::_( 'DESCNEWITEMSLAST' );
		}
		return $ordering;
	}

   /**
	* Build the select list for access level
	*/
	/*
	 * Function is used in administrator/site (com_content) : move into parameters
	 */
	function Access( &$row )
	{
		$db =& JFactory::getDBO();

		$query = 'SELECT id AS value, name AS text'
		. ' FROM #__groups'
		. ' ORDER BY id'
		;
		$db->setQuery( $query );
		$groups = $db->loadObjectList();
		$access = JHTMLSelect::genericList( $groups, 'access', 'class="inputbox" size="3"', 'value', 'text', intval( $row->access ), '', 1 );

		return $access;
	}

   /**
	* Build the multiple select list for Menu Links/Pages
	*/
	/*
	 * Function is only used in the administrator (multiple components)
	 */
	function MenuLinkOptions( $all=false, $unassigned=false )
	{
		$db =& JFactory::getDBO();

		// get a list of the menu items
		$query = 'SELECT m.id, m.parent, m.name, m.menutype'
		. ' FROM #__menu AS m'
		. ' WHERE m.published = 1'
		. ' ORDER BY m.menutype, m.parent, m.ordering'
		;
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();
		$mitems_temp = $mitems;

		// establish the hierarchy of the menu
		$children = array();
		// first pass - collect children
		foreach ( $mitems as $v )
		{
			$id = $v->id;
			$pt = $v->parent;
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push( $list, $v );
			$children[$pt] = $list;
		}
		// second pass - get an indent list of the items
		$list = mosTreeRecurse( intval( $mitems[0]->parent ), '', array(), $children, 9999, 0, 0 );

		// Code that adds menu name to Display of Page(s)
		$mitems_spacer 	= $mitems_temp[0]->menutype;

		$mitems = array();
		if ($all | $unassigned) {
			$mitems[] = JHTMLSelect::option( '<OPTGROUP>', JText::_( 'Menus' ) );

			if ( $all ) {
				$mitems[] = JHTMLSelect::option( 0, JText::_( 'All' ) );
			}
			if ( $unassigned ) {
				$mitems[] = JHTMLSelect::option( -1, JText::_( 'Unassigned' ) );
			}

			$mitems[] = JHTMLSelect::option( '</OPTGROUP>' );
		}

		$lastMenuType	= null;
		$tmpMenuType	= null;
		foreach ($list as $list_a)
		{
			if ($list_a->menutype != $lastMenuType)
			{
				if ($tmpMenuType) {
					$mitems[] = JHTMLSelect::option( '</OPTGROUP>' );
				}
				$mitems[] = JHTMLSelect::option( '<OPTGROUP>', $list_a->menutype );
				$lastMenuType = $list_a->menutype;
				$tmpMenuType  = $list_a->menutype;
			}

			$mitems[] = JHTMLSelect::option( $list_a->id, $list_a->treename );
		}
		if ($lastMenuType !== null) {
			$mitems[] = JHTMLSelect::option( '</OPTGROUP>' );
		}

		return $mitems;
	}

   /**
	* Build the select list to choose an image
	*/
	/*
	 * Function is only used in the administrator (multiple components) : duplicate with JElementImagelist
	 */
	function Images( $name, &$active, $javascript=NULL, $directory=NULL )
	{
		if ( !$directory ) {
			$directory = '/images/stories/';
		}

		if ( !$javascript ) {
			$javascript = "onchange=\"javascript:if (document.forms[0]." . $name . ".options[selectedIndex].value!='') {document.imagelib.src='..$directory' + document.forms[0]." . $name . ".options[selectedIndex].value} else {document.imagelib.src='../images/blank.png'}\"";
		}

		jimport( 'joomla.filesystem.folder' );
		$imageFiles = JFolder::files( JPATH_SITE.DS.$directory );
		$images 	= array(  JHTMLSelect::option( '', '- '. JText::_( 'Select Image' ) .' -' ) );
		foreach ( $imageFiles as $file ) {
			if ( eregi( "bmp|gif|jpg|png", $file ) ) {
				$images[] = JHTMLSelect::option( $file );
			}
		}
		$images = JHTMLSelect::genericList( $images, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );

		return $images;
	}

	/**
	 * Description
	 *
 	 * @param string SQL with ordering As value and 'name field' AS text
 	 * @param integer The length of the truncated headline
 	 * @since 1.5
 	 */
 	 /*
	 * Function is only used in the administrator (com_plugins)
	 */
	function GenericOrdering( $sql, $chop='30' )
	{
		$db =& JFactory::getDBO();
		$order = array();
		$db->setQuery( $sql );
		if (!($orders = $db->loadObjectList())) {
			if ($db->getErrorNum()) {
				echo $db->stderr();
				return false;
			} else {
				$order[] = JHTMLSelect::option( 1, JText::_( 'first' ) );
				return $order;
			}
		}
		$order[] = JHTMLSelect::option( 0, '0 '. JText::_( 'first' ) );
		for ($i=0, $n=count( $orders ); $i < $n; $i++) {

			if (JString::strlen($orders[$i]->text) > $chop) {
				$text = JString::substr($orders[$i]->text,0,$chop)."...";
			} else {
				$text = $orders[$i]->text;
			}

			$order[] = JHTMLSelect::option( $orders[$i]->value, $orders[$i]->value.' ('.$text.')' );
		}
		$order[] = JHTMLSelect::option( $orders[$i-1]->value+1, ($orders[$i-1]->value+1).' '. JText::_( 'last' ) );

		return $order;
	}

   /**
	* Build the select list for Ordering of a specified Table
	*/
	/*
	 * Function is only used in the administrator (multiple components)
	 */
	function SpecificOrdering( &$row, $id, $query, $neworder=0 )
	{
		$db =& JFactory::getDBO();

		if ( $id ) {
			$order = JAdministratorHelper::GenericOrdering( $query );
			$ordering = JHTMLSelect::genericList( $order, 'ordering', 'class="inputbox" size="1"', 'value', 'text', intval( $row->ordering ) );
		} else {
			if ( $neworder ) {
				$text = JText::_( 'descNewItemsFirst' );
			} else {
				$text = JText::_( 'descNewItemsLast' );
			}
			$ordering = '<input type="hidden" name="ordering" value="'. $row->ordering .'" />'. $text;
		}
		return $ordering;
	}

   /**
	* Select list of active users
	*/
	/*
	 * Function is only used in the administrator (multiple components) : function could create preformance issues
	 */
	function UserSelect( $name, $active, $nouser=0, $javascript=NULL, $order='name', $reg=1 )
	{
		$db =& JFactory::getDBO();

		$and = '';
		if ( $reg ) {
		// does not include registered users in the list
			$and = ' AND gid > 18';
		}

		$query = 'SELECT id AS value, name AS text'
		. ' FROM #__users'
		. ' WHERE block = 0'
		. $and
		. ' ORDER BY '. $order
		;
		$db->setQuery( $query );
		if ( $nouser ) {
			$users[] = JHTMLSelect::option( '0', '- '. JText::_( 'No User' ) .' -' );
			$users = array_merge( $users, $db->loadObjectList() );
		} else {
			$users = $db->loadObjectList();
		}

		$users = JHTMLSelect::genericList( $users, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );

		return $users;
	}

   /**
	* Select list of positions - generally used for location of images
	*/
	/*
	 * Function is only used in the administrator (com_categories, com_sections)
	 */
	function Positions( $name, $active=NULL, $javascript=NULL, $none=1, $center=1, $left=1, $right=1, $id=false )
	{
		if ( $none ) {
			$pos[] = JHTMLSelect::option( '', JText::_( 'None' ) );
		}
		if ( $center ) {
			$pos[] = JHTMLSelect::option( 'center', JText::_( 'Center' ) );
		}
		if ( $left ) {
			$pos[] = JHTMLSelect::option( 'left', JText::_( 'Left' ) );
		}
		if ( $right ) {
			$pos[] = JHTMLSelect::option( 'right', JText::_( 'Right' ) );
		}

		$positions = JHTMLSelect::genericList( $pos, $name, 'class="inputbox" size="1"'. $javascript, 'value', 'text', $active, $id );

		return $positions;
	}

   /**
	* Select list of active categories for components
	*/
	/*
	 * Function is used in the site/administrator : duplicate in JElementCategory
	 */
	function ComponentCategory( $name, $section, $active=NULL, $javascript=NULL, $order='ordering', $size=1, $sel_cat=1 )
	{
		global $mainframe;

		$db =& JFactory::getDBO();

		$query = 'SELECT id AS value, title AS text'
		. ' FROM #__categories'
		. ' WHERE section = "'. $section . '"'
		. ' AND published = 1'
		. ' ORDER BY '. $order
		;
		$db->setQuery( $query );
		if ( $sel_cat ) {
			$categories[] = JHTMLSelect::option( '0', '- '. JText::_( 'Select a Category' ) .' -' );
			$categories = array_merge( $categories, $db->loadObjectList() );
		} else {
			$categories = $db->loadObjectList();
		}

		if ( count( $categories ) < 1 ) {
			$mainframe->redirect( 'index.php?option=com_categories&section='. $section, JText::_( 'You must create a category first.' ) );
		}

		$category = JHTMLSelect::genericList( $categories, $name, 'class="inputbox" size="'. $size .'" '. $javascript, 'value', 'text', $active );

		return $category;
	}

   /**
	* Select list of active sections
	*/
	/*
	 * Function is only used in the administrator : duplicate in JElementSection
	 */
	function SelectSection( $name, $active=NULL, $javascript=NULL, $order='ordering' )
	{
		$db =& JFactory::getDBO();

		$categories[] = JHTMLSelect::option( '-1', '- '. JText::_( 'Select Section' ) .' -' );
		$categories[] = JHTMLSelect::option( '0', JText::_( 'Uncategorized' ) );
		$query = 'SELECT id AS value, title AS text'
		. ' FROM #__sections'
		. ' WHERE published = 1'
		. ' ORDER BY ' . $order
		;
		$db->setQuery( $query );
		$sections = array_merge( $categories, $db->loadObjectList() );

		$category = JHTMLSelect::genericList( $sections, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );

		return $category;
	}
}

?>