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

class glossaryAdminEntries extends cmsapiAdminControllers {

	public function listTask () {
		$database = $this->interface->getDB();
		$catid = $this->interface->getParam($_REQUEST, 'catid', 0);
		if ($catid) $where[] = "catid = $catid";
		$search = $this->interface->getParam($_REQUEST, 'search');
		if ($search) {
			$search = $database->getEscaped($search);
			$where[] = "tterm RLIKE '$search'";
		}
		$defn = $this->interface->getParam($_REQUEST, 'defn');
		if ($defn) {
			$defn = $database->getEscaped($defn);
			$where[] = "MATCH (tdefinition) AGAINST ('$defn' IN BOOLEAN MODE)";
		}
		if (isset($where)) $condition = " WHERE ".implode(' AND ', $where);
		else $condition = '';
		$database->setQuery("SELECT COUNT(*) FROM #__glossary".$condition);
		$total = $database->loadResult();
		$database->setQuery("SELECT * FROM #__glossary $condition ORDER BY tterm LIMIT {$this->admin->limitstart}, {$this->admin->limit}");
		$entries = $database->loadObjectList();
		if (!$entries) $entries = array();
		$database->setQuery("SELECT id, name FROM #__glossaries");
		$glossaries = $database->loadObjectList();
		if (!$glossaries) $glossaries = array();
		$selectall = new stdClass();
		$selectall->id = 0;
		$selectall->name = _GLOSSARY_ALL_GLOSSARIES;
		array_unshift($glossaries, $selectall);
		// Create and activate a View object
		$view = $this->admin->newHTMLClassCheck ('listEntriesHTML', $this, $total, '');
		$view->view($entries, $glossaries, $search, $defn, $catid);
	}
	
	public function editTask () {
		$entry = new glossaryEntry();
		if ($this->idparm) $entry->load($this->idparm);
		$glossaries = glossaryGlossaryManager::getInstance()->getAll(false);
		// Create and activate a View object
		$view = $this->admin->newHTMLClassCheck ('editEntriesHTML', $this, 0, '');
		$view->edit($entry, $glossaries);
	}
	
	public function addTask () {
		$this->editTask();
	}
	
	public function saveTask () {
		$this->commonSave();
		$this->interface->redirect("index2.php?option=com_glossary&act=entries");
	}
	
	public function applyTask () {
		$id = $this->commonSave();
		$this->interface->redirect("index2.php?option=com_glossary&act=entries&task=edit&id=".$id);
	}
	
	private function commonSave () {
		$database = $this->interface->getDB();
		$entry = new glossaryEntry($database);
		$entry->published = 0;
		$entry->bind($_POST);
		$entry->taliases = $this->interface->getParam($_POST, 'taliases');
		$this->checkDate($entry->tdate);
		$entry->teditdate = '';
		$this->checkDate($entry->teditdate);
		if ($entry->tterm AND $entry->tdefinition) {
			$entry->store();
			glossaryEntryManager::deleteCache();
		}
		return $entry->id;
	}
	
	private function checkDate (&$date) {
		if (!$date OR '0000-00-00 00:00:00' == $date) $date = date('Y-m-d H:i:s');
		else {
			$time = strtotime($date);
			if (false == $time OR -1 == $time) $date = date('Y-m-d H:i:s');
			else $date = date('Y-m-d H:i:s', $time);
		}
	}

	public function deleteTask () {
		$cfid = $this->interface->getParam($_REQUEST, 'cfid', array());
		glossaryEntryManager::getInstance()->deleteByID($cfid);
		$this->interface->redirect("index2.php?option=com_glossary&act=entries");
	}
	
	public function publishTask () {
		$cfid = $this->interface->getParam($_REQUEST, 'cfid', array());
		$this->publishToggle('#__glossary', $cfid, 1);
		$this->interface->redirect("index2.php?option=com_glossary&act=entries");
	}

	public function unpublishTask () {
		$cfid = $this->interface->getParam($_REQUEST, 'cfid', array());
		$this->publishToggle('#__glossary', $cfid, 0);
		$this->interface->redirect("index2.php?option=com_glossary&act=entries");
	}

}