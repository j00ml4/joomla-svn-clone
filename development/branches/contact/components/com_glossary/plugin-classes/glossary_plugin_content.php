<?php
 /**
 * Glossarbot Plugin 2.7
 * Support site: http://www.remository.com
 * All rights reserved
 * Released under GNU/GPL License Version 2: http://www.gnu.org/copyleft/gpl.html
 * 
 * @by Martin Brampton (martin@remository.com)
 * @Copyright (C)2009 Martin Brampton
 * 
 * This code was originally developed by Martin Brampton from partially written code by Sascha Claren
 * 
 * It was then taken to form the plugin for "Definitions" by Stefan Granholm and published in slightly
 * modified form without any attribution, but the following line:
 *
 * Definition file - By GranholmCMS.com - www.granholmcms.com
 * 
 * The latest Glossarbot for Joomla 1.5.x created some problems because of the attempt to shift to 
 * using mootools to generate the popup.  To quickly solve those problems, this file is being released
 * by Martin Brampton.  It contains some changes commissioned by Stefan Granholm, but is mainly a 
 * simple adaptation of the original Glossarbot code, taking account of differences in Joomla 1.5.
 * 
 * The basis for releasing in this way is that since Glossarbot was released as GPL, it follows that
 * Definitions (being a derived work) must also have been released as GPL.  It is therefore legitimate
 * to base another version of Glossarbot on Definitions, by the rights granted in the GPL.
 * 
 **/

 DEFINE ('_GLOSSARY_CACHE_MINUTES', 1440);

if ('Aliro' == _CMSAPI_CMS_BASE) {
	class bot_glossaryDefinitions extends aliroPlugin {

		public function onPrepareContent ($article) {
		 	$worker = new glossary_plugin_content($this->params);
		 	return $worker->onPrepareContent ($article);
		}

	}
}

class glossary_plugin_content {
	private static $onetime = false;
	private $interface = null;
	private $database = null;
	private $pluginParams = null;
	private $glossids = array();
	private $glosslist = '';
	private $wordlist = '';
	private $glossref = '';
	private $termsfound = array();
	private $terminfo = array();
	private $definitions = array();
	private $letters = array();
	private $terms = array();
	
	public function __construct ($pluginParams) {
		$this->interface = cmsapiInterface::getInstance();
		$this->pluginParams = $pluginParams;
		$this->database = $this->interface->getDB();
		$this->glossref = 'index.php?option=com_glossary';
		$Itemid = $this->interface->getCurrentItemid();
		if ($Itemid) $this->glossref .= '&Itemid='.$Itemid;
		if (!self::$onetime) {
			if (!$this->pluginParams->get('use_cache', 1)) $this->database->doSQL("TRUNCATE TABLE #__glossary_cache");
			$livesite = $this->interface->getCfg('live_site');
			if ('/' != substr($livesite,-1)) $livesite .= '/';
			$nicestuff = <<<NICE_STUFF

<base href="$livesite" />
<script type="text/javascript" src="components/com_glossary/js/puretip.js"></script>
<link type="text/css" rel="stylesheet" href="components/com_glossary/css/puretip.css" />

NICE_STUFF;

			$this->interface->addCustomHeadTag($nicestuff);
			self::$onetime = true;
		}
	}
	
	private function popupCheck (&$text, $symbol, $value, $default) {
		$newtext = str_replace($symbol, '', $text);
		if ($newtext == $text) return $default;
		else {
			$text = $newtext;
			return $value;
		}
	}
	
