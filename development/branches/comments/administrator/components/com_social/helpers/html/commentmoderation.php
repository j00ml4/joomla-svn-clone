<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * HTML behavior class for Social
 *
 * @package		Joomla.Administrator
 * @subpackage	com_social
 * @since		1.6
 */
class JHtmlCommentModeration
{
	function action($item)
	{
		$html = array();
		$html[] = '<ul class="published_selector">';

		// Defer is only an option if a state has not yet been set.
		if ($item->published == 0) {
			$html[] = '<li class="defer"><input type="radio" id="moderate_defer_'.$item->id.'" name="moderate['.$item->id.']" value="0" checked="checked" />';
			$html[] = '	<label for="moderate_defer_'.$item->id.'">'.JText::_('COM_SOCIAL_DEFER').'</label></li>';
		}

		// Add the publish state.
		$html[] = '<li class="publish"><input type="radio" id="moderate_publish_'.$item->id.'" name="moderate['.$item->id.']" value="1"'.(($item->published == 1) ? ' checked="checked"' : null).' />';
		$html[] = '	<label for="moderate_publish_'.$item->id.'">'.JText::_('COM_SOCIAL_PUBLISH').'</label></li>';

		// Add the spam state.
		$html[] = '<li class="spam"><input type="radio" id="moderate_spam_'.$item->id.'" name="moderate['.$item->id.']" value="-1"'.(($item->published == -1) ? ' checked="checked"' : null).' />';
		$html[] = '	<label for="moderate_spam_'.$item->id.'">'.JText::_('COM_SOCIAL_SPAM').'</label></li>';

		// Add the delete state.
		$html[] = '<li class="delete"><input type="radio" id="moderate_delete_'.$item->id.'" name="moderate['.$item->id.']" value="-2"'.(($item->published == -2) ? ' checked="checked"' : null).' />';
		$html[] = '	<label for="moderate_delete_'.$item->id.'">'.JText::_('COM_SOCIAL_DELETE').'</label></li>';

		$html[] = '</ul>';

		return implode("\n", $html);
	}
}
