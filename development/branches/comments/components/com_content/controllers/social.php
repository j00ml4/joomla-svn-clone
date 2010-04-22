<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllersocial');

/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @since		1.6
 */
class ContentControllerSocial extends JControllerSocial
{
	/**
	 * Method to determine if the user can comment on the selected content.
	 *
	 * @param	string	The context for the content.
	 * @return	boolean
	 * @since	1.6
	 */
	protected function canComment($context)
	{
		//tmp
		return true;
		///tmp

		// The context should be in the form com_content.article.N
		// where N is the article id.
		$parts = explode('.', $context);

		if (count($parts) != 3) {
			return false;
		}

		if ($parts[0] != 'com_content') {
			return false;
		}

		if ($parts[1] != 'article') {
			return false;
		}

		$id = (int) $parts[2];
		if (empty($id)) {
			return false;
		}

		$model = JModel::getInstance('Article', 'ContentModel', array('ignore_request' => true));
		if ($article = $model->getItem($id)) {
			return $article->params->get('article-allow_comments');
		} else {
			return false;
		}
	}
}