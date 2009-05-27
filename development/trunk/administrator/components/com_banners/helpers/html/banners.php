<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_banners
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Banners HTML Helper
 *
 * @package		Joomla.Administrator
 * @subpackage	com_banners
 */
class JHtmlBanners
{
	function clients($name, $attribs, $selected)
	{
		$db = &JFactory::getDbo();

		// Build Client select list
		$db->setQuery(
			'SELECT cid AS value, name AS text'
			.' FROM #__bannerclient'
		);
		$options = $db->loadObjectList();

		array_unshift($options, JHtml::_('select.option',  '0', '- ' . JText::_('Select Client') . ' -'));
		return JHtml::_(
            'select.genericlist',
            $options,
            $name,
            $attribs,
            'value',
            'text',
            $selected
      );
	}
}