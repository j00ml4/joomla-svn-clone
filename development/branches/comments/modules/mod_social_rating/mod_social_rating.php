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
if ($params->get('enable_ratings') == 0) {
	return false;
}

// if the JXtended Libraries are not present exit gracefully
if (!defined('JXVERSION')) {
	JError::raiseNotice(500, JText::_('JX_Libraries_Missing'));
	return false;
}

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

// get the user object
$user = &JFactory::getUser();

// get the uri object
$uri = &JURI::getInstance();

// get the document object
$document = &JFactory::getDocument();

// Get the thread.
$thread = modSocialRatingHelper::getThread($params);

// get the item rating
$rating = modSocialRatingHelper::getRating($params);

// render the module
require(JModuleHelper::getLayoutPath('mod_social_rating', $params->get('layout', 'default')));
