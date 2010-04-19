<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_highest_rated
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// merge the component configuration into the module parameters
$params->merge(JComponentHelper::getParams('com_social'));

// import library dependencies
require_once dirname(__FILE__).'/helper.php';

// Initialise variables.
$user		= JFactory::getUser();
$document	= JFactory::getDocument();
$list		= modSocialHighestRatedHelper::getList($params);

// render the module
require(JModuleHelper::getLayoutPath('mod_social_highest_rated', $params->get('layout', 'default')));
