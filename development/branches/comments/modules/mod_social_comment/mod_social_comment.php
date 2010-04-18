<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_comment
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');

// merge the component configuration into the module parameters
$params->merge(JComponentHelper::getParams('com_social'));

// if Comments are disabled, do nothing and return
if ($params->get('enable_comments') == 0) {
	return false;
}

// get the user object
$user = &JFactory::getUser();

// get the uri object
$uri = &JURI::getInstance();

// get the document object
$document = &JFactory::getDocument();

// initialize context variables
$context	= 'error';
$contextId	= 0;

// if the autodetect context parameter is set, let's use it
if ($params->get('autodetect')) {
	// get the application object to retrieve the context
	$application = &JFactory::getApplication('site');

	// assumption is that if a global context is set, it is atomic
	$context	= (string) $application->get('jx.context', $context);
	$contextId	= (string) $application->get('jx.context_id', $contextId);

	// We need to get some values if they are not present.
	$params->def('url', 'index.php?option='.JRequest::getCmd('option').'&view='.JRequest::getCmd('view').'&id='.JRequest::getInt('id'));
	$params->def('route', $uri->toString(array('scheme', 'user', 'pass', 'host', 'port', 'path', 'query')));
	$params->def('title', $document->getTitle());
}

// if module parameters set the context, they always win
$context	= $params->def('context',	$context);
$contextId	= $params->def('context_id', $contextId);

// if we do not have a context set, then lets exit gracefully
if (($context == 'error') and ($contextId == 0)) {
	return false;
}

// import library dependencies
require_once(dirname(__FILE__).DS.'helper.php');

// Get the thread.
$thread = modSocialCommentHelper::getThread($params);

// Get the comments.
$comments = &modSocialCommentHelper::getComments($params);

// get the comment thread pagination object
$pagination = &modSocialCommentHelper::getPagination($params);

// render the module
require(JModuleHelper::getLayoutPath('mod_social_comment', $params->get('layout', 'default')));
