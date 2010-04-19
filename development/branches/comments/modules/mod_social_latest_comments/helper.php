<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_latest
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Add the appropriate include paths for models.
jimport('joomla.application.component.model');
JModel::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_social'.DS.'models');

jx('jx.html.html.jxstring');
jx('jx.html.bbcode');

class modSocialLatestHelper
{
	function getList($params)
	{
		// get the comments rating model
		$model = &modSocialLatestHelper::getModel($params);

		// verify its a model

		$list = &$model->getItems();
		return $list;
	}

	function truncateBody($text, $length=50)
	{
		static $parser;

		if (empty($parser)) {
			// import library dependencies
			jx('jx.html.bbcode');

			// instantiate bbcode parser object
			$parser = &JBBCode::getInstance(array(
				'smiley_path' => JPATH_ROOT.'/media/jxtended/img/smilies/default',
				'smiley_url' => JURI::base().'/media/jxtended/img/smilies/default'
			));
		}

		$text = strip_tags($parser->parse($text));
		return JXHTMLString::truncate($text, $length);
	}

	function &getModel($params)
	{
		static $model;

		if (empty($model))
		{
			// get a comments comments model instance
			$model = &JModel::getInstance('Comments', 'CommentsModel');
			$model->getState();

			// prime the list parameters.
			$model->setState('filter.context', $params->get('context'));
			$model->setState('list.ordering', 'a.created_date DESC');
			$model->setState('list.limit', $params->get('limit', 5));
			$model->setState('check.trackback', $params->get('hide_trackbacks', 1));
		}

		return $model;
	}
}
