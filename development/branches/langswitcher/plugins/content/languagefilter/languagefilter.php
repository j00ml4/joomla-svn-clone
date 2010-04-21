<?php

/**
 * @version		$Id: loadmodule.php 16209 2010-04-19 03:47:23Z chdemko $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// import JPlugin class
jimport('joomla.plugin.plugin');
class plgContentLanguageFilter extends JPlugin {
	public function onPrepareQuery($name, &$query) {
		$app = JFactory::getApplication();
		if ($app->isSite()) {
			$db = JFactory::getDBO();
			$language = JSite::getLanguage();
			if (in_array($name, array('com_content.articles', 'com_content.archive', 'com_content.frontpage', 'com_banners.category', 'com_contact.category', 'com_newsfeeds.category', 'com_weblinks.category', 'mod_related_items', 'mod_articles_archive'))) {
				if ($this->params->get('use_inherited_language', 0) || $this->params->get('show_untagged_content', 1)) {
					$query->where('(a.language=' . $db->Quote($language) . ' OR a.language=' . $db->Quote('') . ')');
				}
				else {
					$query->where('a.language=' . $db->Quote($language));
				}
				if ($this->params->get('use_inherited_language', 0)) {

					// Filter by inherited language
					$query->select('a.language');
					$query->join('LEFT', '#__categories as p on p.lft <= c.lft AND p.rgt >=c.rgt AND p.language!=\'\'');
					$query->select('MIN(CONCAT(LPAD(p.rgt,30," "),p.language)) as inherited_language');
					$query->group('a.id');
					if ($this->params->get('show_untagged_content', 1)) {
						$query->having('(a.language=' . $db->Quote($language) . ' OR inherited_language IS NULL OR substr(inherited_language,31)=' . $db->Quote($language) . ')');
					}
					else {
						$query->having('(a.language=' . $db->Quote($language) . ' OR substr(inherited_language,31)=' . $db->Quote($language) . ')');
					}
				}
			}
			elseif ($name == 'categories') {
				if ($this->params->get('show_untagged_content', 1)) {
					$query->where('(c.language=' . $db->Quote($language) . ' OR c.language = ' . $db->Quote('') . ' )');
				}
				else {
					$query->where('c.language=' . $db->Quote($language));
				}
			}
			elseif ($name == 'menus2') {
				if ($this->params->get('use_inherited_language', 0) || $this->params->get('show_untagged_content', 1)) {
					$query->where('(a.language=' . $db->Quote($language) . ' OR a.language=' . $db->Quote('') . ')');
				}
				else {
					$query->where('a.language=' . $db->Quote($language));
				}
				if ($this->params->get('use_inherited_language', 0)) {

					// Filter by inherited language
					$query->select('a.language');
					$query->join('LEFT', '#__categories as p on p.lft <= c.lft AND p.rgt >=c.rgt AND p.language!=\'\'');
					$query->select('MIN(CONCAT(LPAD(p.rgt,30," "),p.language)) as inherited_language');
					$query->group('a.id');
					if ($this->params->get('show_untagged_content', 1)) {
						$query->having('(a.language=' . $db->Quote($language) . ' OR inherited_language IS NULL OR substr(inherited_language,31)=' . $db->Quote($language) . ')');
					}
					else {
						$query->having('(a.language=' . $db->Quote($language) . ' OR substr(inherited_language,31)=' . $db->Quote($language) . ')');
					}
				}
			}
			elseif ($name == 'modules') {
				if ($this->params->get('show_untagged_content', 1)) {
					$query->where('(m.language=' . $db->Quote($language) . ' OR m.language=' . $db->Quote('') . ')');
				}
				else {
					$query->where('m.language=' . $db->Quote($language));
				}
			}
		}
	}
}

