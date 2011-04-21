<?php

/**************************************************************
* This file is part of Glossary
* Copyright (c) 2009 Martin Brampton
* Issued as open source under GNU/GPL
* For support and other information, visit http://remository.com
* To contact Martin Brampton, write to martin@remository.com
*
*/

// NOTE: LOWER CASE URI - If you want Glossary URIs to be all lower case, please change the
// following definition from 0 to 1:

if (!defined('_GLOSSARY_SEF_LOWER_CASE')) define ('_GLOSSARY_SEF_LOWER_CASE', 0);

if (!defined( '_VALID_MOS' ) AND !defined('_JEXEC')) die( sprintf ('Direct Access to %s is not allowed.', __FILE__ ));

// Define CMS environment
foreach (array('aliro','mambo','joomla') as $cmsapi_cms) @include(dirname(__FILE__)."/cms_is_$cmsapi_cms.php");
require(_CMSAPI_ABSOLUTE_PATH.'/components/com_glossary/glossary.autoload.php');

// SEF handling for Joomla internal system

// This is passed an associative array of URI query values,
// and must return an array of SEF'd values

function glossaryBuildRoute (&$query) {
	foreach ($query as $key=>$value) {
		$item[] = strtolower($key).'='.$value;
		if ('option' != $key AND 'Itemid' != $key) unset ($query[$key]);
	}
	$sefstring = isset($item) ? sef_glossary::create(implode('&', $item), _GLOSSARY_SEF_LOWER_CASE) : '';
	return $sefstring ? explode('/', $sefstring) : array();
}

// This is passed an associative array of SEF'd values,
// and must return an array of query string values

function glossaryParseRoute ($segments) {
	$replacements = 1;
	foreach ($segments as $key=>$segment) $segments[$key] = str_replace(':', '-', $segment, $replacements);
    return explode('&', substr(sef_glossary::revert($segments, -2), 1));
}
