<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	mod_comments_summary
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');

jx('jx.html.html.jxstring');
jx('jx.html.bbcode');

/**
 * Helper class for the JXtended Comments Summary Module.
 *
 * @static
 * @package		JXtended.Comments
 * @subpackage	mod_comments_summary
 * @version		1.2
 */
class modCommentsSummaryHelper
{
	function getList($params)
	{
		// Get the list data.
		$model	= &modCommentsSummaryHelper::getModel($params);
		$list	= &$model->getItems();
		return $list;
	}

	function truncateBody($text)
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
		return JXHTMLString::truncate($text, 50);
	}

	function &getModel($params)
	{
		static $model;

		if (empty($model))
		{
			// get a comments comments model instance
			jimport('joomla.application.component.model');
			JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_comments'.DS.'models');
			$model = &JModel::getInstance('Comments', 'CommentsModel');
			$model->getState();

			// prime the list parameters.
			$model->setState('list.ordering', 'a.created_date');
			$model->setState('list.direction', 'desc');
			$model->setState('list.limit', $params->get('limit', 5));
			$model->setState('filter.state', '*');
			$model->setState('check.state', false);
		}

		return $model;
	}
}
