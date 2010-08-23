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
$canCheckin = $canChange = true;
?>
<td class="center">
	<?php echo JHtml::_('grid.id', $this->item->i, $this->item->id); ?>
</td>
<td class="indent-<?php echo intval(($this->item->level-1)*15)+4; ?>">
	<?php if ($this->item->checked_out) :
		echo JHtml::_('jgrid.checkedout', $this->item->i, $this->item->editor, $this->item->checked_out_time, 'categories.', $canCheckin);
	endif; ?>
	<a href="<?php echo ProjectsHelper::getLink('task.edit', $this->item->id);?>">
		<?php echo $this->escape($this->item->title); ?></a>
  <a href="<?php echo ProjectsHelper::getLink('task.view', $this->item->id);?>">
  	<?php echo JText::_('COM_PROJECTS_TASKS_VIEW_'.$this->prefix.'_LINK');?></a>		
	<p class="smallsub">
		<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($this->item->alias)); ?></p>
</td>
<td class="center">
	<?php echo $this->escape($this->item->category); ?>
</td>
<td class="center">
	<?php echo $this->escape($this->item->access_level); ?>
</td>
<td class="center">
	<?php echo $this->escape($this->item->created_by); ?>
</td>
<td class="center nowrap">
<?php if ($this->item->language=='*'):
	echo JText::_('JALL');
	else:
		echo $this->item->language_title ? $this->escape($this->item->language_title) : JText::_('JUNDEFINED');
	endif;?>
</td>
<td class="center">
	<span title="<?php echo sprintf('%d-%d', $this->item->lft, $this->item->rgt);?>">
		<?php echo (int) $this->item->id; ?></span>
</td>