	private function makeSearchable () {
		$find_exact = $this->pluginParams->get('find_exact', 1);
		$sql = 'SELECT g.id, a.termalias AS tterm, g.catid FROM #__glossary_aliases AS a INNER JOIN #__glossary AS g ON a.termid = g.id WHERE g.published != 0';
		if ($this->glosslist) $sql .= " AND g.catid IN ($this->glosslist)";
		if ($this->wordlist) $sql .= " AND a.tfirst IN ($this->wordlist)";
		$sql .= ' UNION SELECT s.id, s.tterm, s.catid FROM #__glossary AS s WHERE s.published != 0';
		if ($this->glosslist) $sql .= " AND s.catid IN ($this->glosslist)";
		if ($this->wordlist) $sql .= " AND s.tfirst IN ($this->wordlist)";
		$sql .= ' ORDER BY length(tterm) DESC';
		$rows = $this->database->doSQLget($sql);
		
		foreach ($rows as $row) {
			$keyword = htmlspecialchars(trim($row->tterm));
			// $term = htmlspecialchars(trim($row->tterm));
			// if ($keyword AND $term) {
			if ($keyword) {
				$escaped_keyword = preg_quote($keyword, '/');
				$regex = $find_exact ? '(^|\pP|\s)('.$escaped_keyword.')($|\pP|\s)' : $escaped_keyword;
				$this->terminfo[$keyword][$row->catid] = array('id' => $row->id, 'term' => $keyword, 'regex' => "/$regex/iu", 'found' => false);
			}
		}
	}

	private function preScan (&$content) {
		if (preg_match_all('/\{GlossaryDef:([^\}]*):([^\}]*)\}/u', $content, $matches)) {
			foreach ($matches[0] as $sub=>$fullmatch) {
				if (isset($this->terminfo[$matches[1][$sub]])) {
					$count = $this->saveText($matches[2][$sub]);
					$keys = array_keys($this->terminfo[$matches[1][$sub]]);
					$key = $keys[0];
					$content = str_replace($fullmatch, "{GlossaryDefnum:{$this->terminfo[$matches[1][$sub]][$key]['id']}:$count}", $content);
					$this->terminfo[$matches[1][$sub]][$key]['found'] = true;
				}
				else {
					$count = $this->saveText($matches[2][$sub]);
					$content = str_replace($fullmatch, "{GlossaryDefnum:0:$count}", $content);
				}
			}
		}
	}

	private function saveText ($text) {
		$count = count($this->termsfound);
		$this->termsfound[$count] = $text;
		return $count;
	}
	
	private function buildContent (&$content, $times) {
		$find_exact = $this->pluginParams->get('find_exact', 1);
		$newcontent = $content;
		foreach ($this->terminfo as $gkey=>$terms) {
			foreach ($terms as $catid=>$entry) {
				if ($times == 1 AND $entry['found']) continue;
				$id = $entry['id'];
				$replace = $find_exact ? "\$1{GlossaryDef:$id:$2}\$3" : "{GlossaryDef:$id:$0}";
				$newcontent = preg_replace($entry['regex'],$replace,$newcontent,$times);
				if ($newcontent != $content) {
					preg_match_all("/\{GlossaryDef:$id:([^\}]*)\}/u", $newcontent, $matches);
					foreach ($matches[0] as $sub=>$fulltext) {
						$pos = strpos($newcontent, $fulltext);
						$count = count($this->termsfound);
						$this->termsfound[$count] = $matches[1][$sub];
						$newcontent = substr_replace($newcontent, "{GlossaryDefnum:$id:$count}", $pos, strlen($fulltext));
					}
					$this->terminfo[$gkey][$catid]['found'] = true;
					$content = $newcontent;
				}
			}
		}
		return $content;
	}

	private function loadDefinitions () {
		foreach ($this->terminfo as $gkey=>$terms) {
			foreach ($terms as $catid=>$entry) {
				if ($entry['found']) $entries[$entry['id']][] = $gkey;
				else unset($this->terminfo[$gkey][$catid]);
			}
		}
		if (isset($entries)) {
			$parser_class = MARKDOWN_PARSER_CLASS;
			$parser = new $parser_class;
			$idlist = implode(',', array_keys($entries));
			$this->definitions = $this->database->doSQLget("SELECT id, tterm, tletter, tdefinition FROM #__glossary WHERE id IN ($idlist)", 'stdClass', 'id');
			foreach ($this->definitions as $sub=>$info) {
				$this->definitions[$sub] = htmlentities(strip_tags(preg_replace("/(\015\012)|(\015)|(\012)/",' <br />',$parser->transform($info->tdefinition))), ENT_QUOTES, 'UTF-8', false);
				$this->letters[$sub] = $info->tletter;
				$this->terms[$sub] = htmlentities($info->tterm, ENT_QUOTES, 'UTF-8', false);
			}
		}
	}
	
