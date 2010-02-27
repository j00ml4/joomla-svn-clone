<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	com_newsfeeds
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::core();

$n = count($this->items);
?>

<?php if (empty($this->items)) : ?>
	<p> <?php echo JText::_('Newsfeed_No_Articles'); ?>	 </p>
<?php else : ?>

<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" name="adminForm">
	<fieldset class="filters">
	<legend class="element-invisible"><?php echo JText::_('JContent_Filter_Label'); ?></legend>
	<?php if ($this->params->get('show_pagination_limit')) : ?>
		<div class="display-limit">
			<?php echo JText::_('Display_Num'); ?>&nbsp;
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
	<?php endif; ?>
	</fieldset>

	<table class="category">
		<?php if ($this->params->get('show_headings')) : ?>
		<thead><tr>
				<?php if ($this->params->get('show_name')) : ?>
				<th class="item-title" id="tableOrdering">
					<?php echo JHtml::_('grid.sort',  JText::_('Feed_Name'), 'a.name', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<?php endif; ?>
				
				<?php if ($this->params->get('show_articles')) : ?>
				<th class="item-num-art" id="tableOrdering2">
					<?php echo JHtml::_('grid.sort',  JText::_('Num_Articles'), 'a.numarticles', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<?php endif; ?>
				
				<?php if ($this->params->get('show_link')) : ?>
				<th class="item-link" id="tableOrdering3">
					<?php echo JHtml::_('grid.sort',  JText::_('Feed_Link'), 'a.link', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
				</th>
				<?php endif; ?>
				
			</tr>
		</thead>
		<?php endif; ?>

		<tbody>
			<?php foreach ($this->items as $i => $item) : ?>
				<tr class="<?php echo $i % 2 ? 'odd' : 'even';?>">

					<td class="item-title">
						<a href="<?php echo JRoute::_(NewsfeedsHelperRoute::getNewsfeedRoute($item->slug, $item->catid)); ?>">
							<?php echo $item->name; ?></a>
					</td>

					<?php  if ($this->params->get('show_articles')) : ?>
						<td class="item-num-art">
							<?php echo $item->numarticles; ?>
						</td>
					<?php  endif; ?>
					
					<?php  if ($this->params->get('show_link')) : ?>
						<td class="item-link">
							<a href="<?php echo $item->link; ?>"><?php echo $item->link; ?></a>
						</td>
					<?php  endif; ?>

				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if ($this->params->get('show_pagination')) : ?>
	 <div class="pagination">
	<?php if ($this->params->def('show_pagination_results', 1)) : ?>
						<p class="counter">
							<?php echo $this->pagination->getPagesCounter(); ?>
						</p>
   <?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	<?php endif; ?>
	
		<input type="hidden" name="filter_order" value="<?php echo $this->state->get('list.ordering'); ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('list.direction'); ?>" />
</form>
<?php endif; ?>