<?php
/**
 * @version $Id$
 * @package Joomla
 * @subpackage Menus
 * @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights
 * reserved.
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

jimport( 'joomla.application.model' );

/**
 * @package Joomla
 * @subpackage Menus
 * @author Andrew Eddie
 */
class JMenuModelItem extends JModel
{
	/** @var object JTable object */
	var $_table = null;

	/** @var object JTable object */
	var $_url = null;

	/**
	 * Overridden constructor
	 * @access	protected
	 */
	function __construct()
	{
		parent::__construct();
		$url = JRequest::getVar('url', array(), '', 'array');
		if (isset($url['option'])) {
			$this->_url = 'index.php?option='.$url['option'];
			unset($url['option']);
			if (count($url)) {
				foreach ($url as $k => $v)
				{
					$this->_url .= '&amp;'.$k.'='.$v;
				}
			}
		}
	}

	function &getItem() {

		static $item;
		if (isset($item)) {
			return $item;
		}

		$table =& $this->_getTable();

		// Load the current item if it has been defined
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		if ($cid[0]) {
			$table->load($cid[0]);
		}

		// Override the current item's type field if defined in the request
		if ($type = JRequest::getVar('type', false)) {
			$table->type = $type;
		}

		// Override the current item's menutype field if defined in the request
		if ($menu_type = JRequest::getVar('menutype', false)) {
			$table->menutype = $menu_type;
		}

		// Override the current item's link field if defined in the request
		if ($this->_url) {
			$table->link = $this->_url;
		}

		$url = str_replace('index.php?', '', $table->link);
		$url = str_replace('&amp;', '&', $url);
		$table->linkparts = null;
		parse_str($url, $table->linkparts);

		$item = clone($table);
		return $item;
	}

	function &getDetails()
	{
		// Get the helper object then the details from it.
		if ($helper =& $this->_getHelper()) {
			$details =& $helper->getDetails();
		} else {
			$details = array();
		}

		return $details;
	}

	function &getExpansion()
	{
		$item = &$this->getItem();
		$return['option'] = JRequest::getVar('expand');
		$menutype = JRequest::getVar('menutype');
		if ($return['option']) {
			require_once(COM_MENUS.DS.'models'.DS.'ilink.php');
			$handler		= new iLink($return['option'], $item->id, $menutype);
			$return['html'] = $handler->getTree();
			return $return;			
		} else {
			$return['html'] = null;
		}
		return $return;
	}

	function &getStateParams()
	{
		// Get the state parameters
		$item	=& $this->getItem();
		$params	= new JParameter($item->params);

		if ($state =& $this->_getStateXML()) {
			if (is_a($state, 'JSimpleXMLElement')) {
				$sp =& $state->getElementByPath('params');
				$params->setXML($sp);
			}
		}
		return $params;
	}

	function &getAdvancedParams()
	{
		// Get the state parameters
		$item	=& $this->getItem();
		$params	= new JParameter($item->params);

		if ($state =& $this->_getStateXML()) {
			if (is_a($state, 'JSimpleXMLElement')) {
				$ap =& $state->getElementByPath('advanced');
				$params->setXML($ap);
			}
		}
		return $params;
	}

	function getStateName()
	{
		$name = null;
		if ($state =& $this->_getStateXML()) {
			if (is_a($state, 'JSimpleXMLElement')) {
				$sn =& $state->getElementByPath('name');
				if ($sn) {
					$name = $sn->data();
				}
			}
		}
		return JText::_($name);
	}

	function getStateDescription()
	{
		$description = null;
		if ($state =& $this->_getStateXML()) {
			if (is_a($state, 'JSimpleXMLElement')) {
				$sd =& $state->getElementByPath('description');
				if ($sd) {
					$description = $sd->data();
				}
			}
		}
		return JText::_($description);
	}

