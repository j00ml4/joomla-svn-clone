<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_banners
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class modBannersHelper
{
	function &getList(&$params)
	{
		jimport('joomla.application.component.model');
		JModel::addIncludePath(JPATH_ROOT.'/components/com_banners/models');
		$document	= JFactory::getDocument();
		$app		= JFactory::getApplication();
		$keywords	= explode(',', $document->getMetaData('keywords'));

		$model = JModel::getInstance('Banners','BannersModel',array('ignore_request'=>true));
		$model->setState('filter.client_id', (int) $params->get('cid'));
		$model->setState('filter.category_id', $params->get('catid', array()));
		$model->setState('list.limit', (int) $params->get('count', 1));
		$model->setState('list.start', 0);
		$model->setState('filter.ordering', $params->get('ordering'));
		$model->setState('filter.tag_search', $params->get('tag_search'));
		$model->setState('filter.keywords', $keywords);
		$model->setState('filter.language', $app->getLanguageFilter());

		$banners = &$model->getItems();
		$model->impress();

		return $banners;
	}
}