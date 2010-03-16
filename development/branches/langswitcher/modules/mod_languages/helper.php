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
		$selected = JSite::getLanguage(!$useDefault);
		$db = JFactory::getDBO();
		$query = new JDatabaseQuery;
		$query->select($db->nameQuote('lang_code').' as '.$db->nameQuote('value'));
		$query->select($db->nameQuote('title').' as '.$db->nameQuote('text'));
		$query->from($db->nameQuote('#__languages'));
		$query->where($db->nameQuote('published').'=1');
		$query->order($db->nameQuote('text'));
		$db->setQuery($query);
		$result = $db->loadAssocList();
		foreach($result as $i=>$language) {
			if (!JLanguage::exists($language['value'])) {
				unset($result[$i]);
			}
			else {
				$result[$i]['redirect']='http://www.google.fr';
			}
		}
		if ($useDefault) {
			$option = array();
			$option['text'] = JText::_('JOPTION_USE_DEFAULT');
			$option['value'] = 'default';
			$option['redirect']=$result[JSite::getLanguage()];
			array_unshift($result, $option);
		}
		return $result;
	}
}
