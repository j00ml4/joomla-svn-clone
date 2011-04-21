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
/** ensure this file is being included by a parent file */
if (!defined( '_VALID_MOS' ) AND !defined('_JEXEC')) die( sprintf ('Direct Access to %s is not allowed.', __FILE__ ));

// Define CMS environment
foreach (array('aliro','mambo','joomla') as $cmsapi_cms) @include(dirname(__FILE__)."/cms_is_$cmsapi_cms.php");
require(_CMSAPI_ABSOLUTE_PATH.'/components/com_glossary/glossary.autoload.php');

class sef_glossary {

	/**
	* Creates the SEF advance URL out of the CMS request
	* Input: $string, options, The request URL (index.php?option=com_example&Itemid=$Itemid)
	* Output: $sefstring, string, SEF advance URL ($var1/$var2/)
	**/
	public static function create ($string, $lowercase=false, $numeric=false, $maptags=array()) {
		// $string == "index.php?option=com_example&Itemid=$Itemid&var1=$var1&var2=$var2"
		$sefstring = '';
		$string = str_replace( '&amp;', '&', $string);
		if (0 == strncasecmp('index.php?', $string, 10)) $string = substr($string,10);
		parse_str($string,$params);
		$params = array_change_key_case($params);
		if (isset($params['task'])) {
			if ('edit' == $params['task']) $sefstring .= 'edit/';
			unset($params['task']);
		}
		if (isset($params['id'])) {
			$entry = glossaryEntryManager::getInstance()->getByID($params['id']);
			if ($entry) $letter = $entry->tletter;
			else unset($params['id']);
		}
		elseif (isset($params['letter'])) $letter = $params['letter'];
		if (isset($params['glossid'])) $glossid = $params['glossid'];
		elseif (isset($params['id'])) {
			$glossid = $entry->catid;
		}
		if (isset($params['page'])) $pagenum = $params['page'];
		if (!empty($glossid)) {
			$glossary = glossaryGlossaryManager::getInstance()->getByID($glossid);
			if ($glossary) $sefstring .= sef_glossary::uriName($glossary->name, $lowercase).'-'.$glossid.'/';
			if (!empty($letter)) $sefstring .= $letter.'/';
			if (!empty($entry)) $sefstring .= sef_glossary::uriName($entry->tterm, $lowercase).'-'.$entry->id.'/';
			if (!empty($pagenum)) $sefstring .= 'page,'.$pagenum.'/';
		}
		return $sefstring;
	}

	private static function uriName ($name, $lowercase) {
		if (function_exists('sefencode')) return sefencode($name);
		$illegal = array(';', '/', '?', ':', '@', '&', '=', '+', '$', ',', ' ', '<', '>', '#', '%', '"', '{', '}', '|', '\\', '^', '[', ']', '`');
		$name = str_replace($illegal, '-', $name);
		$name = preg_replace("/(\-)+/", '-', $name);
		if ($lowercase) $name = strtolower($name);
		$name = urlencode($name);
		return $name;
	}

	/**
	* Reverts to the CMS query string out of the SEF advance URL
	* Input:
	*    $url_array, array, The SEF advance URL split in arrays (first custom virtual directory beginning at $pos+1)
	*    $pos, int, The position of the first virtual directory (component)
	* Output: $QUERY_STRING, string,CMS query string (var1=$var1&var2=$var2)
	*    Note that this will be added to already defined first part (option=com_example&Itemid=$Itemid)
	**/
	public static function revert ($url_array, $pos, $numeric=false, $maptags=array()) {
		// define all variables you pass as globals - not required for Glossary - uses super globals
 		// Examine the SEF advance URL and extract the variables building the query string
		// (class_exists('aliroSEF')) return false;
		$QUERY_STRING = "";
		if (!empty($url_array[$pos+2])) {
			if ('edit' == $url_array[$pos+2]) {
				$_REQUEST['task'] = $_GET['task'] = 'edit';
				$QUERY_STRING .= '&task=edit';
				$pos++;
			}
			$glossid = sef_glossary::extractGlossaryNumber($url_array[$pos+2]);
			if ($glossid) {
				$_REQUEST['glossid'] = $_GET['glossid'] = $glossid;
				$QUERY_STRING .= "&glossid=$glossid";
				if (!empty($url_array[$pos+3])) {
					$letter = $url_array[$pos+3];
					$_REQUEST['letter'] = $_GET['letter'] = $letter;
					$QUERY_STRING .= '&letter='.$letter;
					if (!empty($url_array[$pos+4])) {
						$parm = $url_array[$pos+4];
						if (0 === strpos($parm, 'page,')) {
							$pagenum = intval(substr($parm,5));
							if ($pagenum) {
								$_REQUEST['page'] = $_GET['page'] = $pagenum;
								$QUERY_STRING .= '&page='.$pagenum;
							}
						}
						else {
							$termid = sef_glossary::extractEntryNumber($parm);
							if ($termid) {
								$_REQUEST['id'] = $_GET['id'] = $termid;
								$QUERY_STRING .= '&id='.$termid;
							}
						}
					}
				}
			}
		}
		return $QUERY_STRING;
	}

	// Assumes that the parameter is a string that ends with an integer
	// preceded by a hyphen; returns the number
	private static function extractEntryNumber ($string) {
		$parts = explode('-', $string);
		$number = intval(end($parts));
		$entry = glossaryEntryManager::getInstance()->getByID($number);
		$sefname = sef_glossary::uriName($entry->tterm, false).'-'.$number;
		return  (0 == strcasecmp($string,$sefname) OR 0 == strcasecmp(urlencode($string),$sefname)) ? $number : 0;
	}

	// Assumes that the parameter is a string that ends with an integer
	// preceded by a hyphen; returns the number
	private static function extractGlossaryNumber ($string) {
		$parts = explode('-', $string);
		$number = intval(end($parts));
		$glossary = glossaryGlossaryManager::getInstance()->getByID($number);
		$sefname = sef_glossary::uriName($glossary->name, false).'-'.$number;
		return  (0 == strcasecmp($string,$sefname) OR 0 == strcasecmp(urlencode($string),$sefname)) ? $number : 0;
	}

}

