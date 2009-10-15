<?php
/**
 * @version		$Id: router.php 11952 2009-06-01 03:21:19Z robs $
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * @param	array
 * @return	array
 */
function SearchBuildRoute(&$query)
{
	$segments = array();

	if (isset($query['searchword'])) {
		$segments[] = $query['searchword'];
		unset($query['searchword']);
	}

	// Retrieve configuration options - needed to know which SEF URLs are used
	$app = &JFactory::getApplication();
	// Allows for searching on strings that include ".xxx" that appear to Apache as an extension
	if (($app->getCfg('sef')) && ($app->getCfg('sef_rewrite')) && !($app->getCfg('sef_suffix'))) {
		$segments[] .= '/';
	}

	if (isset($query['view'])) {
		unset($query['view']);
	}
	return $segments;
}

/**
 * @param	array
 * @return	array
 */
function SearchParseRoute($segments)
{
	$vars = array();

	$searchword	= array_shift($segments);
	$vars['searchword'] = $searchword;
	$vars['view'] = 'search';

	return $vars;
}