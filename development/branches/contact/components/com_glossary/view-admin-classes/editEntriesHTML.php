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

class editEntriesHTML extends cmsapiAdminHTML {
	
	function edit ($entry, $glossaries) {
		$yescheck = $entry->published ? 'checked="checked"' : '';
		$nocheck = $entry->published ? '' : 'checked="checked"';

		$parser_class = MARKDOWN_PARSER_CLASS;
		$parser = new $parser_class;

		echo <<<EDIT_ENTRY

	<div id="glossary">
		<form action="index2.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%">
   		<tr>
			<td width="75%" colspan="3">
			<div class="title header">
				<img src="../components/com_glossary/images/glosslogo.png" alt="" />
				<span class="sectionname">
					{$this->show(_GLOSSARY_COMPONENT_TITLE)} - {$this->show(_GLOSSARY_EDIT_ENTRIES)}
				</span>
			</div>
			</td>
			<td width="25%">
			</td>
    	</tr>
    	</table>
    	<div>
    		<label for="catid">{$this->show(_GLOSSARY_GLOSSARY_SELECT)}:</label>
			<select name="catid" id="catid" class="glossary inputbox">
				{$this->makeGlossarySelect($glossaries, $entry)}
			</select>
    	</div>
    	<div>
    		<label for="tletter">{$this->show(_GLOSSARY_LETTER)}:</label>
    		<input name="tletter" id="tletter" type="text" class="inputbox" value="$entry->tletter" />
    	</div>
    	<div>
    		<label for="tterm">{$this->show(_GLOSSARY_TERM)}:</label>
    		<input name="tterm" id="tterm" type="text" class="widthspec inputbox" size="100" value="$entry->tterm" />
    	</div>
    	<div>
    		<label for="trelated">{$this->show(_GLOSSARY_RELATED_TERMS)}:</label>
    		<input name="trelated" id="trelated" type="text" class="widthspec inputbox" size="100" value="$entry->trelated" />
    	</div>
    	<div>
    		<label for="taliases">{$this->show(_GLOSSARY_ALIASES)}:</label>
    		<input name="taliases" id="taliases" type="text" class="widthspec inputbox" size="100" value="$entry->taliases" />
    	</div>
    	<div>
    		<label for="tdefinition">{$this->show(_GLOSSARY_DEFINITION)}:</label>
    		<textarea name="tdefinition" id="tdefinition" class="widthspec inputbox" rows="6" cols="40">$entry->tdefinition</textarea>
    	</div>
	   	<div>
    		<label for="tdefinitionmd">{$this->show(_GLOSSARY_APPEARS_AS)}:</label>
    		<div id="tdefinitionmd" class="widthspec">{$this->showHTML($parser->transform($entry->tdefinition))}</div>
    	</div>
    	<div>
    		<label for="tname">{$this->show(_GLOSSARY_AUTHOR_NAME)}:</label>
    		<input name="tname" id="tname" type="text" class="widthspec inputbox" size="100" value="$entry->tname" />
    	</div>
    	<div>
    		<label for="tloca">{$this->show(_GLOSSARY_LOCALITY)}:</label>
    		<input name="tloca" id="tloca" type="text" class="widthspec inputbox" size="100" value="$entry->tloca" />
    	</div>
    	<div>
    		<label for="tmail">{$this->show(_GLOSSARY_MAIL)}:</label>
    		<input name="tmail" id="tmail" type="text" class="widthspec inputbox" size="100" value="$entry->tmail" />
    	</div>
    	<div>
    		<label for="tpage">{$this->show(_GLOSSARY_PAGE)}:</label>
    		<input name="tpage" id="tpage" type="text" class="widthspec inputbox" size="100" value="$entry->tpage" />
    	</div>
    	<div>
    		<label for="tdate">{$this->show(_GLOSSARY_DATE)}:</label>
    		<input name="tdate" id="tdate" type="text" class="widthspec inputbox" size="100" value="$entry->tdate" />
    	</div>
    	<div>
    		<label for="published">{$this->show(_GLOSSARY_PUBLISHED)}:</label>
    		<input name="published" id="published" type="radio" class="inputbox" value="1" $yescheck /><span class="glossary">{$this->show(_CMSAPI_YES)}</span>
    		<input name="published" type="radio" class="inputbox" value="0" $nocheck /><span class="glossary">{$this->show(_CMSAPI_NO)}</span>
    	</div>
	    <div class="clear">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="act" value="$this->act" />
			<input type="hidden" name="option" value="com_glossary" />
			<input type="hidden" name="glossid" value="$entry->catid" />
			<input type="hidden" name="id" value="$entry->id" />
		</div>
    	</form>
    </div>
		
EDIT_ENTRY;

	}
	
	function makeGlossarySelect ($glossaries, $entry) {
		$optionlist = '';
		foreach ($glossaries as $glossary) {
			$selected = ($glossary->id == $entry->catid) ? ' selected="selected"' : '';
			$optionlist .= <<<OPTION_ENTRY
		
			<option value="$glossary->id"$selected>$glossary->name</option>
			
OPTION_ENTRY;

		}
		return $optionlist;
	}
	
}