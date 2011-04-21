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

class glossaryAlphabetHTML extends glossaryHTML {
	
	public function view ($glossary, $letters, $currentletter, $numeric, $linkcurrent) {
		$interface =& cmsapiInterface::getInstance();
		$baselink = 'index.php?option=com_glossary&task=list';
		if ($glossary->id) $baselink .= '&glossid='.$glossary->id;
		$baselink .= '&letter=';
		if ($numeric) $letterhtml[] = ('9' == $currentletter ? '0-9' : "<a href=\"{$baselink}9\">" . '0-9' . "</a>");
		if ($letters) foreach ($letters as $letter) {
			$displayletter = 'all' == $letter ? _GLOSSARY_ALL : $letter;
			if ($currentletter == $letter AND !$linkcurrent) $letterhtml[] = <<<NO_LINK

			<span class="glossletselect">{$this->show($displayletter)}</span>

NO_LINK;

			else {
				$link = $this->interface->sefRelToAbs($baselink.urlencode($letter));
				$letterhtml [] = <<<LETTER_LINK

			<a href="$link">{$this->show($displayletter)}</a>

LETTER_LINK;

			}
		}
		$alphabet = implode (' ', $letterhtml);
		return <<<LIST_ALPHA
		
		<div class="glossaryalphabet">
			$alphabet
		</div>
		
LIST_ALPHA;
		
	}
	
}