	function store()
	{
		global $mainframe;

		$row =& $this->getItem();

		$post	= JRequest::get( 'post' );

		if ($helper =& $this->_getHelper()) {
			$values = $helper->prepForStore($post);
		} else {
			$values = $post;
		}

		if (!$row->bind( $values )) {
//			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			return false;
		}

		$row->name = ampReplace( $row->name );

		if (!$row->check()) {
//			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			return false;
		}
		if (!$row->store()) {
//			echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
			return false;
		}
		$row->checkin();
		$row->reorder( "menutype = '$row->menutype' AND parent = $row->parent" );

		// Reset the wizard
		$mainframe->setUserState('request.menuwizard', null);

		return true;
	}

	/**
	 * Delete one or more menu items
	 * @param mixed int or array of id values
	 */
	function delete( $ids )
	{
		if (!is_array( $ids ))
		{
			$ids = array( $ids );
		}

		$db = &$this->getDBO();

		if (count( $ids ))
		{
			// Delete associated module and template mappings
			$where = 'WHERE menuid = ' . implode( ' OR menuid = ', $ids );

			$query = 'DELETE FROM #__modules_menu '
				. $where;
			$db->setQuery( $query );
			if (!$db->query())
			{
				$this->setError( $menuTable->getErrorMsg() );
				return false;
			}

			$query = 'DELETE FROM #__templates_menu '
				. $where;
			$db->setQuery( $query );
			if (!$db->query())
			{
				$this->setError( $menuTable->getErrorMsg() );
				return false;
			}

			// Delete the menu items
			$where = 'WHERE id = ' . implode( ' OR id = ', $ids );

			$query = 'DELETE FROM #__menu ' . $where;
			$db->setQuery( $query );
			if (!$db->query())
			{
				$this->setError( $db->getErrorMsg() );
				return false;
			}
		}
		return true;
	}

	/**
	 * Delete menu items by type
	 */
	function deleteByType( $type = '' )
	{
		$db = &$this->getDBO();

		$query = 'SELECT id' .
				' FROM #__menu' .
				' WHERE menutype = ' . $db->Quote( $type );
		$db->setQuery( $query );
		$ids = $db->loadResultArray();

		if ($db->getErrorNum())
		{
			$this->setError( $db->getErrorMsg() );
			return false;
		}

		return $this->delete( $ids );
	}

	/**
	 * Get a list of the menu_types records
	 * @return array An array of records as objects
	 */
	function getMenuTypeList()
	{
		$db = $this->getDBO();
		$query = 'SELECT * FROM #__menu_types';
		$db->setQuery( $query );
		return $db->loadObjectList();
	}

