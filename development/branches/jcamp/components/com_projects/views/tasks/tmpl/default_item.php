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
	<?php if ($this->item->checked_out) : ?>
		<?php echo JHtml::_('jgrid.checkedout', $this->item->i, $this->item->editor, $this->item->checked_out_time, 'categories.', $canCheckin); ?>
	<?php endif; ?>
	<a href="<?php echo JRoute::_('index.php?option=com_projects&view=task&layout=edit&id='.$this->item->id);?>">
		<?php echo $this->escape($this->item->title); ?></a>
	<p class="smallsub" title="<?php echo $this->escape($this->item->path);?>">
		<?php if (empty($this->item->note)) : ?>
			<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($this->item->alias));?>
		<?php else : ?>
			<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS_NOTE', $this->escape($this->item->alias), $this->escape($this->item->note));?>
		<?php endif; ?></p>
</td>
<td class="center">
	<?php echo JHtml::_('jgrid.published', $this->item->state, $this->item->i);?>
</td>
<td class="center">
	<?php echo $this->escape($this->item->access_level); ?>
</td>
<td class="center nowrap">
<?php if ($this->item->language=='*'):?>
	<?php echo JText::_('JALL'); ?>
<?php else:?>
	<?php echo $this->item->language_title ? $this->escape($this->item->language_title) : JText::_('JUNDEFINED'); ?>
<?php endif;?>
</td>
<td class="center">
	<span title="<?php echo sprintf('%d-%d', $this->item->lft, $this->item->rgt);?>">
		<?php echo (int) $this->item->id; ?></span>
</td>