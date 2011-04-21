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

class glossaryEntry extends cmsapiDatabaseRow {
	protected $tableName = '#__glossary';
	protected $rowKey = 'id';
	public $taliases = '';

	public function load ($key=null) {
		parent::load($key);
		if ($this->id) {
			$database = cmsapiInterface::getInstance()->getDB();
			$database->setQuery("SELECT termalias FROM #__glossary_aliases WHERE termid = $this->id");
			$aliases = $database->loadResultArray();
			if (empty($aliases)) $aliases = array();
			$thisterm = array_search($this->tterm, $aliases);
			if (false !== $thisterm) unset($aliases[$thisterm]);
			$this->taliases = implode(' | ', $aliases);
		}
	}

	public function store ($updateNulls=false) {
		glossaryEntryManager::getInstance()->cacheEntry($this);
		$database = cmsapiInterface::getInstance()->getDB();
		$database->doSQL("DELETE FROM #__glossary_aliases WHERE termid = $this->id");
		$this->tfirst = glossaryEntry::getFirstWord($this->tterm);
		parent::store($updateNulls);
		if (trim($this->taliases)) {
			$aliases = explode('|', $this->taliases);
			$sql = "INSERT INTO #__glossary_aliases (termalias, termid) VALUES";
			foreach ($aliases as $alias) {
				$alias = $database->getEscaped(trim($alias));
				$aliasvalue[] = " ('$alias', $this->id)";
			}
			$sql .= implode(',', $aliasvalue);
			$database->doSQL($sql);
		}
		if (!$this->tletter) {
			$database->doSQL("UPDATE #__glossary SET tletter = UPPER(SUBSTRING(tterm,1,1)) WHERE tletter = ''");
		}
		$database->doSQL("TRUNCATE TABLE #__glossary_cache");
	}

	// Helper method for choosing a reasonable first word from a term
	public static function getFirstWord ($term) {
		//preg_match_all('/(^|\W)([^\W]*)/', $term, $matches);
		$words = preg_split('/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/u', strip_tags($term), -1, PREG_SPLIT_NO_EMPTY);
		$maxword = '';
		//foreach ($matches[2] as $word) {
		foreach ($words as $word) {
			if (2 < strlen($word) AND !is_numeric($word)) {
				$first = $word;
				break;
			}
			if (strlen($word) > strlen($maxword)) $maxword = $word;
		}
		return isset($first) ? $first : $maxword;
	}

	// Helper method for setting first words in the glossary table
	public static function setFirstWords ($max=5000) {
		$database = cmsapiInterface::getInstance()->getDB();
		$database->setQuery("SELECT tterm FROM #__glossary WHERE tfirst = '' LIMIT $max");
		$terms = $database->loadResultArray();
		$sql = glossaryEntry::firstWordSQL($terms, 'tterm', $database);
		if ($sql) {
			$database->doSQL("UPDATE #__glossary ".$sql);
			$modified = true;
		}
		$database->setQuery("SELECT termalias FROM #__glossary_aliases WHERE tfirst = '' LIMIT $max");
		$aliases = $database->loadResultArray();
		$sql = glossaryEntry::firstWordSQL($aliases, 'termalias', $database);
		if ($sql) {
			$database->doSQL("UPDATE #__glossary_aliases ".$sql);
			$modified = true;
		}
		if (!empty($modified)) $database->doSQL("TRUNCATE TABLE #__glossary_cache");
	}

	private function firstWordSQL ($terms, $fieldname, $database) {
		$cases = '';
		if (!empty($terms)) foreach ($terms as $sub=>$term) {
			$first = $database->getEscaped(glossaryEntry::getFirstWord($term));
			$term = $database->getEscaped($term);
			$eterms[] = $term;
			$cases .= "\n WHEN '$term' THEN '$first'";
		}
		if ($cases) {
			$termlist = implode("','", $eterms);
			return " SET tfirst = CASE $fieldname $cases END WHERE $fieldname IN ('$termlist')";
		}
	}
}