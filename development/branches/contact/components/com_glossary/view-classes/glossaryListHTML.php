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

class glossaryListHTML extends glossaryHTML {
	
	public function view ($entries, $letter, $total, $id) {
		
		$parser_class = MARKDOWN_PARSER_CLASS;
		$parser = new $parser_class;
		if ('all' == $letter) $letter = _GLOSSARY_ALL;
		elseif ('9' == $letter) $letter = '0-9';
		$listhtml = '';
		$interface = cmsapiInterface::getInstance();
        $rowcounter = 0;
		if (!empty($entries)) foreach ($entries as $entry) {
			$entry->tdefinition = $parser->transform($entry->tdefinition);
			$link = $interface->sefRelToAbs("index.php?option=com_glossary&letter=$entry->tletter&id=$entry->id");
            // rowcounter used to discriminate between odd and even rows
			$entryclass = (++$rowcounter % 2) ? 'row1' : 'row0';
			$listhtml .= $this->showOneTerm ($entryclass, $link, $entry, $id);
		}
		return $this->applyContentPlugins($this->showTermsList($letter, $listhtml));
	}
	
	protected function showDefinition ($entry, $id) {
		if ($this->gconfig->termsonly AND !$id) return '&nbsp;';
		else return <<<DEFINITION
		
					<div>
						{$this->showHTML($entry->tdefinition)}
					</div>
					{$this->showInDiv($entry->tcomment)}
					{$this->showAliases($entry->taliases)}
					{$this->showRelated($entry->trelated)}
		
DEFINITION;

	}

	protected function showOneTerm ($entryclass, $link, $entry, $id) {
			return <<<LIST_ENTRY

				<tr class="$entryclass">
					<td>
						<a href="$link">
							{$this->showHTML($entry->tterm)}
						</a>
					</td>
					<td>
						{$this->showDefinition($entry, $id)}
					</td>
				</tr>

LIST_ENTRY;

	}

	protected function showAliases ($aliases) {
		if ($aliases) return $this->showHTML($this->showHTMLInDiv('<em>'._GLOSSARY_ALIASES.'</em>: '.$aliases));
	}

	protected function showRelated ($related) {
		
	}

	protected function showTermsList ($letter, $listhtml) {
		return <<<GLOSS_LIST

		<table id="glossarylist" class="glossaryclear">
			<thead>
				<tr>
					<th class="glossary25">{$this->show(_GLOSSARY_TERM_HEAD)}</th>
					<th class="glossary72">{$this->show(_GLOSSARY_DEFINITION_HEAD)}</th>
				</tr>
			</thead>
			<tbody>
			$listhtml
			</tbody>
		</table>

GLOSS_LIST;

	}

	protected function applyContentPlugins ($text) {
		if ($this->gconfig->triggerplugins) {
			$row = new stdClass();
			$row->text = $text;
			$params = array();
			cmsapiInterface::getInstance()->triggerMambots('onPrepareContent', array($row, $params, 0), true);
			return $row->text;
		}
		else return $text;
	}
}
