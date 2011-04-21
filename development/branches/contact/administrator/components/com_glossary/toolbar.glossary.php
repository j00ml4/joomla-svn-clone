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

// ensure this file is being included by a parent file
if (!defined( '_VALID_MOS' ) AND !defined('_JEXEC')) die( 'Direct Access to this location is not allowed.' );

// Define CMS environment
if (!defined('_CMSAPI_ADMIN_SIDE')) define('_CMSAPI_ADMIN_SIDE', 1);
foreach (array('aliro','mambo','joomla') as $cmsapi_cms) @include(dirname(__FILE__)."/cms_is_$cmsapi_cms.php");
require(_CMSAPI_ABSOLUTE_PATH.'/components/com_glossary/glossary.autoload.php');
// End of CMS environment definition

$interface = cmsapiInterface::getInstance();
$mosConfig_absolute_path = $interface->getCfg('absolute_path');
$mosConfig_lang = $interface->getCfg('lang');
$mosConfig_live_site = $interface->getCfg('live_site');
$mosConfig_sitename = $interface->getCfg('sitename');
$Large_Text_Len = 300;
$Small_Text_Len = 150;
if(file_exists($mosConfig_absolute_path."/components/com_glossary/"._CMSAPI_LANGFILE.$mosConfig_lang.'.php')) require_once($mosConfig_absolute_path."/components/com_glossary/"._CMSAPI_LANGFILE.$mosConfig_lang.'.php');
require_once($mosConfig_absolute_path."/components/com_glossary/"._CMSAPI_LANGFILE."english.php");

$toolbar = new glossaryToolbar();