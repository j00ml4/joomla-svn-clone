<?php
/**
* FileName: mod_glossarynewest.php
* Date: April 2009
* License: GNU General Public License
* Script Version #: 2.6
* Glossary Version #: 2.6 or above
* Author: Martin Brampton - martin@remository.com (http://remository.com)
* Copyright: Martin Brampton 2006-9
**/

class mod_glossaryNewest extends mod_glossaryBase {

	public function showFileList ($module, &$content, $area, $params) {
		$interface = cmsapiInterface::getInstance();
		$database = $interface->getDB();

		// Find out $Itemid
		$base_url = 'index.php?option=com_glossary';        	// Base URL string
		$base_url .= '&Itemid='.$this->cmsapi_getItemID('com_glossary');
		$base_url .= '&func=fileinfo&id=';

		/*********************Configuration*********************/
		// Set to '1' to Show the Definition, set to 0 to not show it
		$showdefn = $this->cmsapi_get_module_parm($params,'showdefn',0);
		// Max number of entries to show
		$max = $this->cmsapi_get_module_parm($params,'max',5 );
		// Max number of definition characters
		$maxchars = $this->cmsapi_get_module_parm($params,'maxchars',100);
		// Date format for display
		$date_format = $this->cmsapi_get_module_parm($params,'dateformat','%b.%d');
		// Glossary from which to select terms
		$glossary = $this->cmsapi_get_module_parm($params,'glossary', 0);

		// Get suffix class
		$suffix = $this->cmsapi_get_module_parm($params, 'moduleclass_sfx', '');

		$max = max($max,1);
		$maxchars = max($maxchars,20);
		/*******************************************************/

		// Newest Terms
		$byglossary = $glossary ? "WHERE catid = $glossary" : '';
		$database->setQuery("SELECT * FROM #__glossary $byglossary ORDER BY tdate DESC LIMIT $max");
		$newterms = $database->loadObjectList();

		// $this->cmsapi_module_CSS ();
		$content = <<<START_CATS

		<table class="glossarymodule$suffix" cellspacing="2" cellpadding="1" border="0" width="100%">

START_CATS;

		$tabcnt = 0;

		foreach ($newterms as $newterm) {
			$definition = '';
			if ($showdefn) {
				$definition .= '<br/>'.$this->removeFormatCharacters(strip_tags($newterm->tdefinition));
				if (strlen($definition) > $maxchars) $definition = substr($definition,0,$maxchars-3).'...';
			}
			$url = $interface->sefRelToAbs("index.php?option=com_glossary&Itemid={$this->cmsapi_getItemID('com_glossary','glossid',$newterm->catid)}&id=$newterm->id");
			if ('none' != strtolower($date_format)) {
				$date = strftime($date_format, strtotime($newterm->tdate));
				// Add a calendar image after the date
				$datestring = <<<TERM_DATE

				<td width="20%" valign="middle" class="number">
					$date
				</td>

TERM_DATE;

			}
			else $datestring = '';
			// $class = $tabclass_arr[$tabcnt];
			$content .= <<<ONE_TERM

			<tr>
				$datestring
				<td width="80%">
					<a href="$url">
						$newterm->tterm
					</a>
					$definition
				</td>
			</tr>

ONE_TERM;

			$tabcnt = 1 - $tabcnt;
		}
		$content .= "\n</table>\n";
	}
}