<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_search
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class modSearchHelper
{
	function getSearchImage($button_text) {
		$img = JTHML::_('image','searchButton.gif', $button_text, NULL, true);
		return $img;
	}
}