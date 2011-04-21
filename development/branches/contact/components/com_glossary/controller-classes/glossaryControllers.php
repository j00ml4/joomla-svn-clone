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

// This is the base class for all user side controllers

abstract class glossaryUserControllers {
	protected $remUser = '';
	protected $admin = '';
	protected $idparm = 0;
	protected $glossid = 0;
	protected $Itemid = 0;
	protected $orderby = 0;
	protected $gconfig = null;
	protected $glossary = null;

	public function __construct ($admin) {
		$interface = cmsapiInterface::getInstance();
		$database = $interface->getDB();
		$this->gconfig = cmsapiConfiguration::getInstance('com_glossary');
		$this->admin = $admin;
		$this->idparm = $interface->getParam($_REQUEST, 'id', 0);
		if ($this->idparm) {
			$database->setQuery("SELECT * FROM #__glossary WHERE id = $this->idparm");
			$entries = $database->loadObjectList();
			if ($entries) {
				$this->glossid = $entries[0]->catid;
				$total = 1;
			}
		}
		$glossparam = $interface->getFromConfig('com_glossary', 'glossid', 0);
		if (0 == $this->glossid) $this->glossid = $interface->getParam($_REQUEST, 'glossid', $glossparam);
		if ($this->glossid) $this->glossary = glossaryGlossaryManager::getInstance()->getByID($this->glossid);
		else {
			$this->glossary = glossaryGlossaryManager::getInstance()->getDefault();
			$this->glossid = empty($this->glossary) ? 0 : $this->glossary->id;
		}
		$this->Itemid = $interface->getParam($_REQUEST, 'Itemid', 0);
		$this->orderby = $interface->getParam($_REQUEST, 'orderby', _COMPONENT_DEFAULT_ORDERING);
		$configuration = cmsapiConfiguration::getInstance('com_glossary');
		if (file_exists($interface->getCfg('absolute_path')."/components/com_glossary/css/glossary.css")) {
			$css = <<<GLOSSARY_USER_CSS
			
<link href="{$interface->getCfg('live_site')}/components/com_glossary/css/glossary.css" rel="stylesheet" type="text/css"/>

GLOSSARY_USER_CSS;

			$interface->addCustomHeadTag($css);
		}
		glossaryUserControllers::loadLanguage($interface, $this->gconfig, $this->glossary->language);
		$this->remUser = $interface->getUser();
	}

	public static function loadLanguage ($interface, $configuration, $forcelang='') {
		$absolute_path = $interface->getCfg('absolute_path');
		$lang = $forcelang ? $forcelang : ($configuration->language ? $configuration->language : $interface->getCfg('lang'));
		//Need config values for language files
		foreach (get_object_vars($configuration) as $k=>$v) $$k = $configuration->$k;
		if (file_exists($absolute_path."/components/com_glossary/"._CMSAPI_LANGFILE.$lang.'.php')) require_once($absolute_path."/components/com_glossary/"._CMSAPI_LANGFILE.$lang.'.php');
		require_once($absolute_path."/components/com_glossary/"._CMSAPI_LANGFILE."english.php");
	}
}