	private function makeLink ($termid, $termasfound) {
		// $orgTerm = addslashes(strip_tags($entry['term']));
		$desc = $this->definitions[$termid];
		$term = $this->terms[$termid];
		$ref = $this->interface->sefRelToAbs($this->glossref.'&id='.$termid.'&letter='.$this->letters[$termid]);
		if ($this->pluginParams->get('show_image', 1)) {
			$imglink = $this->interface->getCfg('live_site').'/components/com_glossary/images/plugin/'.$this->pluginParams->get('icon', 'messagebox_info.png');
			return <<<IMG_LINK

				<img src="$imglink" border="0" align="top" alt="{$this->pluginParams->get('glossary_alt', 'Glossary Link')}" />
				<a class="glossarylink" href="$ref" title="$term: $desc">$termasfound</a>
IMG_LINK;

		}
		return <<<ONE_LINK
<a class="glossarylink" href="$ref" title="$desc">$termasfound</a>
ONE_LINK;

	}

	private function convertEntries ($content) {
		// replace temporary {GlossaryDef:id:term_as_found} markers
		if (preg_match_all('/{GlossaryDefnum:([0-9]+):([^}]*)}/u', $content, $matches)) {
			foreach ($matches[0] as $sub=>$fullmarker) {
				$termasfound = $matches[2][$sub];
				$termasnumber = intval($termasfound);
				if ($termasfound == (string) $termasnumber AND isset($this->termsfound[$termasnumber])) {
					$termasfound = $this->termsfound[$termasnumber];
				}
				$before[] = $fullmarker;
				$after[] = $matches[1][$sub] ? $this->makeLink($matches[1][$sub], $termasfound) : $termasfound;
			}
		}
		return isset($before) ? str_replace($before, $after, $content) : $content;
	}

	private function showDefinition ($content) {
		foreach ($this->terminfo as $terms) foreach ($terms as $entry) {
			if ($entry['found']) $temparray[$entry['term']] = $this->definitions[$entry['id']];
		}
		if (count($temparray) == 0) return;
		ksort($temparray);

		define ('_headline', $this->pluginParams->get('headline', "Nomenclature"));
		define ('_head_term',$this->pluginParams->get('head_term', "Term"));
		define ('_head_explanation', $this->pluginParams->get('head_explanation', "Description"));

		$show_headline = $this->pluginParams->get('show_headline', 1);
		$style = '2';
		if ($_SERVER['SCRIPT_NAME'] == "/index2.php") {
			$border = '1';
			$cellspacing = '0';
			$cellpadding = '2';
		}
		else {
			$border = '0';
			$cellspacing = '2';
			$cellpadding = '2';
		}
		// --- Array Sorting by Keyword

		$definitiontable = '<table width="100%" cellpadding="1" cellspacing="0">';
		if ($show_headline) $definitiontable .= '<tr><td width="100%" style="border-bottom: 1px solid #C9C9C9;border-top: 1px solid #C9C9C9;"><b>' . _headline . '</b></td></tr>';
		$definitiontable .= '<tr><td><br><table border="' . $border . '" align="center" style="border: 1px solid #C9C9C9;" width="100%" cellpadding="' . $cellpadding . '" cellspacing="' . $cellspacing . '"><tr class="sectiontableheader"><td><b>' . _head_term . '</b></td><td><b>' . _head_explanation . '</b></td></tr>';
		foreach ($temparray as $keyword=>$definition) {
			$definitiontable .= '<tr class="sectiontableentry' . $style . '"><td width="auto">' . $keyword . '&nbsp;</td><td>' . $definition . '</td></tr>';
			$style = ($style % 2) + 1;
		}
		$definitiontable .= '</td></tr></table></td></tr><tr><td align="right"><a href="http://www.remository.com" target="_blank">&copy;2005 remository.com</a></td></tr></table>';
		return str_replace('{definition}', $definitiontable, $content);
	}

