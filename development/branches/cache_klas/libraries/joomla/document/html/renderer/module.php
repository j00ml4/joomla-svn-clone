<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @copyright	Copyright (C) 2010 Klas Berlič
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('JPATH_BASE') or die;

/**
 * JDocument Module renderer
 *
 * @package		Joomla.Framework
 * @subpackage	Document
 * @since		1.5
 */
class JDocumentRendererModule extends JDocumentRenderer
{
	/**
	 * Renders a module script and returns the results as a string
	 *
	 * @param	string $name	The name of the module to render
	 * @param	array $params	Associative array of values
	 * @return	string			The output of the script
	 */
	public function render($module, $params = array(), $content = null)
	{
		if (!is_object($module))
		{
			$title	= isset($params['title']) ? $params['title'] : null;

			$module = &JModuleHelper::getModule($module, $title);

			if (!is_object($module))
			{
				if (is_null($content)) {
					return '';
				}
				else {
					/**
					 * If module isn't found in the database but data has been pushed in the buffer
					 * we want to render it
					 */
					$tmp = $module;
					$module = new stdClass();
					$module->params = null;
					$module->module = $tmp;
					$module->id = 0;
					$module->user = 0;
				}
			}
		}

		// get the user and configuration object
		//$user = &JFactory::getUser();
		$conf = &JFactory::getConfig();

		// set the module content
		if (!is_null($content)) {
			$module->content = $content;
		}

		//get module parameters
		$mod_params = new JRegistry;
		$mod_params->loadJSON($module->params);

		$contents = '';
		
		// Depreciated, included only for compatibility purposes! Use JModuleHelper::cache from within the module instead!
		// @Todo remove this in 1.7.
		
		if ($mod_params->get('cache', 0)  && $conf->get('caching'))
		{	
			$user = &JFactory::getUser();
			$cache = &JFactory::getCache($module->module,'callback');

			$cache->setLifeTime($mod_params->get('cache_time', $conf->get('cachetime') * 60));
			
			// default to itemid creating mehod and workarounds on
			$contents =  $cache->get(array('JModuleHelper', 'renderModule'), array($module, $params), $module->id. $user->get('aid', 0).JRequest::getVar('Itemid',null,'default','INT'),true);
		
		}
		else {
			$contents = JModuleHelper::renderModule($module, $params);
		}

		return $contents;
	}
}
