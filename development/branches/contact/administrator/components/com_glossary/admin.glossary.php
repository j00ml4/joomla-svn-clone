<?php

/**************************************************************
* This file is part of$current_dir Glossary
* Copyright (c) 2008-9 Martin Brampton
* Issued as open source under GNU/GPL
* For support and other information, visit http://remository.com
* To contact Martin Brampton, write to martin@remository.com
*
* See header in glossary.php for further details
*/

// Don't allow direct linking
if (!defined( '_VALID_MOS' ) AND !defined('_JEXEC')) die( 'Direct Access to this location is not allowed.' );

if (!function_exists('version_compare') OR version_compare(PHP_VERSION, '5.2.3') < 0) die ('Sorry, this version of Glossary requires PHP version 5.2.3 or later');

//error_reporting(E_ALL);

// Define CMS environment
if (!defined('_CMSAPI_ADMIN_SIDE')) define('_CMSAPI_ADMIN_SIDE', 1);
foreach (array('aliro','mambo','joomla') as $cmsapi_cms) @include(dirname(__FILE__)."/cms_is_$cmsapi_cms.php");
require(_CMSAPI_ABSOLUTE_PATH.'/components/com_glossary/glossary.autoload.php');
// End of CMS environment definition

new cmsapiAdminManager('glossary', 'com_glossary');
