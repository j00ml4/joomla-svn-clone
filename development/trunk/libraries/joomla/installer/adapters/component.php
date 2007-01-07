<?php
/**
 * @version		$Id: component.php 6138 2007-01-02 03:44:18Z eddiea $
 * @package		Joomla.Framework
 * @subpackage	Installer
 * @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

/**
 * Component installer
 *
 * @package		Joomla.Framework
 * @subpackage	Installer
 * @since		1.5
 */
class JInstaller_component extends JObject
{
	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	object	$parent	Parent object [JInstaller instance]
	 * @return	void
	 * @since	1.5
	 */
	function __construct(&$parent)
	{
		$this->parent =& $parent;
	}

	/**
	 * Custom install method for components
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function install()
	{
		// Get a database connector object
		$db =& $this->parent->getDBO();

		// Get the extension manifest object
		$manifest =& $this->parent->getManifest();
		$this->manifest =& $manifest->document;

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Manifest Document Setup Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Set the component name
		$name =& $this->manifest->getElementByPath('name');
		$this->set('name', $name->data());

		// Get the component description
		$description = & $this->manifest->getElementByPath('description');
		if (is_a($description, 'JSimpleXMLElement')) {
			$this->parent->set('message', $this->get('name').'<p>'.$description->data().'</p>');
		} else {
			$this->parent->set('message', $this->get('name'));
		}

		// Get some important manifest elements
		$this->adminElement		=& $this->manifest->getElementByPath('administration');
		$this->installElement	=& $this->manifest->getElementByPath('install');
		$this->uninstallElement	=& $this->manifest->getElementByPath('uninstall');

		// Set the installation target paths
		$this->parent->setPath('extension_site', JPath::clean(JPATH_SITE.DS."components".DS.strtolower("com_".str_replace(" ", "", $this->get('name'))).DS));
		$this->parent->setPath('extension_administrator', JPath::clean(JPATH_ADMINISTRATOR.DS."components".DS.strtolower("com_".str_replace(" ", "", $this->get('name'))).DS));

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Filesystem Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		/*
		 * If the component site or admin directory already exists, then we will assume that the component is already
		 * installed or another component is using that directory.
		 */
		if ((file_exists($this->parent->getPath('extension_site')) || file_exists($this->parent->getPath('extension_administrator'))) && !$this->parent->getOverwrite()) {
			JError::raiseWarning(1, 'Component Install: '.JText::_('Another component is already using directory').': "'.$this->parent->getPath('extension_site').'"');
			return false;
		}

		// If the component directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_site'))) {
			if (!$created = JFolder::create($this->parent->getPath('extension_site'))) {
				JError::raiseWarning(1, 'Component Install: '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_site').'"');
				return false;
			}
		}

		/*
		 * Since we created the component directory and will want to remove it if we have to roll back
		 * the installation, lets add it to the installation step stack
		 */
		if ($created) {
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_site')));
		}

		// If the component admin directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_administrator'))) {
			if (!$created = JFolder::create($this->parent->getPath('extension_administrator'))) {
				JError::raiseWarning(1, 'Component Install: '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_administrator').'"');
				// Install failed, rollback any changes
				$this->parent->abort();
				return false;
			}
		}

		/*
		 * Since we created the component admin directory and we will want to remove it if we have to roll
		 * back the installation, lets add it to the installation step stack
		 */
		if ($created) {
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_administrator')));
		}

		// Find files to copy
		foreach ($this->manifest->children() as $child)
		{
			if (is_a($child, 'JSimpleXMLElement') && $child->name() == 'files') {
				if ($this->parent->parseFiles($child) === false) {
					// Install failed, rollback any changes
					$this->parent->abort();
					return false;
				}
			}
		}
		foreach ($this->adminElement->children() as $child)
		{
			if (is_a($child, 'JSimpleXMLElement') && $child->name() == 'files') {
				if ($this->parent->parseFiles($child, 1) === false) {
					// Install failed, rollback any changes
					$this->parent->abort();
					return false;
				}
			}
		}

		// Parse optional tags
		$this->parent->parseFiles($this->manifest->getElementByPath('media'));
		$this->parent->parseFiles($this->manifest->getElementByPath('administration/media'), 1);
		$this->parent->parseFiles($this->manifest->getElementByPath('languages'));
		$this->parent->parseFiles($this->manifest->getElementByPath('administration/languages'), 1);

