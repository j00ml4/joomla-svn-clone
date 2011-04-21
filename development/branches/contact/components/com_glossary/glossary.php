<?php

/**************************************************************
* This file is part of Glossary
* Copyright (c) 2008-9 Martin Brampton
* Issued as open source under GNU/GPL version 2
* 
* Please note that this code is released subject to copyright
* and is licensed to you under the terms of the GPL version 2.
* It is NOT in the public domain.
*
* Although the GPL grants generous rights to you, it does require
* you to observe certain limitations.  Please study the GPL
* if you need details.

* For support and other information, visit http://remository.com
* To contact Martin Brampton, write to martin@remository.com
*
* The glossary component was originally based on Arthur Konze's 
* Akobook Guestbook component and on the Weblinks component. 
*
* Up to version 1.3 the Glossary was developed by Michelle Farren; 
* development was then carried forward up to version 1.5 by Sascha 
* Claren. Bernhard Zechmann created 1.9.x versions (www.zechmann.com) 
*
* All upcoming versions are developed and released by Martin Brampton
* with the Glossary component totally rewritten at release 2.5.
* Subsequent versions consist of code entirely written by Martin 
* Brampton except where specifically stated.
*
**/

/* Parameters removed from glossary popups plugin
 *
		<param name="outputmode" type="list" default="0" label="Select ouput mode" description="If you selected GlossarBox Module you need to install the Modul: Glossary Description Box">
			<option value="0">Popup on mouseover</option>
			<option value="4">Popup on mouseover and click</option>
			<option value="3">Popup on click only</option>
			<option value="1">Browser ToolTips</option>
			<option value="2">GlossarBox Module</option>
		</param>
		<param name="fgcolor" type="text" default="#CCCCFF" label="Box forceground color (text)" description=""></param>
		<param name="bgcolor" type="text" default="#333399" label="Box background color (border and caption)" description=""></param>
		<param name="txtcolor" type="text" default="#000000" label="Box textcolor" description=""></param>
		<param name="capcolor" type="text" default="#FFFFFF" label="Box caption textcolor" description=""></param>
		<param name="width" type="text" default="300" label="Box width" description="Width if the box"></param>
		<param name="position" type="list" default="BELOW" label="Box position" description="Set the postion of the box to below or above mousepointer">
			<option value="BELOW">BELOW</option>
			<option value="ABOVE">ABOVE</option>
		</param>
		<param name="alignment" type="list" default="RIGHT" label="Box alignment" description="Set alignment of the box from mousepointer">
			<option value="LEFT">LEFT</option>
			<option value="CENTER">CENTER</option>
			<option value="RIGHT">RIGHT</option>
		</param>
		<param name="offset_x" type="text" default="10" label="X Offset" description="How far away from the mousepointer the popup will show up, horizontally"></param>
		<param name="offset_y" type="text" default="10" label="Y Offset" description="How far away from the mousepointer the popup will show up, vertically"></param>
		<param name="css" type="text" default="cursor:help;border-bottom:1px dotted #000000;" label="CSS-Style for the term"></param>


 */

// Don't allow direct linking
if (!defined( '_VALID_MOS' ) AND !defined('_JEXEC')) die( 'Direct Access to this location is not allowed.' );

if (!function_exists('version_compare') OR version_compare(PHP_VERSION, '5.2.3') < 0) die ('Sorry, this version of Glossary requires PHP version 5.2.3 or above');

// Define CMS environment
foreach (array('aliro','mambo','joomla') as $cmsapi_cms) @include(dirname(__FILE__)."/cms_is_$cmsapi_cms.php");
require(_CMSAPI_ABSOLUTE_PATH.'/components/com_glossary/glossary.autoload.php');

//error_reporting(E_ALL);

// Add first words to the glossary table if missing
glossaryEntry::setFirstWords();

// 'entry' is treated as synonymous with 'list' because xmap is out of date
$alternatives = array ('entry' => 'list');

$admin = new cmsapiUserAdmin('task', $alternatives, 'list', 'Glossary', 'com_glossary');