	/**
	 * Get a list of the menutypes
	 * @return array An array of menu type names
	 */
	function getMenuTypes()
	{
		$db = $this->getDBO();
		$query = 'SELECT menutype FROM #__menu_types';
		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	/**
	 * Gets the componet table object related to this menu item
	 */
	function &getComponent()
	{
		$item		=& $this->getItem();
		$id			= $item->componentid;
		$component	= & JTable::getInstance( 'component', $this->getDBO() );
		$component->load( $id );
		return $component;
	}

	/**
	 * Gets a list of components that can link to the menu
	 */
	function getComponentList()
	{
		$db = $this->getDBO();
		$query = "SELECT c.id, c.name, c.link, c.option"
		. "\n FROM #__components AS c"
		. "\n WHERE c.link <> '' AND parent = 0"
		. "\n ORDER BY c.name"
		;
		$db->setQuery( $query );
		$result = $db->loadObjectList( );
		return $result;
	}

	/**
	 * Returns the internal table object
	 * @return JTable
	 */
	function &_getTable()
	{
		if ($this->_table == null) {
			$this->_table =& JTable::getInstance( 'menu', $this->getDBO() );
		}
		return $this->_table;
	}

	function &_getHelper()
	{
		static $helper;

		if (isset($helper)) {
			return $helper;
		}

		// Include and create the helper object
		$item =& $this->getItem();
		if ($item->type && file_exists(COM_MENUS.'helpers'.DS.$item->type.'.php')) {
			require_once(COM_MENUS.'helpers'.DS.$item->type.'.php');
			$class = 'JMenuHelper'.ucfirst($item->type);
			$helper = new $class($this);
		} else {
			$helper = false;
		}
		return $helper;
	}

	function &_getStateXML()
	{
		static $xml;

		if (isset($xml)) {
			return $xml;
		}
		$xml = null;
		$xmlpath = null;
		$item 	= &$this->getItem();
		if (isset($item->linkparts['view'])) {
			// View is set... so we konw to look in view file
			if (isset($item->linkparts['layout'])) {
				$layout = $item->linkparts['layout'];
			} else {
				$layout = 'default';
			}
			$lpath = JPATH_ROOT.DS.'components'.DS.$item->linkparts['option'].DS.'views'.DS.$item->linkparts['view'].DS.'tmpl'.DS.$layout.'.xml';
			$vpath = JPATH_ROOT.DS.'components'.DS.$item->linkparts['option'].DS.'views'.DS.$item->linkparts['view'].DS.'metadata.xml';
			if (file_exists($lpath)) {
				$xmlpath = $lpath;
			} elseif (file_exists($vpath)) {
				$xmlpath = $vpath;
			}
		}
		if (!$xmlpath && isset($item->linkparts['option'])) {
			$xmlpath = JPATH_ROOT.DS.'components'.DS.$item->linkparts['option'].DS.'metadata.xml';
		}

		if (file_exists($xmlpath)) {
			$xml =& JFactory::getXMLParser('Simple');
			if ($xml->loadFile($xmlpath)) {
				$this->_xml = &$xml;
				$document =& $xml->document;
				$xml =& $document->getElementByPath('state');

				if (!is_a($xml, 'JSimpleXMLElement')) {
					return $document;
				}

				/*
				 * HANDLE A SWITCH IF IT EXISTS
				 */
				if ($switch = $xml->attributes('switch')) {
					$default = $xml->attributes('default');
					// Handle switch
					$switchVal = ($item->linkparts[$switch])? $item->linkparts[$switch] : 'default';
					$found = false;

					foreach ($xml->children() as $child) {
						if ($child->name() == $switchVal) {
							$xml =& $child;
							$found = true;
							break;
						}
					}

					if (!$found) {
						foreach ($xml->children() as $child) {
							if ($child->name() == $default) {
								$xml =& $child;
								break;
							}
						}
					}
				}

				/*
				 * HANDLE INCLUDED PARAMS
				 */
				$children =& $xml->children();
				if (count($children) == 1) {
					if ($children[0]->name() == 'include') {
						$ret =& $this->_getIncludedParams($children[0]);
						if ($ret) {
							$xml =& $ret;
						}
					}
				}

				if ($switch = $xml->attributes('switch')) {
					$default = $xml->attributes('default');
					// Handle switch
					$switchVal = ($item->linkparts[$switch])? $item->linkparts[$switch] : 'default';
					$found = false;

					foreach ($xml->children() as $child) {
						if ($child->name() == $switchVal) {
							$xml =& $child;
							$found = true;
							break;
						}
					}

					if (!$found) {
						foreach ($xml->children() as $child) {
							if ($child->name() == $default) {
								$xml =& $child;
								break;
							}
						}
					}
				}
			}
		}
		return $xml;
	}

	function &_getIncludedParams($include)
	{
		$tags	= array();
		$state	= null;
		$source	= $include->attributes('source');
		$path	= $include->attributes('path');
		$item 	= &$this->getItem();

		preg_match_all( "/{([A-Za-z\-_]+)}/", $source, $tags);
		if (isset($tags[1])) {
			for ($i=0;$i<count($tags[1]);$i++) {
				$source = str_replace($tags[0][$i], @$item->linkparts[$tags[1][$i]], $source);
			}
		}

		// load the source xml file
		if (file_exists( JPATH_ROOT.$source )) {
			$xml = & JFactory::getXMLParser('Simple');

			if ($xml->loadFile(JPATH_ROOT.$source)) {
				$document = &$xml->document;
				$state = $document->getElementByPath($path);
			}
		}
		return $state;
	}
}
?>