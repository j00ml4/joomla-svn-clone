<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_languages
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.language.helper');
jimport('joomla.utilities.utility');

JLoader::register('MenusHelper', JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');

abstract class modLanguagesHelper
{
	public static function getList(&$params)
	{
		$lang = JFactory::getLanguage();
		$languages	= JLanguageHelper::getLanguages();
		$db			= JFactory::getDBO();
		$app		= JFactory::getApplication();
		$query		= $db->getQuery(true);

		$query->select('id');
		$query->select('language');
		$query->from($db->nameQuote('#__menu'));
		$query->where('home=1');
		$db->setQuery($query);
		$homes = $db->loadObjectList('language');

		if ($app->get('menu_associations', 0)) {
			$menu = $app->getMenu();
			$active = $menu->getActive();
			if ($active) {
				$associations = MenusHelper::getAssociations($active->id);
			}
		}
		foreach($languages as $i => &$language) {
			// Do not display language without frontend UI
			if (!JLanguage::exists($language->lang_code)) {
				unset($languages[$i]);
			}
			// Do not display language without specific home menu
			elseif (!isset($homes[$language->lang_code])) {
				unset($languages[$i]);
			}
			else {
				if ($app->getLanguageFilter()) {
					$language->active =  $language->lang_code == $lang->getTag();
					if (isset($associations[$language->lang_code]) && $menu->getItem($associations[$language->lang_code])) {
						$itemid = $associations[$language->lang_code];
						if ($app->getCfg('sef')=='1') {
							$language->link = JRoute::_('index.php?lang='.$language->sef.'&Itemid='.$itemid);
						}
						else {
							$language->link = 'index.php?lang='.$language->sef;
						}
					}
					else {
						if ($app->getCfg('sef')=='1') {
							$itemid = isset($homes[$language->lang_code]) ? $homes[$language->lang_code]->id : $homes['*']->id;
							$language->link = JRoute::_('index.php?lang='.$language->sef.'&Itemid='.$itemid);
						}
						else {
							$language->link = 'index.php?lang='.$language->sef;
						}
					}
				}
				else {
					$language->link = 'index.php';
				}
			}
		}
		return $languages;
	}
}
