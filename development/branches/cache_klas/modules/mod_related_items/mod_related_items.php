<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_related_items
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @copyright	Copyright (C) 2010 Klas Berlič
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).DS.'helper.php';

//$list = modRelatedItemsHelper::getList($params);
$list = JModuleHelper::cache('mod_related_items','modRelatedItemsHelper',array('getList',$params),$params,'safeuri',array('id'=>'int','Itemid'=>'int'));
if (!count($list)) {
	return;
}

$showDate = $params->get('showDate', 0);

require JModuleHelper::getLayoutPath('mod_related_items', $params->get('layout', 'default'));
