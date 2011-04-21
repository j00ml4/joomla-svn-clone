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

class glossary_list_Controller extends glossaryUserControllers {

	function glossary_list ($task) {
		$interface = cmsapiInterface::getInstance();
		$database = $interface->getDB();
		$my = $interface->getUser();

		$glosshtmltop = $glosshtmlbottom = '';
		$glossaries = glossaryGlossaryManager::getInstance()->getOthers($this->glossid);
		$moreglossaries = empty($glossaries) ? false : true;
		if ($this->gconfig->showcategories AND $moreglossaries) {
			$glister = new glossaryGlossaryHTML();
			$glosshtmltop = $this->gconfig-> categoriesabove ? $glister->view($glossaries) : '';
			$glosshtmlbottom = $this->gconfig-> categoriesabove ? '' : $glister->view($glossaries);
		}

		$searchword = $interface->getParam($_REQUEST, 'glossarysearchword');
		$searchmethod = $interface->getParam($_REQUEST, 'glossarysearchmethod', 1);
		if ($this->gconfig->show_search) {
			$searching = new glossarySearchHTML;
			$Itemid = $interface->getParam($_REQUEST, 'Itemid', 0);
			$Itemidline = $Itemid ? sprintf('<input type="hidden" name="Itemid" value="%s" />', $Itemid) : '';
			$manygloss = $interface->getParam($_REQUEST, 'manygloss', 0);
			$searchhtml = $searching->view($this->glossary, $searchword, $searchmethod, $Itemidline, $moreglossaries, $manygloss);
		}
		else $searchhtml = $searchword = '';
		$searchword = $database->getEscaped($searchword);

		$letter = urldecode($interface->getParam($_REQUEST, 'letter', 'all'));
		$letter = $database->getEscaped($letter);

		$id = $interface->getParam($_REQUEST, 'id', 0);
		if ($this->gconfig->show_alphabet OR $this->gconfig->show_alphabet_below) {
			if ($this->glossary->id) $where[] = 'catid='.$this->glossary->id;
			$where[] = "published != 0";
			$where[] = "tletter NOT RLIKE '[0-9]'";
			$sql = "SELECT DISTINCT tletter FROM #__glossary WHERE ".implode(' AND ', $where).' ORDER BY tletter';
			$database->setQuery($sql);
			$letters = $database->loadResultArray();
			array_unshift($letters, 'all');
			array_pop($where);
			$where[] = "tletter RLIKE '[0-9]'";
			$database->setQuery("SELECT COUNT(*) FROM #__glossary WHERE ".implode(' AND ', $where));
			$numeric = $database->loadResult() ? true : false;
			unset($where);
			$alphabet = new glossaryAlphabetHTML();
			$alphabethtml = $alphabet->view($this->glossary, $letters, $letter, $numeric, ($id OR $searchword));
		}
		else $alphabethtml = '';

		if ($id) {
			$where[] = 'id = '.$id;
			$entry = glossaryEntryManager::getInstance()->getByID($id);
			if ($entry) $entries = array($entry);
			else $this->return404();
			$interface->addMetaTag('description', strip_tags($entry->tdefinition));
		}
		elseif ($this->glossary instanceof glossaryGlossary) $interface->addMetaTag('description', strip_tags($this->glossary->description));

		$where[] = 'published != 0';
		if ($this->glossid AND empty($manygloss)) $where[] = "catid = $this->glossid";
		if ($letter) {
			if ('9' == $letter) $where[] = "tletter RLIKE '[0-9]'";
			elseif ('all' != $letter) $where[] = "tletter = '$letter'";
		}
		if ($searchword) $where[] = $this->searchSQL($searchword, $searchmethod, $where, $database);

		$sql = 'SELECT COUNT(*) FROM #__glossary';
		if ($this->gconfig->shownumberofentries) {
			$database->setQuery($sql." WHERE catid = $this->glossid");
			$grandtotal = $database->loadResult();
		}
		else $grandtotal = 0;

		if (empty($total)) {
			if (isset($where)) $sql .= ' WHERE '.implode(' AND ', $where);
			$database->setQuery($sql);
			$total = $database->loadResult();
		}

		if ($total) {
			if (empty($entries)) {
				$sql = "SELECT * FROM #__glossary";
				if (isset($where)) $sql .= ' WHERE '.implode(' AND ', $where);
				$page = $interface->getParam($_REQUEST, 'page', 1);
				$querystring = "&task=list&glossid=$this->glossid&letter=".urlencode($letter);
				if ($searchword) $querystring .= "&glossarysearchword=$searchword&glossarysearchmethod=$searchmethod";
				$pagecontrol = new cmsapiUserPage ( $total, $my, $this->gconfig->perpage, $page, $querystring );
				$sql .= ' ORDER BY tterm';
				$sql .= " LIMIT $pagecontrol->startItem, $pagecontrol->itemsperpage";
				$entries = $database->doSQLget($sql);
				glossaryEntryManager::getInstance()->addAliases($entries);
			}
			else $pagecontrol = null;
			$listing = new glossaryListHTML;
			$listhtml = $listing->view($entries, $letter, $grandtotal, $id);
		}
		else {
			$listhtml = _GLOSSARY_IS_EMPTY;
			$pagecontrol = null;
		}

		$title = $this->glossary->name;
		if ($id AND !empty($entries)) $title = $entries[0]->tterm.' | '.$title;
		else $title = $letter.' | '.$title;
		$interface->setPageTitle($title);

		if (!empty($entries)) foreach ($entries as $entry) $keyword[] = $entry->tterm;
		if (isset($keyword)) $interface->addMetaTag('keywords', implode(', ', $keyword));

		$allowentry = ($this->gconfig->anonentry OR ($this->gconfig->allowentry AND $my->id));
		$lister = new glossaryUserHTML;
		$lister->view($this->glossary, $grandtotal, $allowentry, $glosshtmltop, $glosshtmlbottom, $searchhtml, $alphabethtml, $listhtml, $pagecontrol, $id);
	}

	function searchSQL ($word, $method, $where, $database) {
		if (4 == $method) return "SOUNDEX(tterm) = SOUNDEX('$word')";
		if (3 == $method) $word = "^$word$";
		if (1 == $method) $word = '^'.$word;
		$condition = " AND (tterm RLIKE '$word' OR termalias RLIKE '$word')";
		$sql = "SELECT DISTINCT id FROM #__glossary LEFT JOIN #__glossary_aliases ON id = termid";
		$sql .= ' WHERE '.implode(' AND ', $where).$condition;
		$sql .= ' GROUP BY id';
		$database->setQuery($sql);
		$ids = $database->loadResultArray();
		if (empty($ids)) return 'id = 0';
		$idlist = implode(',', $ids);
		return "id IN ($idlist)";
	}

}