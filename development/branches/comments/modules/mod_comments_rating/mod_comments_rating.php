<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	mod_comments_rating
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');

// merge the component configuration into the module parameters
$params->merge(JComponentHelper::getParams('com_comments'));

// if JXtended Comments are disabled, do nothing and return
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
$thread = modCommentsRatingHelper::getThread($params);

// get the item rating
$rating = modCommentsRatingHelper::getRating($params);

// render the module
require(JModuleHelper::getLayoutPath('mod_comments_rating', $params->get('layout', 'default')));
