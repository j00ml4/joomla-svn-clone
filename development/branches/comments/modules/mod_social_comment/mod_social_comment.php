<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_comment
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.html.pagination');
jimport('joomla.social.comments');

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

	// We need to get some values if they are not present.
	$params->def('route', $uri->toString(array('scheme', 'user', 'pass', 'host', 'port', 'path', 'query')));
	$params->def('title', $document->getTitle());
}

// If module parameters set the context, they always win.
$context	= $params->def('context',	$context);
$extension	= $params->def('extension',	$extension);

// If we do not have a context set, then lets exit gracefully.
if ($context == 'error' || $extension == 'error') {
	return false;
}

// Get the list of published comments for the given content context and list ordering.
$comments = JComments::getCommentsByContext($params->get('context'), 1, (strtolower($params->get('list_order')) == 'asc'));

// Get the total number of published comments for the given content context.
$total = (int) JComments::getTotalByContext($params->get('context'), 1);

// Get a pagination object for the comments thread.
$pagination = new JPagination($total, JRequest::getInt('limitstart'), $params->get('pagination', 10));

// Get a form object for the comment submission form.
$form = JComments::getForm($params);

// Render the module using the specified layout.
require(JModuleHelper::getLayoutPath('mod_social_comment', $params->get('layout', 'default')));
