<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
?>

<form action="<?php echo JRoute::_('index.php?option=com_social&view=comments');?>" method="post" name="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSearch_Filter_Label'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::_('SOCIAL_Search_in_name'); ?>" />
			<button type="submit"><?php echo JText::_('JSearch_Filter_Submit'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSearch_Filter_Clear'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<select name="filter_state" id="published" class="inputbox" onchange="this.form.submit()">
				<?php echo JHtml::_('comments.commentStateOptions', $this->state->get('filter.state')); ?>
			</select>

			<select name="filter_context" id="context" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('SOCIAL_ALL_CONTEXTS'); ?></option>
				<?php echo JHtml::_('comments.commentContextOptions', $this->state->get('filter.context')); ?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="15%">
					<?php echo JHtml::_('grid.sort', 'SOCIAL_DATE', 'a.created_time', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
					,
					<?php echo JHtml::_('grid.sort', 'SOCIAL_AUTHOR', 'a.name', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th width="15%">
					<?php echo JHtml::_('grid.sort', 'SOCIAL_EMAIL', 'a.email', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
					,
					<?php echo JHtml::_('grid.sort', 'SOCIAL_URL', 'a.url', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
					,
					<?php echo JHtml::_('grid.sort', 'SOCIAL_IP_ADDRESS', 'a.address', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<th align="center">
					<?php echo JHtml::_('grid.sort', 'SOCIAL_SUBJECT', 'a.subject', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
					,
					<?php echo JText::_('SOCIAL_BODY'); ?>
				</th>
				<th nowrap="nowrap" width="12%">
					<?php echo JText::_('SOCIAL_ACTION'); ?>
				</th>
				<th width="1%" nowrap="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td align="center">
					<a href="<?php echo JRoute::_('index.php?option=com_social&view=comment&id='.$item->id); ?>">
						<?php echo JText::sprintf('SOCIAL_SUBMITTED_AUTHOR_DATE', $item->name, JHTML::_('date',$item->created_date, JText::_('DATE_FORMAT_LC2'))); ?></a>
				</td>
				<td valign="top">
					<ul class="comment-author-data">
						<li class="email" title="<?php echo JText::_('SOCIAL_EMAIL'); ?>">
							<?php echo ($item->email) ? $item->email : '- N/A -'; ?></li>
						<li class="url" title="<?php echo JText::_('SOCIAL_WEBSITE_URL'); ?>">
							<?php echo ($item->url) ? $item->url : JText::_('SOCIAL_NOT_AVAILABLE'); ?></li>
						<li class="ip" title="<?php echo JText::_('SOCIAL_IP_ADDRESS'); ?>">
							<?php echo ($item->address) ? long2ip($item->address) : JText::_('SOCIAL_NOT_AVAILABLE'); ?> <a href="index.php?option=com_social&amp;task=config.block&amp;block=address&amp;cid[]=<?php echo $item->id;?>">[ <?php echo JText::_('SOCIAL_BLOCK');?> ]</a></li>
					</ul>
				</td>
				<td valign="top">
					<ul class="comment-data">
						<li class="thread">
							<a href="<?php echo $this->getContentRoute($item->page_route); ?>" target="_blank">
								<?php
								$subject = JText::sprintf('SOCIAL_RE', !empty($item->subject) ? $item->subject : $item->page_title);
								$subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
								echo $subject;
								?></a>
						</li>
						<li class="body">
							<?php echo $this->escape(JHtml::_('string.truncate', $item->body, 430)); ?>
						</li>
					</ul>
				</td>
				<td>
					<?php echo JHtml::_('commentmoderation.action', $item); ?>
				</td>
				<td>
					<?php echo (int) $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->state->get('list.ordering'); ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('list.direction'); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
