<?php
/**
 * @version		$Id: sef.php 14563 2010-02-04 06:58:22Z eddieajau $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
* Joomla! Language SEF Plugin
*
 * @package		Joomla
 * @subpackage	System
 */
class plgSystemLanguageSEF extends JPlugin
{
	public function onAfterInitialise()
	{
		$app = JFactory::getApplication();
		if ($app->isSite()) {
			$router = $app->getRouter();

			// attach build rules for language SEF
			$router->attachBuildRule(array($this, "buildRule"));

			// attach parse rules for language SEF
			$router->attachParseRule(array($this, "parseRule"));
		}
	}
	public function buildRule(&$router, &$uri)
	{
		if ($router->getMode()==JROUTER_MODE_SEF) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->from('#__languages');
			$query->select('sef');
			$query->select('lang_code');
			$query->where('lang_code='.$db->Quote(JFactory::getLanguage()->getTag()));
			$db->setQuery($query);
			$result = $db->loadObject();
			$component = JComponentHelper::getParams('com_languages');
			if ($result->sef /*&& $result->lang_code != $component->get('site')*/) {
				$path = $uri->getPath();
				$uri->setPath($path.'/'.$result->sef);
			}
		}
	}
	public function parseRule(&$router, &$uri)
	{
		if ($router->getMode()==JROUTER_MODE_SEF) {
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->from('#__languages');
			$query->select('sef');
			$query->select('lang_code');
			$query->where('lang_code='.$db->Quote(JFactory::getLanguage()->getTag()));
			$db->setQuery($query);
			$result = $db->loadObject();
			$component = JComponentHelper::getParams('com_languages');
			if ($result->sef /*&& $result->lang_code != $component->get('site')*/) {
				$path = $uri->getPath();
				$parts = explode('/',$path);
				array_shift($parts);
				$uri->setPath(implode('/',$parts));
			}
		}
		return array();
	}	
}
