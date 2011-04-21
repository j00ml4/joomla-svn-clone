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

class glossaryEditHTML extends glossaryHTML {
	
	public function edit ($entry, $my, $gconfig, $glossaries) {
		$compname = _THIS_COMPONENT_NAME;
		$cname = strtolower($compname);
		// Not used - what was this for?
		// $icon = "../components/com_glossary/images/glosslogo.png";

		if ($my->id) {
			$entry->tname = $my->name;
			$entry->tmail = $my->email;
		}
	
		$action = $this->interface->sefRelToAbs('index.php');
		if ($gconfig->anonentry) echo <<<EDIT_ENTRY_FREE
		
		<form action="$action" method="post" name="adminForm">
		<h2>$compname {$this->show(_GLOSSARY_EDIT_ENTRIES)}</h2>
		<div>
			<label for="glossid">{$this->show(_GLOSSARY_CHOOSE_WHICH_ONE)}</label>
			<select name="catid" id="glossid" class="inputbox">
				{$this->makeGlossaryOptions($glossaries, $entry)}
			</select>
		</div>
    	<div class="glossformdiv">
    		<label for="tname">{$this->show(_GLOSSARY_USER_NAME)}:</label>
    		<input name="tname" id="tname" type="text" class="inputbox" size="50" value="{$this->show($entry->tname)}" />
    	</div>
    	<div class="glossformdiv">
    		<label for="tmail">{$this->show(_GLOSSARY_USER_MAIL)}:</label>
    		<input name="tmail" id="tmail" type="text" class="inputbox" size="50" value="{$this->show($entry->tmail)}" />
    	</div>
    	<div class="glossformdiv">
    		<label for="tpage">{$this->show(_GLOSSARY_USER_URI)}:</label>
    		<input name="tpage" id="tpage" type="text" class="inputbox" size="50" value="{$this->show($entry->tpage)}" />
    	</div>
    	<div class="glossformdiv">
    		<label for="tloca">{$this->show(_GLOSSARY_USER_LOCALITY)}:</label>
    		<input name="tloca" id="tloca" type="text" class="inputbox" size="50" value="{$this->show($entry->tloca)}" />
    	</div>
    	<div class="glossformdiv">
    		<label for="tletter">{$this->show(_GLOSSARY_LETTER)}:</label>
    		<input name="tletter" id="tletter" type="text" class="inputbox" value="{$this->show($entry->tletter)}" />
    	</div>
    	<div class="glossformdiv">
    		<label for="tterm">{$this->show(_GLOSSARY_TERM)}:</label>
    		<input name="tterm" id="tterm" type="text" class="inputbox" size="50" value="{$this->showHTML($entry->tterm)}" />
    	</div>
    	<div class="glossformdiv">
    		<label for="trelated">{$this->show(_GLOSSARY_RELATED_TERMS)}:</label>
    		<input name="trelated" id="trelated" type="text" class="inputbox" size="50" value="{$this->show($entry->trelated)}" />
    	</div>
    	<div class="glossformdiv">
    		<label for="taliases">{$this->show(_GLOSSARY_ALIASES)}:</label>
    		<input name="taliases" id="taliases" type="text" class="inputbox" size="50" value="{$this->show($entry->taliases)}" />
    	</div>
    	<div class="glossformdiv">
    		<label for="tdefinition">{$this->show(_GLOSSARY_DEFINITION)}:</label>
    		<textarea id="tdefinition" name="tdefinition" class="inputbox" rows="4" cols="50">{$this->showHTML($entry->tdefinition)}</textarea>
    	</div>
    	<div class="clear">
    		<p>
    			{$this->showHTML(_GLOSSARY_MARKDOWN_USAGE)}
    		</p>
    	</div>
    	<div>
    		<input type="submit" class="button clear" value="{$this->show(_GLOSSARY_USER_SUBMIT)}" />
    		<input type="reset" class="button" value="{$this->show(_GLOSSARY_USER_CLEAR)}" />
    	</div>
	    <div class="clear">
			<input type="hidden" name="task" value="save" />
			<input type="hidden" name="option" value="com_$cname" />
			<input type="hidden" name="id" value="$entry->id" />
		</div>
    	</form>
		
EDIT_ENTRY_FREE;

		else echo <<<EDIT_ENTRY_LIMITED
		
		<form action="$action" method="post" name="adminForm">
		<h2>$compname {$this->show(_GLOSSARY_EDIT_ENTRIES)}</h2>
		<div>
			<label for="glossid">{$this->show(_GLOSSARY_CHOOSE_WHICH_ONE)}</label>
			<select name="catid" id="glossid" class="inputbox">
				{$this->makeGlossaryOptions($glossaries, $entry)}
			</select>
		</div>
    	<div class="glossformdiv">
    		<label for="tname">{$this->show(_GLOSSARY_USER_NAME)}:</label>
    		<input name="tname" id="tname" type="text" class="inputbox" readonly="readonly" size="50" value="{$this->show($entry->tname)}" />
    	</div>
    	<div class="glossformdiv">
    		<label for="tmail">{$this->show(_GLOSSARY_USER_MAIL)}:</label>
    		<input name="tmail" id="tmail" type="text" class="inputbox" readonly="readonly" size="50" value="{$this->show($entry->tmail)}" />
    	</div>
    	<div class="glossformdiv">
    		<label for="tpage">{$this->show(_GLOSSARY_USER_URI)}:</label>
    		<input name="tpage" id="tpage" type="text" class="inputbox" size="50" value="{$this->show($entry->tpage)}" />
    	</div>
    	<div class="glossformdiv">
    		<label for="tloca">{$this->show(_GLOSSARY_USER_LOCALITY)}:</label>
    		<input name="tloca" id="tloca" type="text" class="inputbox" size="50" value="{$this->show($entry->tloca)}" />
    	</div>
    	<div class="glossformdiv">
    		<label for="tletter">{$this->show(_GLOSSARY_LETTER)}:</label>
    		<input name="tletter" id="tletter" type="text" class="inputbox" value="{$this->show($entry->tletter)}" />
    	</div>
    	<div class="glossformdiv">
    		<label for="tterm">{$this->show(_GLOSSARY_TERM)}:</label>
    		<input name="tterm" id="tterm" type="text" class="inputbox" size="50" value="{$this->showHTML($entry->tterm)}" />
    	</div>
    	<div class="glossformdiv">
    		<label for="tdefinition">{$this->show(_GLOSSARY_DEFINITION)}:</label>
    		<textarea id="tdefinition" name="tdefinition" class="inputbox" rows="4" cols="50">{$this->showHTML($entry->tdefinition)}</textarea>
    	</div>
    	<div class="clear">
    		<p>
    			{$this->showHTML(_GLOSSARY_MARKDOWN_USAGE)}
    		</p>
    	</div>
    	<div>
    		<input type="submit" class="button clear" value="{$this->show(_GLOSSARY_USER_SUBMIT)}" />
    		<input type="reset" class="button" value="{$this->show(_GLOSSARY_USER_CLEAR)}" />
    	</div>
	    <div class="clear">
			<input type="hidden" name="task" value="save" />
			<input type="hidden" name="option" value="com_$cname" />
			<input type="hidden" name="id" value="$entry->id" />
		</div>
    	</form>
		
EDIT_ENTRY_LIMITED;

	}

	public function makeGlossaryOptions ($glossaries, $entry) {
		$optionlist = '';
		foreach ($glossaries as $glossary) {
			$selected = $glossary->id == $entry->catid ? ' selected="selected"' : '';
			$optionlist .= <<<OPTION_ENTRY
		
			<option value="$glossary->id"$selected>{$this->show($glossary->name)}</option>
			
OPTION_ENTRY;

		}
		return $optionlist;
	}

}