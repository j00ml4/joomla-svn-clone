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

class glossaryUserHTML extends glossaryHTML {
	
	function view ($glossary, $grandtotal, $allowentry, $glosshtmltop, $glosshtmlbottom, $searchhtml, $alphabethtml, $listhtml, $pagecontrol) {
		if ($this->gconfig->show_list) {
			$navigation = $pagecontrol ? $pagecontrol->showNavigation($this->gconfig->pagespread) : '';
			$alphabetabove = $this->gconfig->show_alphabet ? $alphabethtml : '';
			$alphabetbelow = $this->gconfig->show_alphabet_below ? $alphabethtml : '';
			$item_list = $this->makeItemList ($alphabetabove, $navigation, $listhtml, $navigation, $alphabetbelow);
		}
		else $item_list = '';
		$itemcount = $grandtotal ? sprintf(_GLOSSARY_ITEM_COUNT, $grandtotal) : '';
		$interface =& cmsapiInterface::getInstance();
		if ($allowentry) {
			$addlink = $interface->sefRelToAbs("index.php?option=com_glossary&task=edit&id=0&glossid=".$glossary->id);
			$addhtml = $this->showLinkToEntryForm($addlink);
		}
		else $addhtml = '';

		echo $this->showGlossaryPage($glossary, $glosshtmltop, $itemcount, $addhtml, $searchhtml, $item_list, $glosshtmlbottom);
	}
	
	protected function makeItemList ($alphabetabove, $navigation, $listhtml, $navigation, $alphabetbelow) {
		return <<<ITEM_LIST
			
			$alphabetabove
			$navigation
			$listhtml
			$navigation
			$alphabetbelow
			
ITEM_LIST;
		
	}
	
	protected function showLinkToEntryForm ($addlink) {
		return <<<ADD_ENTRY_HTML

			<a href="$addlink">{$this->show(_GLOSSARY_ADD_ENTRY)}</a>
		
ADD_ENTRY_HTML;

	}

	protected function showGlossaryPage ($glossary, $glosshtmltop, $itemcount, $addhtml, $searchhtml, $item_list, $glosshtmlbottom) {
		return <<<GLOSSARY_DISPLAY
		
		<h2>{$this->showHTML($glossary->description)}</h2>
		$glosshtmltop
		$itemcount
		$addhtml
		$searchhtml
		$item_list
		$glosshtmlbottom
		<div id="glossarycredit" class="small">
			<a href='http://remository.com'>Glossary {$this->show(_GLOSSARY_VERSION)}</a> uses technologies including
			<a href='http://php-ace.com'>PHP</a> and
			<a href='http://sql-ace.com'>SQL</a>
		</div>

GLOSSARY_DISPLAY;

	}
	
}