		// Parse deprecated tags
		$this->parent->parseFiles($this->manifest->getElementByPath('images'));
		$this->parent->parseFiles($this->manifest->getElementByPath('administration/images'), 1);

		// If there is an install file, lets copy it.
		$installScriptElement =& $this->manifest->getElementByPath('installfile');
		if (is_a($installScriptElement, 'JSimpleXMLElement')) {
			// Make sure it hasn't already been copied (this would be an error in the xml install file)
			if (!file_exists($this->parent->getPath('extension_administrator').$installScriptElement->data()))
			{
				$path['src']	= $this->parent->getPath('source').$installScriptElement->data();
				$path['dest']	= $this->parent->getPath('extension_administrator').$installScriptElement->data();
				if (!$this->parent->copyFiles(array ($path))) {
					// Install failed, rollback changes
					$this->parent->abort('Component Install: '.JText::_('Could not copy PHP install file.'));
					return false;
				}
			}
			$this->set('install.script', $installScriptElement->data());
		}

		// If there is an uninstall file, lets copy it.
		$uninstallScriptElement =& $this->manifest->getElementByPath('uninstallfile');
		if (is_a($uninstallScriptElement, 'JSimpleXMLElement')) {
			// Make sure it hasn't already been copied (this would be an error in the xml install file)
			if (!file_exists($this->parent->getPath('extension_administrator').$uninstallScriptElement->data()))
			{
				$path['src']	= $this->parent->getPath('source').$uninstallScriptElement->data();
				$path['dest']	= $this->parent->getPath('extension_administrator').$uninstallScriptElement->data();
				if (!$this->parent->copyFiles(array ($path))) {
					// Install failed, rollback changes
					$this->parent->abort('Component Install: '.JText::_('Could not copy PHP uninstall file.'));
					return false;
				}
			}
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Database Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		/*
		 * Let's run the install queries for the component
		 *	If backward compatibility is required - run queries in xml file
		 *	If Joomla 1.5 compatible, with discreet sql files - execute appropriate
		 *	file for utf-8 support or non-utf-8 support
		 */
		$result = $this->parent->parseQueries($this->manifest->getElementByPath('install/queries'));
		if ($result === false) {
			// Install failed, rollback changes
			$this->parent->abort('Component Install: '.JText::_('SQL Error')." ".$db->stderr(true));
			return false;
		} elseif ($result === 0) {
			// no backward compatibility queries found - try for Joomla 1.5 type queries
			// second argument is the utf compatible version attribute
			$utfresult = $this->parent->parseSQLFiles($this->manifest->getElementByPath('install/sql'));
			if ($utfresult === false) {
				// Install failed, rollback changes
				$this->parent->abort('Component Install: '.JText::_('SQLERRORORFILE')." ".$db->stderr(true));
				return false;
			}
		}

		// Time to build the admin menus
		$this->_buildAdminMenus();

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Custom Installation Script Section
		 * ---------------------------------------------------------------------------------------------
		 */

		/*
		 * If we have an install script, lets include it, execute the custom
		 * install method, and append the return value from the custom install
		 * method to the installation message.
		 */
		if ($this->get('install.script')) {
			if (is_file($this->parent->getPath('extension_administrator').$this->get('install.script'))) {
				ob_start();
				ob_implicit_flush(false);
				require_once ($this->parent->getPath('extension_administrator').$this->get('install.script'));
				if (function_exists('com_install')) {
					if (com_install() === false) {
						$this->parent->abort('Component Install: '.JText::_('Custom install routine failure'));
						return false;
					}
				}
				$msg = ob_get_contents();
				ob_end_clean();
				if ($msg != '') {
					$this->parent ->set('extension.message', $msg);
				}
			}
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Finalization and Cleanup Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Lastly, we will copy the manifest file to its appropriate place.
		if (!$this->parent->copyManifest()) {
			// Install failed, rollback changes
			$this->parent->abort('Component Install: '.JText::_('Could not copy setup file'));
			return false;
		}
		return true;
	}

	/**
	 * Custom uninstall method for components
	 *
	 * @access	public
	 * @param	int		$cid	The id of the component to uninstall
	 * @param	int		$clientId	The id of the client (unused)
	 * @return	mixed	Return value for uninstall method in component uninstall file
	 * @since	1.0
	 */
	function uninstall($id, $clientId)
	{
		// Initialize variables
		$db =& $this->parent->getDBO();
		$row	= null;
		$retval	= true;

		// First order of business will be to load the component object table from the database.
		// This should give us the necessary information to proceed.
		$row = & JTable::getInstance('component');
		$row->load($id);

		// Is the component we are trying to uninstall a core one?
		// Because that is not a good idea...
		if ($row->iscore) {
			JError::raiseWarning(100, 'Component Uninstall: '.JText::sprintf('WARNCORECOMPONENT', $row->name)."<br />".JText::_('WARNCORECOMPONENT2'));
			return false;
		}

		// Get the admin and site paths for the component
		$this->parent->setPath('extension_administrator', JPath::clean(JPATH_ADMINISTRATOR.DS.'components'.DS.$row->option));
		$this->parent->setPath('extension_site', JPath::clean(JPATH_SITE.DS.'components'.DS.$row->option));

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Manifest Document Setup Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Find and load the XML install file for the component
		$this->parent->setPath('source', $this->parent->getPath('extension_administrator'));

		// Get the package manifest objecct
		$manifest =& $this->parent->getManifest();
		if (!is_a($manifest, 'JSimpleXML')) {
			// Make sure we delete the folders if no manifest exists
			JFolder::delete($this->parent->getPath('extension_administrator'));
			JFolder::delete($this->parent->getPath('extension_site'));
			JError::raiseWarning(100, 'Component Uninstall: Package manifest file invalid or not found');
			return false;
		}

		// Get the root node of the manifest document
		$this->manifest =& $manifest->document;

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Custom Uninstallation Script Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Now lets load the uninstall file if there is one and execute the uninstall function if it exists.
		$uninstallfileElement =& $this->manifest->getElementByPath('uninstallfile');
		if (is_a($uninstallfileElement, 'JSimpleXMLElement')) {
			// Element exists, does the file exist?
			if (file_exists($this->parent->getPath('extension_administrator').$uninstallfileElement->data())) {
				ob_start();
				ob_implicit_flush(false);
				require_once ($this->parent->getPath('extension_administrator').$uninstallfileElement->data());
				if (function_exists('com_uninstall')) {
					if (com_uninstall() === false) {
						JError::raiseWarning(100, 'Component Uninstall: '.JText::_('Custom Uninstall script unsuccessful'));
						$retval = false;
					}
				}
				$msg = ob_get_contents();
				ob_end_clean();
				if ($msg != '') {
					$this->parent->set('extension.message', $msg);
				}
			}
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Database Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		/*
		 * Let's run the uninstall queries for the component
		 *	If backward compatibility is required - run queries in xml file
		 *	If Joomla 1.5 compatible, with discreet sql files - execute appropriate
		 *	file for utf-8 support or non-utf support
		 */
		$result = $this->parent->parseQueries($this->manifest->getElementByPath('uninstall/queries'));
		if ($result === false) {
			// Install failed, rollback changes
			JError::raiseWarning(100, 'Component Uninstall: '.JText::_('SQL Error')." ".$db->stderr(true));
			$retval = false;
		} elseif ($result === 0) {
			// no backward compatibility queries found - try for Joomla 1.5 type queries
			// second argument is the utf compatible version attribute
			$utfresult = $this->parent->parseSQLFiles($this->manifest->getElementByPath('uninstall/sql'));
			if ($utfresult === false) {
				// Install failed, rollback changes
				JError::raiseWarning(100, 'Component Uninstall: '.JText::_('SQLERRORORFILE')." ".$db->stderr(true));
				$retval = false;
			}
		}

		$this->_removeAdminMenus($row);

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Filesystem Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Let's remove language files and media in the JROOT/images/ folder that are
		// associated with the component we are uninstalling
		$this->parent->removeFiles($this->manifest->getElementByPath('media'));
		$this->parent->removeFiles($this->manifest->getElementByPath('media'), 1);
		$this->parent->removeFiles($this->manifest->getElementByPath('languages'));
		$this->parent->removeFiles($this->manifest->getElementByPath('administration/languages'), 1);

		// Now we need to delete the installation directories.  This is the final step in uninstalling the component.
		if (trim($row->option)) {
			// Delete the component site directory
			if (is_dir($this->parent->getPath('extension_site'))) {
				if (!JFolder::delete($this->parent->getPath('extension_site'))) {
					JError::raiseWarning(100, 'Component Uninstall: '.JText::_('Unable to remove the component site directory'));
					$retval = false;
				}
			}

			// Delete the component admin directory
			if (is_dir($this->parent->getPath('extension_administrator'))) {
				if (!JFolder::delete($this->parent->getPath('extension_administrator'))) {
					JError::raiseWarning(100, 'Component Uninstall: '.JText::_('Unable to remove the component admin directory'));
					$retval = false;
				}
			}
			return $retval;
		} else {
			// No component option defined... cannot delete what we don't know about
			JError::raiseWarning(100, 'Component Uninstall: Option field empty, cannot remove files');
			return false;
		}
	}

	/**
	 * Method to build menu database entries for a component
	 *
	 * @access	private
	 * @return	boolean	True if successful
	 * @since	1.5
	 */
	function _buildAdminMenus()
	{
		// Get database connector object
		$db =& $this->parent->getDBO();

		// Initialize variables
		$option = strtolower("com_".str_replace(" ", "", $this->get('name')));

		// Ok, now its time to handle the menus.  Start with the component root menu, then handle submenus.
		$menuElement = & $this->adminElement->getElementByPath('menu');
		if (is_a($menuElement, 'JSimpleXMLElement')) {

			$db_name = $menuElement->data();
			$db_link = "option=".$option;
			$db_menuid = 0;
			$db_parent = 0;
			$db_admin_menu_link = "option=".$option;
			$db_admin_menu_alt = $menuElement->data();
			$db_option = $option;
			$db_ordering = 0;
			$db_admin_menu_img = ($menuElement->attributes('img')) ? $menuElement->attributes('img') : 'js/ThemeOffice/component.png';
			$db_iscore = 0;
			$db_params = $this->parent->getParams();
			$db_enabled = 1;

			$query = "INSERT INTO #__components" .
					"\n VALUES( '', '$db_name', '$db_link', $db_menuid, $db_parent, '$db_admin_menu_link', '$db_admin_menu_alt', '$db_option', $db_ordering, '$db_admin_menu_img', $db_iscore, '$db_params', '$db_enabled' )";
			$db->setQuery($query);
			if (!$db->query()) {
				// Install failed, rollback changes
				$this->parent->abort('Component Install: '.$db->stderr(true));
				return false;
			}
			$menuid = $db->insertid();

			/*
			 * Since we have created a menu item, we add it to the installation step stack
			 * so that if we have to rollback the changes we can undo it.
			 */
			$this->parent->pushStep(array ('type' => 'menu', 'id' => $menuid));
		} else {

			/*
			 * No menu element was specified so lets first see if we have an admin menu entry for this component
			 * if we do.. then we obviously don't want to create one -- we'll just attach sub menus to that one.
			 */
			$query = "SELECT id" .
					"\n FROM #__components" .
					"\n WHERE `option` = ".$db->Quote($option) .
					"\n AND parent = 0";
			$db->setQuery($query);
			$menuid = $db->loadResult();

			if (!$menuid) {
				// No menu entry, lets just enter a component entry to the table.
				$db_name = $this->get('name');
				$db_link = "";
				$db_menuid = 0;
				$db_parent = 0;
				$db_admin_menu_link = "";
				$db_admin_menu_alt = $this->get('name');
				$db_option = $option;
				$db_ordering = 0;
				$db_admin_menu_img = "";
				$db_iscore = 0;
				$db_params = $this->parent->getParams();
				$db_enabled = 1;

				$query = "INSERT INTO #__components" .
						"\n VALUES( '', '$db_name', '$db_link', $db_menuid, $db_parent, '$db_admin_menu_link', '$db_admin_menu_alt', '$db_option', $db_ordering, '$db_admin_menu_img', $db_iscore, '$db_params', '$db_enabled' )";
				$db->setQuery($query);
				if (!$db->query()) {
					// Install failed, rollback changes
					$this->parent->abort('Component Install: '.$db->stderr(true));
					return false;
				}
				$menuid = $db->insertid();

				/*
				 * Since we have created a menu item, we add it to the installation step stack
				 * so that if we have to rollback the changes we can undo it.
				 */
				$this->parent->pushStep(array ('type' => 'menu', 'id' => $menuid));
			}
		}

		/*
		 * Process SubMenus
		 */

		// Initialize submenu ordering value
		$ordering = 0;
		$submenu = $this->adminElement->getElementByPath('submenu');
		if (!is_a($submenu, 'JSimpleXMLElement') || !count($submenu->children())) {
			return true;
		}
		foreach ($submenu->children() as $child)
		{
			if (is_a($child, 'JSimpleXMLElement') && $child->name() == 'menu') {

				$com = JTable::getInstance('component');
				$com->name = $child->data();
				$com->link = '';
				$com->menuid = 0;
				$com->parent = $menuid;
				$com->iscore = 0;
				$com->admin_menu_alt = $child->data();
				$com->option = $option;
				$com->ordering = $ordering ++;

				// Set the sub menu link
				if ($child->attributes("link")) {
					$com->admin_menu_link = str_replace('&amp;', '&', $child->attributes("link"));
				} else {
					$request = array();
					if ($child->attributes('act')) {
						$request[] = 'act='.$child->attributes('act');
					}
					if ($child->attributes('task')) {
						$request[] = 'task='.$child->attributes('task');
					}
					if ($child->attributes('controller')) {
						$request[] = 'controller='.$child->attributes('controller');
					}
					if ($child->attributes('view')) {
						$request[] = 'view='.$child->attributes('view');
					}
					if ($child->attributes('layout')) {
						$request[] = 'layout='.$child->attributes('layout');
					}
					if ($child->attributes('sub')) {
						$request[] = 'sub='.$child->attributes('sub');
					}
					$qstring = (count($request)) ? '&'.implode('&',$request) : '';
					$com->admin_menu_link = "option=".$option.$qstring;
				}

				// Set the sub menu image
				if ($child->attributes("img")) {
					$com->admin_menu_img = $child->attributes("img");
				} else {
					$com->admin_menu_img = "js/ThemeOffice/component.png";
				}

				// Store the submenu
				if (!$com->store()) {
					// Install failed, rollback changes
					$this->parent->abort('Component Install: '.JText::_('SQL Error')." ".$db->stderr(true));
					return false;
				}

				/*
				 * Since we have created a menu item, we add it to the installation step stack
				 * so that if we have to rollback the changes we can undo it.
				 */
				$this->parent->pushStep(array ('type' => 'menu', 'id' => $com->id));
			}
		}
	}

	/**
	 * Method to remove admin menu references to a component
	 *
	 * @access	private
	 * @param	object	$component	Component table object
	 * @return	boolean	True if successful
	 * @since	1.5
	 */
	function _removeAdminMenus(&$row)
	{
		// Get database connector object
		$db =& $this->parent->getDBO();
		$retval = true;

		// Delete the submenu items
		$sql = "DELETE " .
				"\nFROM #__components " .
				"\nWHERE parent = ".(int)$row->id;
		$db->setQuery($sql);
		if (!$db->query()) {
			JError::raiseWarning(100, 'Component Uninstall: '.$db->stderr(true));
			$retval = false;
		}

		// Next, we will delete the component object
		if (!$row->delete($row->id)) {
			JError::raiseWarning(100, 'Component Uninstall: '.JText::_('Unable to delete the component from the database'));
			$retval = false;
		}
		return $retval;
	}

	/**
	 * Custom rollback method
	 * 	- Roll back the component menu item
	 *
	 * @access	public
	 * @param	array	$arg	Installation step to rollback
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _rollback_menu($arg)
	{
		// Get database connector object
		$db =& $this->parent->getDBO;

		// Remove the entry from the #__components table
		$query = "DELETE " .
				"\nFROM `#__components` " .
				"\nWHERE id=".(int)$arg['id'];
		$db->setQuery($query);
		return ($db->query() !== false);
	}
}
?>