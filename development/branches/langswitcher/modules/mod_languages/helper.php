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
	public static function getList(&$params)
	{
		$useDefault = $params->get('default');
		$db = JFactory::getDBO();
		$query = new JDatabaseQuery;
		$query->select($db->nameQuote('lang_code').' as '.$db->nameQuote('value'));
		$query->select($db->nameQuote('title').' as '.$db->nameQuote('text'));
		$query->from($db->nameQuote('#__languages'));
		$query->where($db->nameQuote('published').'=1');
		$query->order($db->nameQuote('text'));
		$db->setQuery($query);
		$result = $db->loadAssocList('value');
		$menus= JFactory::getApplication()->getMenu();
		foreach($result as $i=>$language) {
			if (!JLanguage::exists($language['value'])) {
				unset($result[$i]);
			}
			else {
				$item = $menus->getDefault($language['value']);
				switch ($item->type) {
				case 'url':
					if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false)) {
						// If this is an internal Joomla link, ensure the Itemid is set.
						$item->link = $tmp->link.'&amp;Itemid='.$item->id;
					}
					break;

				case 'alias':
					// If this is an alias use the item id stored in the parameters to make the link.
					$item->link = 'index.php?Itemid='.$item->params->get('aliasoptions');
					break;

				default:
					$router = JSite::getRouter();
					if ($router->getMode() == JROUTER_MODE_SEF) {
						$item->link = 'index.php?Itemid='.$item->id;
					} else {
						$item->link .= '&Itemid='.$item->id;
					}
					break;
				}
				$result[$i]['redirect']=$item->link;
			}
		}
		if ($useDefault && count($result)) {
			$option = array();
			$option['text'] = JText::_('JOPTION_USE_DEFAULT');
			$option['value'] = 'default';
			$config =& JFactory::getConfig();
			$paramsLanguagues =  JComponentHelper::getParams('com_languages');
			$option['redirect']=$result[$paramsLanguagues->get('site', $config->get('language','en-GB'))]['redirect'];
			array_unshift($result, $option);
		}
		return $result;
	}
}
