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

class glossaryAdminGlossaries extends cmsapiAdminControllers {

	public function listTask () {
		$database = $this->interface->getDB();
		$search = $this->interface->getParam($_REQUEST, 'search', '');
		$search = $database->getEscaped($search);
		$sql = "SELECT COUNT(*) FROM #__glossaries";
		if ($search) $sql .= " WHERE name LIKE '%$search%' OR description LIKE '%$search%' ";
		$database->setQuery($sql);
		$total = $database->loadResult();
		$sql = "SELECT * FROM #__glossaries";
		if ($search) $sql .= " WHERE description LIKE '%$search%' ";
		$sql .= " ORDER BY name LIMIT {$this->admin->limitstart}, {$this->admin->limit}";
		$database->setQuery($sql);
		$glossaries = $database->loadObjectList();
		if (!$glossaries) $glossaries = array();
		// Create and activate a View object
		$view = $this->admin->newHTMLClassCheck ('listGlossariesHTML', $this, $total, '');
		$view->view($glossaries, $search);
	}
	
	public function editTask () {
		$database = $this->interface->getDB();
		$glossary = new glossaryGlossary($database);
		if ($this->idparm) $glossary->load($this->idparm);
		// Create and activate a View object
		$view = $this->admin->newHTMLClassCheck ('editGlossariesHTML', $this, 0, '');
		$view->edit($glossary);
	}
	
	public function addTask () {
		$this->editTask();
	}
	
	public function saveTask () {
		$message = $this->commonSave();
		$this->interface->redirect("index2.php?option=com_glossary&act=glossaries", $message);
	}
	
	public function applyTask () {
		$message = $this->commonSave();
		$this->interface->redirect("index2.php?option=com_glossary&act=glossaries&task=edit&id=".$this->idparm, $message);
	}
	
	private function commonSave () {
		$database = $this->interface->getDB();
		$glossary = new glossaryGlossary($database);
		$glossary->published = 0;
		$glossary->bind($_POST);
		if ($glossary->name) {
			$this->idparm = $glossary->store();
			return '';
		}
		return _GLOSSARY_MUST_HAVE_NAME;
	}
	
	public function deleteTask () {
		$database = $this->interface->getDB();
		$cfid = $this->interface->getParam($_REQUEST, 'cfid', array());
		foreach ($cfid as $key=>$value) $cfid[$key] = intval($value);
		$idlist = implode(',', $cfid);
		if ($idlist) {
			$database->setQuery("DELETE FROM #__glossaries WHERE id IN ($idlist)");
			$database->query();
		}
		$this->interface->redirect("index2.php?option=com_glossary&act=glossaries");
	}

	public function publishTask () {
		$cfid = $this->interface->getParam($_REQUEST, 'cfid', array());
		$this->publishToggle('#__glossaries', $cfid, 1);
		$this->interface->redirect("index2.php?option=com_glossary&act=glossaries");
	}

	public function unpublishTask () {
		$cfid = $this->interface->getParam($_REQUEST, 'cfid', array());
		$this->publishToggle('#__glossaries', $cfid, 0);
		$this->interface->redirect("index2.php?option=com_glossary&act=glossaries");
	}

}