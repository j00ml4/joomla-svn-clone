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
			$language	= $uri->getVar('language');
			$Itemid		= $uri->getVar('Itemid');
			$db			= JFactory::getDBO();
			$query		= $db->getQuery(true);
			$query->from('#__languages');
			$query->select('sef');
			$query->select('lang_code');
			$query->where('lang_code='.$db->quote($language ? $language : JFactory::getLanguage()->getTag()));
			$query->join('LEFT','#__menu as m on m.id='.$db->quote($Itemid));
			$query->select('m.home');
			$db->setQuery($query);
			$result = $db->loadObject();
			if ($result && $result->sef) {
				$uri->delVar('language');
				$path = $uri->getPath();
				if ($result->home && $language) {
					$uri->delVar('option');
					$uri->delVar('Itemid');
				}
				$component = JComponentHelper::getParams('com_languages');
				if ($result->lang_code != $component->get('site') || $this->params->get('default_suffix')) {
					$uri->setPath($path.'/'.$result->sef.'/');
				}
			}
		}
	}
	public function parseRule(&$router, &$uri)
	{
		$array = array();
		if ($router->getMode()==JROUTER_MODE_SEF) {
			$path = $uri->getPath();
			$parts = explode('/',$path);
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			
			// Try to find the language according to the sef prefix
			$query->from($db->nameQuote('#__languages'));
			$query->select('sef');
			$query->select('lang_code');
			$query->where('sef='.$db->quote($parts[0]));
			$query->where('published=1');
			$db->setQuery($query);
			$result = $db->loadObject();
			if (!$result ||  !JLanguage::exists($result->lang_code)) {

				// Use the default language
				$component = JComponentHelper::getParams('com_languages');
				$query = $db->getQuery(true);
				$query->from($db->nameQuote('#__languages'));
				$query->select('sef');
				$query->select('lang_code');
				$query->where('lang_code='.$db->quote($component->get('site')));
				$db->setQuery($query);
				$result = $db->loadObject();
			}
			else {
				array_shift($parts);
				$uri->setPath(implode('/',$parts));
			}
			$array = array('language'=>$result->lang_code);
			$lang = JFactory::getLanguage();
			$lang->setLanguage($result->lang_code);
			$config = JFactory::getConfig();
			$cookie_domain = $config->get('config.cookie_domain', '');
			$cookie_path = $config->get('config.cookie_path', '/');
			setcookie(JUtility::getHash('language'), $result->lang_code, time() + 365 * 86400, $cookie_path, $cookie_domain);
		}
		return $array;
	}
}
