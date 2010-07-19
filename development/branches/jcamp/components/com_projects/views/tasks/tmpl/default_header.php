<?php
/**
 * @version		$Id:
 * @package		Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$listOrder	= $this->getModel()->getState('list.ordering');
$listDir	= $this->getModel()->getState('list.direction');
?>
<tr>
	<th width="1%">
		<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDir, $listOrder); ?>
	</th>
	<th width="5%">
		<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.published', $listDir, $listOrder); ?>
	</th>
	<th width="10%">
		<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ACCESS', 'access_level', $listDir, $listOrder); ?>
	</th>
	<th width="5%" class="nowrap">
		<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDir, $listOrder); ?>
	</th>
	<th width="1%" class="nowrap">
		<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'a.id', $listDir, $listOrder); ?>
	</th>
</tr>
