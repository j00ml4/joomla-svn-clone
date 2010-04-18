<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_rating
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');

// merge the component configuration into the module parameters
$params->merge(JComponentHelper::getParams('com_comments'));

// if Comments are disabled, do nothing and return
if ($params->get('enable_sharing') == 0) {
	return false;
}

// import library dependencies
require_once(dirname(__FILE__).DS.'helper.php');

// get the user object
$user = &JFactory::getUser();

// get the document object
$document = &JFactory::getDocument();

// get the base url
$baseurl = JURI::base();

// render the module
require(JModuleHelper::getLayoutPath('mod_social_share', $params->get('layout', 'default')));
