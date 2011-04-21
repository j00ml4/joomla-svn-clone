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

class glossaryGlossaryManager extends cachedSingleton {
	protected static $instance = __CLASS__;

	protected $glossaries = array();

	protected function __construct () {
		$interface = cmsapiInterface::getInstance();
		$database = $interface->getDB();
		$this->glossaries = $database->doSQLget("SELECT * FROM #__glossaries", 'glossaryGlossary', 'id');
	}

	public static function getInstance () {
	    return (self::$instance instanceof self) ? self::$instance : (self::$instance = parent::getCachedSingleton(__CLASS__));
	}

	public function getByID ($id, $onlyPublished=true) {
		$result = isset($this->glossaries[$id]) ? $this->glossaries[$id] : null;
		return empty($result) ? null : ($result->published OR !$onlyPublished) ? $result : null;
	}

	public function getDefault ($onlyPublished=true) {
		foreach ($this->glossaries as $glossary) if ($glossary->isdefault AND $glossary->published) return $glossary;
		foreach ($this->glossaries as $glossary) if ($glossary->published OR !onlyPublished) return $glossary;
		return null;
	}

	public function getOthers ($id, $onlyPublished=true) {
		$results = $this->getAll($onlyPublished);
		if (isset($results[$id])) unset($results[$id]);
		return $results;
	}

	public function getAll ($onlyPublished=true) {
		return $onlyPublished ? array_filter($this->glossaries, array($this,'isPublished')) : $this->glossaries;
	}

	// Not really public, but has to be declared so to use as a callback
	public function isPublished ($glossary) {
		return $glossary->published ? true : false;
	}


}