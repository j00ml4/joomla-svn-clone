<?php

/* ******************************************************************
* This file is a generic interface to Aliro, Joomla 1.5+, Joomla 1.0.x and Mambo
* Copyright (c) 2008 Martin Brampton
* Issued as open source under GNU/GPL
* For support and other information, visit http://acmsapi.org
* To contact Martin Brampton, write to martin@remository.com
*
*/

class cmsapiJoomlaIntegrated {
	private $dbmodules = array();
	private $dbplugins = array();

	public function __construct () {
		$database = JFactory::getDBO();
		$database->setQuery("SELECT module FROM #__modules WHERE module LIKE 'mod_glossary%'");
		$this->dbmodules = $database->loadResultArray();
		if (!is_array($this->dbmodules)) $this->dbmodules = array();
		$database->setQuery("SELECT element FROM #__plugins WHERE element LIKE '%Glossary%' OR folder = 'cmsapi'");
		$this->dbplugins = $database->loadResultArray();
		if (!is_array($this->dbplugins)) $this->dbplugins = array();
	}

	public function installModulesPlugins ($installer) {
		// Set the installation path
		// For the time being handle only user side modules
		$mtype = 'user';
		$modules = $installer->manifest->getElementByPath('modules')->children();
		foreach ($modules as $module) {
			$files = $module->getElementByPath('files');
			$names = $files->children();
			foreach ($names as $filename) {
				$modname = $filename->attributes('module');
				if (!empty ($modname)) {
					if (in_array($modname, $this->dbmodules)) echo "<br />Module $modname is already installed";
					else {
						$ROOT_PATH = 'admin' == $mtype ? JPATH_ADMINISTRATOR : JPATH_SITE;
						$installer->parent->setPath('extension_root', $ROOT_PATH.'/modules/'.$modname);
						$message = $this->installFiles($installer, $files, 'module');
						if ($message) return $message;
						$message = $this->makeModule($modname);
						if (!$message) echo "<br />Module $modname has been installed";
					}
				}
			}
		}
		$plugins = $installer->manifest->getElementByPath('plugins')->children();
		foreach ($plugins as $plugin) {
			$groupname = $plugin->attributes('group');
			$title = $plugin->attributes('title');
			$files = $plugin->getElementByPath('files');
			$names = $files->children();
			foreach ($names as $filename) {
				$plugname = $filename->attributes('plugin');
				if (!empty ($plugname) AND !empty($groupname)) {
					if (in_array($plugname, $this->dbplugins)) echo "<br />Plugin $plugname is already installed";
					else {
						$installer->parent->setPath('extension_root', JPATH_SITE.'/plugins/'.$groupname);
						$message = $this->installFiles($installer, $files, 'plugin');
						if ($message) return $message;
						$message = $this->makePlugin($plugname, $groupname, $title);
						if (!$message) echo "<br />Plugin $plugname has been installed";
					}
				}
			}
		}
	}

	private function installFiles ($installer, $element, $type) {
		/*
		 * If the directory already exists, then we will assume that the
		 * add-on is already installed or another add-on is using that
		 * directory.
		 */
		if ('module' == $type AND file_exists($installer->parent->getPath('extension_root')) AND !$installer->parent->getOverwrite()) {
			$installer->parent->abort(JText::_('Module').' '.JText::_('Install').': '.JText::_('Another module is already using directory').': "'.$installer->parent->getPath('extension_root').'"');
			return JText::_("Another $type is already using directory");
		}
		// If the add-on directory does not exist, lets create it
		$created = false;
		if (!file_exists($installer->parent->getPath('extension_root'))) {
			if (!$created = JFolder::create($installer->parent->getPath('extension_root'))) {
				$installer->parent->abort(JText::_($type).' '.JText::_('Install').': '.JText::_('Failed to create directory').': "'.$installer->parent->getPath('extension_root').'"');
				return JText::_('Failed to create directory');
			}
		}
		/*
		 * Since we created the module directory and will want to remove it if
		 * we have to roll back the installation, lets add it to the
		 * installation step stack
		 */
		if ($created) {
			$installer->parent->pushStep(array ('type' => 'folder', 'path' => $installer->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		if ($installer->parent->parseFiles($element, -1) === false) {
			// Install failed, roll back changes
			$installer->parent->abort();
			return JText::_('Unable to parse files for copying');
		}
	}

	private function makeModule ($modname) {
		$clientId = 0;
		$row = JTable::getInstance('module');
		$row->title = $modname;
		$row->ordering = $row->getNextOrder( "position='left'" );
		$row->position = 'left';
		$row->showtitle = 1;
		$row->iscore = 0;
		$row->access = $clientId == 1 ? 2 : 0;
		$row->client_id = $clientId;
		$row->module = $modname;
		$row->published = 0;
		$row->params = '';
		if (!$row->store()) {
			// Install failed, roll back changes
			$installer->parent->abort(JText::_('Module').' '.JText::_('Install').': '.$db->stderr(true));
			return JText::_('Unable to write module information to database');
		}
	}

	private function makePlugin ($plugname, $groupname, $title) {
	    $row = JTable::getInstance('plugin');
	    $row->name = $title;
	    $row->ordering = 0;
	    $row->folder = $groupname;
	    $row->iscore = 0;
	    $row->access = 0;
	    $row->client_id = 0;
	    $row->element = $plugname;
	    $row->published = 1;
	    $row->params = '';

	    if (!$row->store()) {
	        // Install failed, roll back changes
	        $installer->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
	        $status->errmsg[]=JText::_('Unable to write plugin information to database');
			return $status;

	    }
	}
}