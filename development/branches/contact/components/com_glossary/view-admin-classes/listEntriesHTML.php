<?php

/**************************************************************
* This file is part of Glossary
* Copyright (c) 2008-9 Martin Brampton
* Issued as open source under GNU/GPL
* For support and other information, visit http://remository.com
* To contact Martin Brampton, write to martin@remository.com
*
* More details in glossary.php
*/

class listEntriesHTML extends cmsapiAdminHTML {

	function __construct ($controller, $limit, $clist, $full_name) {
		parent::__construct($controller, $limit, $clist, $full_name);
	}

public function view ($entries, $glossaries, $search, $defn, $catid) {
		$parser_class = MARKDOWN_PARSER_CLASS;
		$parser = new $parser_class;
		$listing = '';
		$gmanager = glossaryGlossaryManager::getInstance();
		$i = 0;
		foreach ($entries as $i=>$entry) {
			$glossary = $gmanager->getByID($entry->catid);
			$glossaryname = is_object($glossary) ? $glossary->name : '';
			$entry->tdefinition = $parser->transform($entry->tdefinition);
			$link = <<<EDIT_LINK
index2.php?option=com_glossary&amp;act=entries&amp;task=edit&amp;id=$entry->id
EDIT_LINK;
			$listing .= <<<LIST_ITEM
			
				<tr>
					<td>
						<input type="checkbox" id="cb$i" name="cfid[]" value="$entry->id" onclick="isChecked(this.checked);" />
					</td>
					<td>$entry->id</td>
					<td><a href="$link">{$this->showHTML($entry->tterm)}</a></td>
					<td>$entry->tletter</td>
					<td>$glossaryname</td>
					<td width="60%">{$this->showHTML($entry->tdefinition)}</td>
					<td>$entry->tdate</td>
					<td>$entry->published</td>
				</tr>
			
LIST_ITEM;

			$i++;
		}
		$count = count($entries);
		echo <<<GLOSSARY_HEAD
		
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
		<script type="text/javascript" src="../includes/js/overlib_mini.js"></script>
		<form action="index2.php" method="post" name="adminForm">
		<table id="glossaryheader" cellpadding="4" cellspacing="0" border="0" width="100%">
   		<tr>
			<td class="glossarytitle">
			<div class="title header">
				<img src="../components/com_glossary/images/glosslogo.png" alt="" />
				<span class="sectionname">
					{$this->show(_GLOSSARY_COMPONENT_TITLE)} - {$this->show(_GLOSSARY_LIST_ENTRIES)}
				</span>
			</div>
			</td>
    	</tr>
    	<tr>
    		<td></td>
    		<td>
				{$this->show(_GLOSSARY_FILTER_TERM)}
				<input type="text" name="search" value="$search" size="30" onchange="document.adminForm.submit();" />
			</td>
    		<td>
				{$this->show(_GLOSSARY_FILTER_DEFINITION)}
				<input type="text" name="defn" value="$defn" size="30" onchange="document.adminForm.submit();" />
			</td>
    		<td>
				<select name="catid" id="glossaryselect" onchange="document.adminForm.submit();">
					{$this->glossarySelect($glossaries, $catid)}
				</select>
			</td>
    		<td>
    			<noscript><input type="submit" value="{$this->show(_GLOSSARY_REFRESH)}" /></noscript>
    		</td>
    	</tr>
    	</table>
		<table id="glossarylist" cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<thead>
				<tr>
					<th width="5" align="left">
						<input type="checkbox" name="toggle" value="" onclick="checkAll($count);" />
					</th>
					<th>{$this->show(_GLOSSARY_ID)}</th>
					<th>{$this->show(_GLOSSARY_TERM)}</th>
					<th>{$this->show(_GLOSSARY_LETTER)}</th>
					<th>{$this->show(_GLOSSARY_GLOSSARY_CAT)}</th>
					<th>{$this->show(_GLOSSARY_DEFINITION)}</th>
					<th>{$this->show(_GLOSSARY_DATE)}</th>
					<th>{$this->show(_GLOSSARY_PUBLISHED)}</th>
				</tr>
			</thead>
			
GLOSSARY_HEAD;

		$this->pageNav->listFormEnd('com_glossary');
		if (!$listing) $listing = '<tr><td></td></tr>';
		echo <<<GLOSSARY_LIST
		
			<tbody>
				$listing
			</tbody>
		</table>
		</form>
				
GLOSSARY_LIST;

	}
	
	private function glossarySelect($glossaries, $catid) {
		$options = '';
		foreach ($glossaries as $glossary) {
			$selected = ($catid == $glossary->id) ? 'selected="selected"' : '';
			$options .= <<<GLOSS_OPTION
	
			<option value="$glossary->id" $selected>$glossary->name</option>
		
GLOSS_OPTION;

		}
		return $options;
	}
	
}