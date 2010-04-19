<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_rating
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// merge the component configuration into the module parameters
$params->merge(JComponentHelper::getParams('com_social'));

// if Comments are disabled, do nothing and return
if ($params->get('enable_sharing') == 0) {
	return false;
}

// import library dependencies
require_once(dirname(__FILE__).'/helper.php');

// Initialise variables.
$user		= JFactory::getUser();
$document	= JFactory::getDocument();
$baseurl	= JURI::base();

// render the module
require(JModuleHelper::getLayoutPath('mod_social_share', $params->get('layout', 'default')));
