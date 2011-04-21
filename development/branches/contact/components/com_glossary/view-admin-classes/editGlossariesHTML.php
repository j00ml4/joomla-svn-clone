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

class editGlossariesHTML extends cmsapiAdminHTML {
	
	function edit ($glossary) {
		$yescheck = $glossary->published ? 'checked="checked"' : '';
		$nocheck = $glossary->published ? '' : 'checked="checked"';

		echo <<<EDIT_GLOSSARY
		
		<form action="index2.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%">
   		<tr>
			<td width="75%" colspan="3">
			<div class="title header">
				<img src="../components/com_glossary/images/glosslogo.png" alt="" />
				<span class="sectionname">
					{$this->show(_GLOSSARY_COMPONENT_TITLE)} - {$this->show(_GLOSSARY_EDIT_GLOSSARIES)}
				</span>
			</div>
			</td>
			<td width="25%">
			</td>
    	</tr>
    	</table>
    	<div>
    		<label for="name">{$this->show(_GLOSSARY_NAME)}:</label>
    		<input name="name" id="name" type="text" class="inputbox" value="$glossary->name" />
    	</div>
    	<div>
    		<label for="description">{$this->show(_GLOSSARY_DESCRIPTION)}:</label>
    		<input name="description" id="description" type="text" class="inputbox" size="100" value="$glossary->description" />
    	</div>
    	<div>
    		<label for="language">{$this->show(_GLOSSARY_GLOSS_LANGUAGE)}:</label>
    		<input name="language" id="language" type="text" class="inputbox" value="$glossary->language" />
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
			<input type="hidden" name="id" value="$glossary->id" />
		</div>
    	</form>
		
EDIT_GLOSSARY;

	}
}