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
	public static $languages, $default_language;
	
	public function onAfterInitialise()
	{
		$app = JFactory::getApplication();
		if ($app->isSite()) {
			$router =& $app->getRouter();

			// attach build rules for language SEF
			$router->attachBuildRule(array($this, 'buildRule'));

			// attach parse rules for language SEF
			$router->attachParseRule(array($this, 'parseRule'));
			
			// load languages
			$db 	=& JFactory::getDBO();
			$query	= $db->getQuery(true);
			$query->select('*')->from('#__languages')->where('published=1');
			$db->setQuery($query);
			$this->languages = $db->loadObjectList('lang_code');
			
			$this->default_language = JComponentHelper::getParams('com_languages')->get('site');
		}
	}
	
	public function buildRule(&$router, &$uri)
	{
		if ($router->getMode() == JROUTER_MODE_SEF) {
			$language	= $uri->getVar('language');
			$language   = $language ? $language : JFactory::getLanguage()->getTag();
			$Itemid		= $uri->getVar('Itemid');

			if (isset($this->languages[$language]) && $this->languages[$language]->sef) {
				$uri->delVar('language');
				if ($Itemid) {
					$menu =& JSite::getMenu()->getItem($Itemid);
					// if no menu - that means that we are routing home menu item of none-current language
					if (!$menu || $menu->home) {
						$uri->delVar('option');
						$uri->delVar('Itemid');
					}
				}
				if ($this->languages[$language]->lang_code != $this->default_language || $this->params->get('default_prefix')) {
					$uri->setPath($uri->getPath().'/'.$this->languages[$language]->sef.'/');
				}
			}
		}
	}
	
	public function parseRule(&$router, &$uri)
	{
		$array = array();
		if ($router->getMode() == JROUTER_MODE_SEF) {
			$path = $uri->getPath();
			$parts = explode('/', $path);

			$lang_code = null;
			foreach($this->languages as $language) {
				if ($language->sef == $parts[0]) {
					$lang_code = $language->lang_code;
					break; 
				}
			}
			
			if (!$lang_code ||  !JLanguage::exists($lang_code)) {
				// Use the default language
				$lang_code = $this->default_language;
			}
			else {
				array_shift($parts);
				$uri->setPath(implode('/', $parts));
			}

			$array = array('language' => $lang_code);
			JFactory::getLanguage()->setLanguage($lang_code);
			$config = JFactory::getConfig();
			$cookie_domain 	= $config->get('config.cookie_domain', '');
			$cookie_path 	= $config->get('config.cookie_path', '/');
			setcookie(JUtility::getHash('language'), $lang_code, time() + 365 * 86400, $cookie_path, $cookie_domain);
		}
		return $array;
	}
}