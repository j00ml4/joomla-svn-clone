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

class glossaryGlossaryHTML extends glossaryHTML {
	
	function view ($glossaries) {
		$interface =& cmsapiInterface::getInstance();
		$listhtml = '';
		foreach ($glossaries as $glossary) {
			$link = $interface->sefRelToAbs("index.php?option=com_glossary&letter=All&glossid=".$glossary->id);
			$listhtml .= <<<GLOSSARY_ITEM
		
			<div class="glossaryglossary">
				<a href="$link">
					{$this->showHTML($glossary->description)}
				</a>
			</div>
			
GLOSSARY_ITEM;

		}
		
		return <<<LIST_GLOSSARIES
		
		<div id="glossaryglossarylist">
			<h3>{$this->show(_GLOSSARY_GLOSSARY_LIST)}</h3>
			$listhtml
		</div>
		
LIST_GLOSSARIES;
		
	}
	
}