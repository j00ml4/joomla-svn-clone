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

class glossary_edit_Controller extends glossaryUserControllers {
	
	public function glossary_edit ($task) {
		$interface = cmsapiInterface::getInstance();
		$my = $interface->getUser();
		$database = $interface->getDB();
		if (!$this->gconfig->anonentry AND !($this->gconfig->allowentry AND $my->id)) die ('Illegal attempt to edit');

		$database->setQuery("SELECT * FROM #__glossaries WHERE published != 0");
		$glossaries = $database->loadObjectList();

		$gitem = new glossaryEntry($database);
		if ($this->idparm) $gitem->load($this->idparm);
		else $gitem->catid = $this->glossid;
		
		if ($my->id) {
			$database->setQuery("SELECT name, email FROM #__users WHERE id = $my->id");
			$userentry = $database->loadObjectList();
			if ($userentry) {
				$my->email = $userentry[0]->email;
				$my->name = $userentry[0]->name;
			}
		}
		
		$editor = new glossaryEditHTML;
		$editor->edit($gitem, $my, $this->gconfig, $glossaries);
	}
	
}