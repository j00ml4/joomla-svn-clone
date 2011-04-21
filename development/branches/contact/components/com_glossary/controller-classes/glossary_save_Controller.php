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

class glossary_save_Controller extends glossaryUserControllers {
	
	public function glossary_save ($task) {
		$interface = cmsapiInterface::getInstance();
		$my = $interface->getUser();
		$database = $interface->getDB();
		if (!$this->gconfig->anonentry AND !($this->gconfig->allowentry AND $my->id)) die ('Illegal attempt to edit');
		$entry = new glossaryEntry($database);
		$entry->bind($_POST);
		$entry->taliases = $interface->getParam($_POST, 'taliases');
		$entry->tdefinition = strip_tags($entry->tdefinition);
		$entry->tterm = strip_tags($entry->tterm);
		$this->checkDate($entry->tdate);
		$entry->teditdate = '';
		$this->checkDate($entry->teditdate);
		$entry->published = $this->gconfig->autopublish ? 1 : 0;
		$entry->taliases = $interface->getParam($_POST, 'taliases');
		$entry->store();
		if ($this->gconfig->thankuser AND $entry->tmail AND $this->gconfig->from_email) {
			$glossname = $this->glossid ? $this->glossary->name : 'Glossary';
			$subject = $glossname.' - '.$entry->tterm;
			$interface->sendMail($this->gconfig->from_email, $glossname, $entry->tmail, $subject, $this->gconfig->mail_thanks);
		}
		if ($this->gconfig->notify AND $this->gconfig->notify_email) {
			$glossname = $this->glossid ? $this->glossary->name : 'Glossary';
			$subject = $glossname.' - '.$entry->tterm;
			$body = sprintf(_GLOSSARY_MAIL_ALERT, $my->username, $entry->tterm, $glossname);
			$interface->sendMail($this->gconfig->from_email, $glossname, $this->gconfig->notify_email, $subject, $body);
		}
		$interface->redirect($interface->getCfg('live_site')."/index.php?option=com_glossary&glossid=$entry->catid");		
	}
	
	private function checkDate (&$date) {
		if (!$date OR '0000-00-00 00:00:00' == $date) $date = date('Y-m-d H:i:s');
		else {
			$time = strtotime($date);
			if (false == $time OR -1 == $time) $date = date('Y-m-d H:i:s');
			else $date = date('Y-m-d H:i:s', $time);
		}
	}

}
