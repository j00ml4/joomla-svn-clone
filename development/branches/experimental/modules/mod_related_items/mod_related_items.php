<?php
/**
 * @version		$Id$
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).DS.'helper.php';

$list = modRelatedItemsHelper::getList($params);

if (!count($list)) {
	return;
}

$showDate = $params->get('showDate', 0);

require(JModuleHelper::getLayoutPath('mod_related_items'));
