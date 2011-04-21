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

class glossaryEntryManager extends cachedSingleton {
	protected static $instance = __CLASS__;

	protected $entries = array();

	public static function getInstance () {
	    return (self::$instance instanceof self) ? self::$instance : (self::$instance = parent::getCachedSingleton(__CLASS__));
	}

	public function getByID ($id, $onlyPublished=true) {
		$id = intval($id);
		if (!$id) return null;
		$result = isset($this->entries[$id]) ? $this->entries[$id] : $this->readByID($id);
		return empty($result) ? null : ($result->published OR !$onlyPublished) ? $result : null;
	}

	protected function readByID ($id) {
		$interface = cmsapiInterface::getInstance();
		$database = $interface->getDB();
		$entries = $database->doSQLget("SELECT * FROM #__glossary WHERE id = $id", 'glossaryEntry', 'id');
		$this->addAliases($entries);
		$this->entries[$id] = isset($entries[$id]) ? $entries[$id] : null;
		$this->cacheNow(false);
		return $this->entries[$id];
	}

	public function cacheEntry ($entry) {
		$this->entries[$entry->id] = $entry;
		$this->cacheNow();
	}

	// The parameter is a single ID number or an array of ID numbers
	public function deleteByID ($ids) {
		$ids = array_map('intval', (array) $ids);
		$idlist = implode(',', $ids);
		if ($idlist) {
			$database = cmsapiInterface::getInstance()->getDB();
			$database->doSQL("DELETE FROM #__glossary WHERE id IN ($idlist)");
			foreach ($ids as $id) if (isset($this->entries[$id])) unset($this->entries[$id]);
			$this->cacheNow();
		}
	}

	// Purely a helper method that doesn't really belong anywhere in particular
	public static function deleteCache () {
		$database = cmsapiInterface::getInstance()->getDB();
		$database->doSQL("TRUNCATE TABLE #__glossary_cache");
	}

	public function addAliases (&$entries) {
		$interface = cmsapiInterface::getInstance();
		$database = $interface->getDB();
		foreach ($entries as $entry) $terms[] = $entry->id;
		if (isset($terms)) {
			$termlist = implode(',', $terms);
			$results = $database->doSQLget("SELECT termid, termalias FROM #__glossary_aliases WHERE termid IN ($termlist)");
			foreach ($results as $result) $aliases[$result->termid][] = $result->termalias;
			foreach ($entries as $entry) {
				$entry->taliases = isset($aliases[$entry->id]) ? implode('|', $aliases[$entry->id]) : '';
			}
		}
	}
}