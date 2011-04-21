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

class glossarySearchHTML extends glossaryHTML {
	
	public function view ($glossary, $term, $method, $Itemidline, $more, $manygloss) {
		$search = $term ? $this->show($term) : _GLOSSARY_SEARCH_SEARCH;
		for ($i=1; $i<5; $i++) {
			$fieldname = 'check'.$i;
			$$fieldname = ($method == $i) ? 'checked="checked"' : '';	
		}
		$formlink = cmsapiInterface::getInstance()->sefRelToAbs('index.php?option=com_glossary');
		return <<<GLOSSARY_SEARCH
		
		<div id="glossarysearch">
			<form action="$formlink" method="post" id="glossarysearchform">
				<div id="glossarysearchheading">
					{$this->show(_GLOSSARY_SEARCH_INTRO)}
				</div>
   				<input type="text" name="glossarysearchword" id="glossarysearchword" value="$search" size="30" onblur="if(this.value=='') this.value='$search';" onfocus="if(this.value=='$search') this.value='';" />
           <!-- ////// Modificata per mostrare l'autocompleter AJAX ///////// -->
				<div id="glossarysearchmethod">
					<input type="radio" name="glossarysearchmethod" value="1" $check1 />{$this->show(_GLOSSARY_BEGINS_WITH)}
					<input type="radio" name="glossarysearchmethod" value="2" $check2 />{$this->show(_GLOSSARY_TERM_CONTAINS)}
					<input type="radio" name="glossarysearchmethod" value="3" $check3 />{$this->show(_GLOSSARY_EXACT_TERM)}
					{$this->offerSoundex($check4)}
					{$this->offerMultipleGlossaries($more, $manygloss)}
				</div>
				<div>
					<input type="submit" class="button" value="{$this->show(_GLOSSARY_GO)}" />
					<input type="submit" class="button" value="{$this->show(_GLOSSARY_CANCEL_SEARCH)}" onclick="sw=document.getElementById('glossarysearchword'); sw.value=''; return true;" />
					<input type="hidden" name="task" value="list" />
					<input type="hidden" name="id" value="0" />
					<input type="hidden" name="glossid" value="$glossary->id" />
					$Itemidline
				</div>
			</form>
		</div>
		
GLOSSARY_SEARCH;

	}

	protected function offerMultipleGlossaries ($more, $manygloss) {
		if ($more) {
			$checked = $manygloss ? 'checked="checked"' : '';
			return <<<OFFER_MULTIPLE

					<input type="checkbox" name="manygloss" value="1" $checked />{$this->show(_GLOSSARY_SEARCH_MULTIPLE)}

OFFER_MULTIPLE;

		}
	}

	protected function offerSoundex ($check) {
		if ($this->gconfig->show_soundex) return <<<SHOW_SOUNDEX

					<input type="radio" name="glossarysearchmethod" value="4" $check />{$this->show(_GLOSSARY_SOUNDS_LIKE)}

SHOW_SOUNDEX;

	}
}

/* This is the code to go in the AJAX modification

                <script type="text/javascript">
                    new Ajax.Autocompleter('glossarysearchword','searchChoices','./utils/ajax_autocompleter/GlossaryAutocompleter.php', {paramName: "glossarysearchword", minChars:1});
               </script>   

*/
