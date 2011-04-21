<?php

/**************************************************************
* This file is part of Glossary
* Copyright (c) 2008-9 Martin Brampton
* Issued as open source under GNU/GPL
* For support and other information, visit http://remository.com
* To contact Martin Brampton, write to martin@remository.com
*
* Please see glossary.php for more details
*/

// Don't allow direct linking
if (!defined( '_VALID_MOS' ) AND !defined('_JEXEC')) die( 'Direct Access to this location is not allowed.' );

if (!defined('_CMSAPI_LOCAL_DIRECTORY')) define ('_CMSAPI_LOCAL_DIRECTORY', str_replace('\\','/',dirname(__FILE__)));

if (file_exists(_CMSAPI_LOCAL_DIRECTORY.'/cmsapi.joomla.integrated.php')) {
	require_once(_CMSAPI_LOCAL_DIRECTORY.'/cmsapi.joomla.integrated.php');
	$integrator = new cmsapiJoomlaIntegrated();
	$message = $integrator->installModulesPlugins($this);
	if ($message) echo '<br />Installation of modules/plugins had errors:<br />'.$message;
}

clearstatcache();
if (@file_get_contents(_CMSAPI_LOCAL_DIRECTORY.'/cms_is_joomla.php')) include_once(JPATH_SITE.'/plugins/cmsapi/plgCmsapiLoad.php');

// Define CMS environment
if (!defined('_CMSAPI_ADMIN_SIDE')) define('_CMSAPI_ADMIN_SIDE', 1);
foreach (array('aliro','mambo','joomla') as $cmsapi_cms) @include(dirname(__FILE__)."/cms_is_$cmsapi_cms.php");
require(_CMSAPI_ABSOLUTE_PATH.'/components/com_glossary/glossary.autoload.php');
// End of CMS environment definition


$gconfig = cmsapiConfiguration::getInstance('com_glossary', true);

function com_install () {
	
	// This function should work for any component without alteration
	function makeMenuEntry ($database) {
		if ('Aliro' == _CMSAPI_CMS_BASE) return;
		$compname = 'Glossary';
		$shortname = 'glossary';
		$option = 'com_glossary';
		$database->setQuery("SELECT MIN(id) FROM `#__components` WHERE `option` = '$option'");
		$compnum = intval($database->loadResult());
		$database->setQuery("SELECT count(*) FROM `#__menu` WHERE published > 0 AND link = 'index.php?option=$option'");
		if (!$database->loadResult()) {
			$database->setQuery("SELECT MAX(ordering) FROM `#__menu`");
			$ordering = intval($database->loadResult() + 1);
			if ('Joomla' == _CMSAPI_CMS_BASE) $database->setQuery("INSERT INTO `#__menu` "
			." (`id`, `menutype`, `name`, `alias`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`) "
			." VALUES (NULL , 'mainmenu', '$compname', '$shortname', 'index.php?option=$option', 'component', '1', '0', $compnum, '0', $ordering, '0', '0000-00-00 00:00:00', '0', '0', '0', '0', '')");
			else $database->setQuery("INSERT INTO `#__menu` "
			." (`id`, `menutype`, `name`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`) "
			." VALUES (NULL , 'mainmenu', '$compname', 'index.php?option=$option]', 'components', '1', '0', $compnum, '0', $ordering, '0', '0000-00-00 00:00:00', '0', '0', '0', '0', '')");
			$database->query();
		}
		else {
			$database->setQuery("UPDATE #__menu SET componentid = $compnum WHERE link LIKE 'index.php?option=$option%'");
			$database->query();
		}
	}
	
	function updateDB ($database) {
		$database->setQuery ("SHOW COLUMNS FROM #__glossaries");
		$fields = $database->loadObjectList();
		$fieldnames = array();
		foreach ($fields as $field) $fieldnames[] = $field->Field;

		if (!in_array('language', $fieldnames)) {
			$sql = 'ALTER TABLE `#__glossaries`'
			   	.' ADD `language` varchar(25) NOT NULL AFTER `description`;';
			$database->setQuery($sql);
			$database->query();
		}

		$database->setQuery ("SHOW COLUMNS FROM #__glossary");
		$fields = $database->loadObjectList();
		$fieldnames = array();
		foreach ($fields as $field) $fieldnames[] = $field->Field;

		if (!in_array('tfirst', $fieldnames)) {
			$sql = 'ALTER TABLE `#__glossary`'
			   	.' ADD `tfirst` varchar(255) NOT NULL AFTER `tterm`;';
			$database->setQuery($sql);
			$database->query();
		}

		if (!in_array('trelated', $fieldnames)) {
			$sql = 'ALTER TABLE `#__glossary`'
			   	.' ADD `trelated` text NOT NULL AFTER `tdefinition`;';
			$database->setQuery($sql);
			$database->query();
		}
		$database->setQuery ("SHOW COLUMNS FROM #__glossary_aliases");
		$fields = $database->loadObjectList();
		$fieldnames = array();
		foreach ($fields as $field) $fieldnames[] = $field->Field;

		if (!in_array('tfirst', $fieldnames)) {
			$sql = 'ALTER TABLE `#__glossary_aliases`'
			   	.' ADD `tfirst` varchar(255) NOT NULL AFTER `termalias`;';
			$database->setQuery($sql);
			$database->query();
		}
	}

	$interface = cmsapiInterface::getInstance();
	$database = $interface->getDB();
	updateDB($database);
	makeMenuEntry($database);
	// This code is application specific
	glossaryEntry::setFirstWords(5000);
	$database->setQuery("SELECT COUNT(*) FROM #__glossaries");
	if (!$database->loadResult()) {
		$database->setQuery("SELECT id, name, description, published FROM #__categories WHERE section = 'com_glossary'");
		$categories = $database->loadObjectList();
		if ($categories) {
			foreach ($categories as $category) {
				$database->setQuery("INSERT INTO #__glossaries (id, name, description, published) VALUES ('$category->id', '$category->name', '$category->description', $category->published)");
				$database->query();
			}
		}
		else {
			$database->setQuery("INSERT INTO #__glossaries (name, description, published) VALUES ('Glossary', 'Glossary of terms used on this site', 1)");
			$database->query();
		}
	}
}

