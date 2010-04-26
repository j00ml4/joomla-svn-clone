<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_rating
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.social.ratings');

// merge the component configuration into the module parameters
$params->merge(JComponentHelper::getParams('com_social'));

// Initialise variables.
$context	= 'error';
$extension	= 'error';

// if the autodetect context parameter is set, let's use it
if ($params->get('autodetect')) {
	// get the application object to retrieve the context
	$application = JFactory::getApplication('site');

	// assumption is that if a global context is set, it is atomic
	$context	= (string) $application->get('content.context', $context);
	$parts		= explode('.', $context);
	$extension	= $parts[0];
}

// if module parameters set the context, they always win
$context	= $params->def('context',	$context);
$extension	= $params->def('extension',	$extension);

// if we do not have a context set, then lets exit gracefully
if ($context == 'error' || $extension == 'error') {
	return false;
}

$user		= JFactory::getUser();
$uri		= JURI::getInstance();
$document	= &JFactory::getDocument();

// Get the thread data.
$rating = JRatings::getRatingsByContext($params->get('context'));

// render the module
require(JModuleHelper::getLayoutPath('mod_social_rating', $params->get('layout', 'default')));
