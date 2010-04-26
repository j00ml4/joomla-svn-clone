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

// Merge the component configuration into the module parameters.
$params->merge(JComponentHelper::getParams('com_social'));

// Initialize variables.
$document	= JFactory::getDocument();
$context	= 'error';
$extension	= 'error';
$uri		= JURI::getInstance();
$user		= JFactory::getUser();

// If the autodetect context parameter is set, let's use it.
if ($params->get('autodetect')) {

	// Get the application object to retrieve the context
	$application = JFactory::getApplication('site');

	// Assumption is that if a global content context is set, it is atomic.
	$context	= (string) $application->get('content.context', $context);
	$extension	= substr($context, 0, strcspn($context, '.'));
}

// If module parameters set the context, they always win.
$context	= $params->def('context',	$context);
$extension	= $params->def('extension',	$extension);

// If we do not have a context set, then lets exit gracefully.
if ($context == 'error' || $extension == 'error') {
	return false;
}

// Get the rating data for the content item.
$rating = JRatings::getRatingsByContext($params->get('context'));

// Render the module using the specified layout.
require(JModuleHelper::getLayoutPath('mod_social_rating', $params->get('layout', 'default')));
