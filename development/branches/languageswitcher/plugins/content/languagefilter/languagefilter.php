<?php

/**
 * @version		$Id$
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// import JPlugin class
jimport('joomla.plugin.plugin');
class plgcontentLanguageFilter extends JPlugin {
	public function onPrepareQuery($name, &$query) {
		$app = JFactory::getApplication();
		if ($app->isSite()) {
			$db = JFactory::getDBO();
			$language = JFactory::getLanguage()->getTag();
			if (in_array($name, array('mod_related_items', 'mod_articles_archive', 'plg_search_contacts', 'plg_search_content', 'plg_search_newsfeeds', 'plg_search_weblinks'))) {
				switch ($this->params->get('manage_language')) {
					case 'no':
						$query->where('a.language in (' . $db->Quote($language) . ',' . $db->Quote('*') . ')');
					break;
					case 'inherited':
						// Filter by inherited language
						$query->select('a.language');
						$query->join('LEFT', '#__categories as p on p.lft <= c.lft AND p.rgt >=c.rgt AND p.language!=' . $db->Quote(''));
						$query->select('MIN(CONCAT(LPAD(p.rgt,30," "),p.language)) as inherited_language');
						$query->group('a.id');
						$query->having('(a.language in (' . $db->Quote($language) . ',' . $db->Quote('*') . ')' . ' OR substr(inherited_language,31) in (' . $db->Quote($language) . ',' . $db->Quote('*') . ')' . ')');
					case 'always':
					default:
						$query->where('a.language in (' . $db->Quote($language) . ',' . $db->Quote('*') . ',' . $db->Quote('') . ')');
					break;
				}
			}
			elseif ($name == 'categories') {
				switch ($this->params->get('manage_language')) {
					case 'no':
						$query->where('c.language in (' . $db->Quote($language) . ',' . $db->Quote('*') . ')');
					break;
					default:
						$query->where('c.language in (' . $db->Quote($language) . ',' . $db->Quote('*') . ',' . $db->Quote('') . ')');
					break;
				}
			}
			elseif ($name == 'menus') {
				switch ($this->params->get('manage_language')) {
					case 'no':
						$query->where('(m.language in (' . $db->Quote($language) . ',' . $db->Quote('*') . ') OR (m.home=1 AND m.language =' . $db->Quote('*') . '))');
					break;
					case 'inherited':
						// Filter by inherited language
						$query->select('m.language');
						$query->join('LEFT', '#__menu as p on p.lft <= m.lft AND p.rgt >=m.rgt AND p.language!=' . $db->Quote(''));
						$query->select('MIN(CONCAT(LPAD(p.rgt,30," "),p.language)) as inherited_language');
						$query->group('m.id');
						$query->having('(m.language in (' . $db->Quote($language) . ',' . $db->Quote('*') . ')' . ' OR substr(inherited_language,31) in (' . $db->Quote($language) . ',' . $db->Quote('*') . ')' . ' OR m.home=1)');
					case 'always':
					default:
						$query->where('(m.language in (' . $db->Quote($language) . ',' . $db->Quote('*') . ',' . $db->Quote('') . ') OR (m.home=1 AND m.language =' . $db->Quote('*') . '))');
					break;
				}
			}
			elseif ($name == 'modules') {
				switch ($this->params->get('manage_language')) {
					case 'always':
						$query->where('m.language in (' . $db->Quote($language) . ',' . $db->Quote('*') . ',' . $db->Quote('') . ')');
					break;
					default:
						$query->where('m.language in (' . $db->Quote($language) . ',' . $db->Quote('*') . ')');
					break;
				}
			}
		}
	}
}
