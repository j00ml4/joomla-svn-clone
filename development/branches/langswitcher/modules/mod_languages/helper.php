<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_languages
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.language.helper');
jimport('joomla.utilities.utility');
abstract class modLanguagesHelper
{
	/**
	 * Get the language from
	 * - the cookie session if the user is not logged in or
	 * - the user preference
	 */
	public static function getTag(&$params)
	{
		$user = JFactory::getUser();
		$tag = JRequest::getString(JUtility::getHash('language'), null ,'cookie');
		if(empty($tag) && $user->id) {
			$tag = $user->getParam('language');
		}
		return $tag;
	}
	public static function getList(&$params)
	{
		$useDefault = $params->get('default');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);;
		$query->from($db->nameQuote('#__languages'));
		$query->select($db->nameQuote('lang_code').' AS '.$db->nameQuote('value'));
		$query->select($db->nameQuote('title').' AS '.$db->nameQuote('text'));
		$query->select($db->nameQuote('image'));
		$query->where($db->nameQuote('published').'=1');
		$db->setQuery($query);
		$result = $db->loadAssocList('value');
		$query->clear();
		$query->from($db->nameQuote('#__menu'));
		$query->select('link');
		$query->select('type');
		$query->select('id');
		$query->select('params');
		$query->select('language');
		$query->where('home=1');
		$db->setQuery($query);
		$home = $db->loadAssocList('language');
		foreach($result as $i=>&$language) {
			if (!JLanguage::exists($language['value'])) {
				unset($result[$i]);
			}
			else {
				$menu = array_key_exists($language['value'], $home) ? $home[$language['value']] : $home['*'];
				switch ($menu['type']) {
				case 'url':
					if ((strpos($menu['link'], 'index.php?') === 0) && (strpos($menu['link'], 'Itemid=') === false)) {
						// If this is an internal Joomla link, ensure the Itemid is set.
						$language['redirect'] = $menu['link'] . '&amp;Itemid='.$menu['id'];
					}
					else {
						$language['redirect'] = $menu['link'];
					}
					break;

				case 'alias':
					// If this is an alias use the item id stored in the parameters to make the link.
					$registry = new JRegistry;
					$registry->loadJSON($menu['params']);
					$language['redirect'] = 'index.php?Itemid='.$registry->get('aliasoptions');
					break;

				default:
					$router = JSite::getRouter();
					if ($router->getMode() == JROUTER_MODE_SEF) {
						$language['redirect'] = 'index.php?Itemid='.$menu['id'];
					} else {
						$language['redirect'] = $menu['link'] . '&Itemid='.$menu['id'];
					}
					break;
				}
			}
		}
		if ($useDefault && count($result)) {
			$option = array();
			$option['text'] = JText::_('MOD_LANGUAGES_OPTION_DEFAULT_LANGUAGE');
			$option['value'] = 'default';
			$config =& JFactory::getConfig();
			$paramsLanguagues =  JComponentHelper::getParams('com_languages');
			$option['redirect']=$result[$paramsLanguagues->get('site', $config->get('language','en-GB'))]['redirect'];
			array_unshift($result, $option);
		}
		return $result;
	}
}
