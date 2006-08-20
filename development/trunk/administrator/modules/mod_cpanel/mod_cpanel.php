<?php
/**
* @version $Id: mod_cpanel.php 4394 2006-08-05 03:03:55Z Jinx $
* @package Joomla
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.presentation.pane');
jimport('joomla.application.extension.module');

$modules = JModuleHelper::getModules('cpanel');

$pane   =& JPane::getInstance('sliders');
$pane->startPane("content-pane");

foreach ($modules as $module) {
	$title = $module->title ;
	$pane->startPanel( $title, "cpanel-panel" );
	echo JModuleHelper::renderModule($module);
	$pane->endPanel();
}

$pane->endPane();








