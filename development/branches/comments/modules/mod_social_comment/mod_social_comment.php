<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_comment
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// merge the component configuration into the module parameters
$params->merge(JComponentHelper::getParams('com_social'));

// Initialiase variables.
$user		= JFactory::getUser();
$uri		= JURI::getInstance();
$document	= JFactory::getDocument();
$context	= 'error';

// if the autodetect context parameter is set, let's use it
if ($params->get('autodetect')) {
	// get the application object to retrieve the context
	$application = &JFactory::getApplication('site');

	// assumption is that if a global context is set, it is atomic
	$context	= (string) $application->get('thread.context', $context);

	// We need to get some values if they are not present.
	$params->def('route', $uri->toString(array('scheme', 'user', 'pass', 'host', 'port', 'path', 'query')));
	$params->def('title', $document->getTitle());
}

// if module parameters set the context, they always win
$context = $params->def('context',	$context);

// if we do not have a context set, then lets exit gracefully
if ($context == 'error') {
	return false;
}

// import library dependencies
require_once dirname(__FILE__).'/helper.php';

// Get the thread.
//$thread = modSocialCommentHelper::getThread($params);

// Get the comments.
$comments = &modSocialCommentHelper::getComments($params);

// get the comment thread pagination object
$pagination = &modSocialCommentHelper::getPagination($params);

// render the module
require(JModuleHelper::getLayoutPath('mod_social_comment', $params->get('layout', 'default')));
