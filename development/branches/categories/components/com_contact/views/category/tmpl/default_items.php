<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>

	<script language="javascript" type="text/javascript">
		function tableOrdering(order, dir, task) {
		var form = document.adminForm;

		form.filter_order.value	= order;
		form.filter_order_Dir.value	= dir;
		document.adminForm.submit(task);
	}
	</script>

	<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()); ?>" method="post" name="adminForm">
	 <div class="filter">
		<?php echo JText::_('COM_CONTACT_DISPLAY_NUM'); ?>
		<?php echo $this->pagination->getLimitBox(); ?>
	 </div>
	<input type="hidden" name="filter_order" value="<?php echo $this->state->get('list.ordering'); ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('list.direction'); ?>" />
 </form>

	<table class="jlist-table">
		<?php if ($this->params->get('show_headings')) : ?>
		<thead>
			<tr>
				<th class="item-num">
					<?php echo JText::_('Num'); ?>
				</th>

				<th class="item-title">
					<?php echo JHtml::_('grid.sort',  'Name', 'cd.name', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>

				<?php if ($this->params->get('show_position')) : ?>
					<th class="item-position">
						<?php echo JHtml::_('grid.sort',  'Position', 'cd.con_position', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
					</th>
				<?php endif; ?>

				<?php if ($this->params->get('show_email')) : ?>
					<th class="item-email">
						<?php echo JText::_('Email'); ?>
					</th>
				<?php endif; ?>

				<?php if ($this->params->get('show_telephone')) : ?>
					<th class="item-phone">
						<?php echo JText::_('Phone'); ?>
					</th>
				<?php endif; ?>

				<?php if ($this->params->get('show_mobile')) : ?>
					<th class="item-phone">
						<?php echo JText::_('Mobile'); ?>
					</th>
				<?php endif; ?>

				<?php if ($this->params->get('show_fax')) : ?>
					<th class="item-phone">
						<?php echo JText::_('Fax'); ?>
					</th>
				<?php endif; ?>

			</tr>
		</thead>
		<?php endif; ?>

		<tbody>
			<?php foreach ($this->items as $i => $item) : ?>
				<tr class="<?php echo $i % 2 ? 'odd' : 'even';?>">
					<td class="item-num">
						<?php echo $i +1; ?>
					</td>

					<td class="item-title">
						<a href="<?php echo $item->link; ?>">
							<?php echo $item->name; ?></a>
					</td>

					<?php if ($this->params->get('show_position')) : ?>
						<td class="item-position">
							<?php echo $item->con_position; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_email')) : ?>
						<td class="item-email">
							<?php echo $item->email_to; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_telephone')) : ?>
						<td class="item-phone">
							<?php echo $item->telephone; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_mobile')) : ?>
						<td class="item-phone">
							<?php echo $item->mobile; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_fax')) : ?>
					<td class="item-phone">
						<?php echo $item->fax; ?>
					</td>
					<?php endif; ?>

				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div class="jpagination">
		<?php echo $this->pagination->getPagesLinks(); ?>
		<div class="jpag-results">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</div>
	</div>