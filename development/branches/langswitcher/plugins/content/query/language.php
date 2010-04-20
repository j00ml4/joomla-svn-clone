<?php
/**
 * @version		$Id: loadmodule.php 16209 2010-04-19 03:47:23Z chdemko $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgContentLanguage extends JPlugin
{  
	public function onPrepareQuery(&$query)
	{
		$args = func_get_args();
		$alias = $args[2];
		$inherit_field = $args[3];
		$inherit_table = $args[4];
		$db = JFactory::getDBO();
		$language = JSite::getLanguage();
		$query->where('('.$alias.'.language='.$db->Quote($language).' OR '.$alias.'.language='.$db->Quote('').')');
		
		// Filter by inherited language
		if ($this->params->get('use_inherited_language',0)) {
			$query->select("$alias.language");
			$query->join('LEFT', $db->nameQuote($inherit_table) ' as p on p.lft <= c.lft AND p.rgt >=c.rgt AND p.language!=\'\'');
			$query->select('MIN(CONCAT(LPAD(p.rgt,30," "),p.language)) as inherited_language');
			$query->group('a.id');
			$query->having('(a.language='.$db->Quote($language) . ($this->params->get('show_untagged_content',1) ? ' OR inherited_language IS NULL':'') . ' OR substr(inherited_language,31)='.$db->Quote($language).')');
		}
	}
}
