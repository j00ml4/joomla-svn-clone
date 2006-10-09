<?php
/**
 * @version $Id: item.php 5216 2006-09-27 19:02:42Z Jinx $
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

// Import library dependencies
require_once(dirname(__FILE__).DS.'extension.php');
jimport( 'joomla.filesystem.folder' );

/**
 * Extension Manager Languages Model
 * 
 * @author		Louis Landry <louis.landry@joomla.org>
 * @package		Joomla
 * @subpackage	Installer
 * @since		1.5
 */
class ExtensionManagerModelLanguages extends ExtensionManagerModel
{
	/**
	 * Extension Type
	 * @var	string
	 */
	var $_type = 'language';

	/**
	 * Overridden constructor
	 * @access	protected
	 */
	function __construct()
	{
		global $mainframe;

		// Call the parent constructor
		parent::__construct();

		// Set state variables from the request
		$this->setState('filter.string', $mainframe->getUserStateFromRequest( "com_installer.languages.string", 'filter' ));
		$this->setState('filter.client', $mainframe->getUserStateFromRequest( "com_installer.languages.client", 'client', -1 ));
	}

	function _loadItems()
	{
		global $mainframe, $option;

		$db = &JFactory::getDBO();

		if ($this->_state->get('filter.client') < 0) {
			$client = 'all';
			// Get the site languages
			$langBDir = JLanguage::getLanguagePath(JPATH_SITE);
			$langDirs = JFolder::folders($langBDir);

			for ($i=0; $i < count($langDirs); $i++)
			{
				$lang = new stdClass();
				$lang->folder = $langDirs[$i];
				$lang->client = 0;
				$lang->baseDir = $langBDir;
				$languages[] = $lang;
			}
			// Get the admin languages
			$langBDir = JLanguage::getLanguagePath(JPATH_ADMINISTRATOR);
			$langDirs = JFolder::folders($langBDir);

			for ($i=0; $i < count($langDirs); $i++)
			{
				$lang = new stdClass();
				$lang->folder = $langDirs[$i];
				$lang->client = 1;
				$lang->baseDir = $langBDir;
				$languages[] = $lang;
			}
		} else {
			$clientInfo = JApplicationHelper::getClientInfo($this->_state->get('filter.client'));
			$client = $clientInfo->name;
			$langBDir = JLanguage::getLanguagePath($clientInfo->path);
			$langDirs = JFolder::folders($langBDir);

			for ($i=0, $n=count($langDirs); $i < $n; $i++)
			{
				$lang = new stdClass();
				$lang->folder = $langDirs[$i];
				$lang->client = $clientInfo->id;
				$lang->baseDir = $langBDir;

				if ($this->_state->get('filter.string')) {
					if (strpos($lang->folder, $this->_state->get('filter.string')) !== false) {
						$languages[] = $lang;
					}
				} else {
					$languages[] = $lang;
				}
			}
		}

		$rows = array();
		$rowid = 0;
		foreach ($languages as $language)
		{
			$files = JFolder::files( $language->baseDir . $language->folder, '^([-_A-Za-z]*)\.xml$' );
			foreach ($files as $file)
			{
				$data = JApplicationHelper::parseXMLLangMetaFile($language->baseDir .DS. $language->folder . DS . $file);

				$row 			= new StdClass();
				$row->id 		= $rowid;
				$row->client_id = $language->client;
				$row->language 	= $language->baseDir . $language->folder;
				
				// If we didn't get valid data from the xml file, move on...
				if (!is_array($data)) {
					continue;
				}

				// Populate the row from the xml meta file
				foreach($data as $key => $value) {
					$row->$key = $value;
				}

				// if current than set published
				$clientVals = JApplicationHelper::getClientInfo($row->client_id);
				$lang = 'lang_'.$clientVals->name;
				if ( $mainframe->getCfg($lang) == basename( $row->language ) ) {
					$row->published	= 1;
				} else {
					$row->published = 0;
				}

				$row->checked_out = 0;
				$row->jname = JString::strtolower( str_replace( " ", "_", $row->name ) );
				$rows[] = $row;
				$rowid++;
			}
		}
		$this->setState('pagination.total', count($rows));
		$this->_items = array_slice( $rows, $this->_state->get('pagination.offset'), $this->_state->get('pagination.limit') );
	}
}
?>