	public function onPrepareContent ($page) {
	    setlocale (LC_ALL, $this->interface->getLocale());

		$show_frontpage = $this->pluginParams->get('show_frontpage', 1);
		$run = $this->pluginParams->get('run_default', 1);
		$show_once_only = $this->pluginParams->get('show_once_only', 1);
		if ($show_once_only) $times = 1;
		else $times = -1;
	
		// checking if the plugin creates popups
		$run = $this->popupCheck($page->text, '{mosinfopop=enable}', 1, $run);
		$run = $this->popupCheck($page->text, '{definitionbot=enable}', 1, $run);
		$run = $this->popupCheck($page->text, '{glossarbot=enable}', 1, $run);
		$run = $this->popupCheck($page->text, '{mosinfopop=disable}', 0, $run);
		$run = $this->popupCheck($page->text, '{definitionbot=disable}', 0, $run);
		$run = $this->popupCheck($page->text, '{glossarbot=disable}', 0, $run);
	
		if ($run == 0) return true;
		$option = $this->interface->getParam($_REQUEST, 'option');

		if (preg_match('/\{glossid:([0-9,]+)\}/u', $page->text, $matches)) {
			$this->glossids = array_filter(array_map('intval', explode(',', $matches[1])));
			if (count($this->glossids)) $this->glosslist = implode(',', $this->glossids);
			$page->text = preg_replace('/\{glossid:([0-9,])+\}/u', '', $page->text);
		}

		if (!$this->pluginParams->get('show_frontpage', 1) AND $this->interface->isFrontPage()) return true;

		if ($this->pluginParams->get('use_cache', 1)) {
			$expires = time() - 60 * _GLOSSARY_CACHE_MINUTES;
			$md5hash = md5($page->text);
			$sha1hash = sha1($page->text);
			$this->database->setQuery("SELECT fixup FROM #__glossary_cache WHERE md5hash = '$md5hash' AND sha1hash = '$sha1hash' AND stamp < $expires");
			$text = $this->database->loadResult();
			if ($text) {
				$page->text = $text;
				$this->database->doSQL("DELETE FROM #__glossary_cache WHERE stamp < $expires");
				return true;
			}
		}
		
		if ($this->pluginParams->get('find_exact', 1)) {
			// preg_match_all('/^|\W([^\W]+)/', $page->text, $matches);
			$awords = array_map(array($this->database, 'getEscaped'), array_unique(preg_split('/[\p{P}\s]+/u', strip_tags($page->text), -1, PREG_SPLIT_NO_EMPTY)));
			$this->wordlist = "'".implode("', '", $awords)."'";
			unset($awords);
			/*
			if (!empty($matches[1])) {
				$awords = array_unique($matches[1]);
				unset($matches);
				$awords = array_map(array($this->database, 'getEscaped'), $awords);
				$this->wordlist = "'".implode("', '", $awords)."'";
				unset($awords);
			}
			*/
		}
		
		$this->makeSearchable();
		if (count($this->terminfo)) {
			foreach (array_keys($this->terminfo) as $key) $length[] = strlen($key);
			$minkeylen = min($length);
			$newContent = '';
			$this->preScan($page->text);
			$htmlregex = '#(<a\s+.*?</a\s*>|<h[0-9]+.*?</h[0-9]+\s*>|<script\s+.*?</script\s*>|<style\s+.*?</style\s*>|</?.*?\>|\<!--.*?-->)#isu';
			$bits = preg_split($htmlregex, $page->text);
			preg_match_all($htmlregex, $page->text, $matches);
			foreach ($bits as $i=>$bit) {
				if (strlen($bit) < $minkeylen) $newContent .= $bit;
				else $newContent .= $this->buildContent ($bit, $times);
				if (isset($matches[0][$i])) $newContent .= $matches[0][$i];
			}
			$this->loadDefinitions();
			$newContent = $this->convertEntries ($newContent);
			if (preg_match('/\{definition\}/iu',$newContent)) $page->text = $this->showDefinition ($newContent);
			else $page->text = $newContent;

			if ($this->pluginParams->get('use_cache', 1)) {
				$now = time();
				$text = $this->database->getEscaped($page->text);
				$this->database->doSQL("INSERT INTO #__glossary_cache (md5hash, sha1hash, stamp, fixup) VALUES ('$md5hash', '$sha1hash', $now, '$text')");
			}
		}
		return true;
	}
}
