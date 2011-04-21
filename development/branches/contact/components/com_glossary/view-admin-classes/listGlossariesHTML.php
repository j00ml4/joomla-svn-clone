<?php

/**************************************************************
* This file is part of Glossary
* Copyright (c) 2008 Martin Brampton
* Issued as open source under GNU/GPL
* For support and other information, visit http://remository.com
* To contact Martin Brampton, write to martin@remository.com
*
* More details in glossary.php
*/

class listGlossariesHTML extends cmsapiAdminHTML {

	function __construct ($controller, $limit, $clist, $full_name) {
		parent::__construct($controller, $limit, $clist, $full_name);
	}

	function view ($glossaries, $search) {
		$listing = '';
		$i = 0;
		foreach ($glossaries as $i=>$glossary) {
			$listing .= <<<LIST_ITEM
			
				<tr>
					<td>
						<input type="checkbox" id="cb$i" name="cfid[]" value="$glossary->id" onclick="isChecked(this.checked);" />
					</td>
					<td>$glossary->id</td>
					<td><a href="index2.php?option=com_glossary&amp;act=glossaries&amp;task=edit&amp;id=$glossary->id">$glossary->name</a></td>
					<td>$glossary->description</td>
					<td>$glossary->published</td>
				</tr>
			
LIST_ITEM;

			$i++;
		}
		$count = count($glossaries);
		echo <<<GLOSSARY_LIST
		
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
		<script type="text/javascript" src="../includes/js/overlib_mini.js"></script>
		<form action="index2.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%">
   		<tr>
			<td width="75%" colspan="3">
			<div class="title header">
				<img src="../components/com_glossary/images/glosslogo.png" alt="" />
				<span class="sectionname">
					{$this->show(_GLOSSARY_COMPONENT_TITLE)} - {$this->show(_GLOSSARY_LIST_GLOSSARIES)}
				</span>
			</div>
			</td>
			<td width="25%">
			</td>
    	</tr>
    	</table>
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<thead>
				<tr>
					<th width="5" align="left">
						<input type="checkbox" name="toggle" value="" onclick="checkAll($count);" />
					</th>
					<th>{$this->show(_GLOSSARY_ID)}</th>
					<th>{$this->show(_GLOSSARY_NAME)}</th>
					<th>{$this->show(_GLOSSARY_DESCRIPTION)}</th>
					<th>{$this->show(_GLOSSARY_PUBLISHED)}</th>
				</tr>
			</thead>
			
GLOSSARY_LIST;
			
		$this->pageNav->listFormEnd('com_glossary');
		echo <<<GLOSSARY_LIST
					
			<tbody>
				$listing
			</tbody>
		</table>
		</form>				
				
GLOSSARY_LIST;

	}
}