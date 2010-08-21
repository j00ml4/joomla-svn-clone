<?php
/**
 * @version		$Id: default_articles.php 18212 2010-07-22 06:02:54Z eddieajau $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
JHtml::_('behavior.tooltip');
JHtml::core();

$n = count($this->items);
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>
<?php if (empty($this->items)) : ?>
	<p><?php echo JText::_('COM_PROJECTS_NO_DOCUMENTS'); ?></p>
<?php else : ?>
	<?php if ($this->params->get('filter_field') != 'hide') :?>
	<fieldset class="filters">
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter-search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('JSEARCH_FILTER_DESC'); ?>" />
		</div>

		<?php if ($this->params->get('show_pagination_limit', 1)) : ?>
		<div class="display-limit">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
		<?php endif; ?>
	</fieldset>
	<?php endif; ?>

	<table class="category" border="1">
		<?php if ($this->params->get('show_headings', 1)) :?>
		<thead>
			<tr>
				<?php if($this->canDo->get('document.edit') || $this->canDo->get('document.delete')): ?>	
				<th>
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>
				<?php endif;?>
				
				<th class="list-title" id="tableOrdering">
					<?php  echo JHTML::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder) ; ?>
				</th>

				<th class="list-category" id="tableOrdering">
					<?php  echo JHTML::_('grid.sort', 'JCATEGORY', 'category_title', $listDirn, $listOrder) ; ?>
				</th>
				
				<th class="list-author" id="tableOrdering3">
					<?php echo JHTML::_('grid.sort', 'JAUTHOR', 'author', $listDirn, $listOrder); ?>
				</th>
				
				<th class="list-date" id="tableOrdering2">
					<?php echo JHTML::_('grid.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<?php endif; ?>

		<tbody>
			<?php foreach ($this->items as $i => &$item) : ?>
			<tr class="cat-list-row<?php echo $i % 2; ?>">
				<?php if($this->canDo->get('core.edit')): ?>	
				<td>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<?php endif; ?>
					
				<td class="list-title">
					<a href="<?php echo ProjectsHelper::getLink('document', $item->id); ?>">
					<?php echo $item->title; ?></a>
				</td>
				
				<td class="list-category"> 
					<?php echo $item->category_title; ?>
				</td>

				<td class="list-author">
					<?php echo $this->params->get('link_author', 0) ? 
						JHTML::_('link',JRoute::_('index.php?option=com_users&view=profile&member_id='.$item->created_by),$item->author) :
						$item->author; ?>
				</td>
				
				<td class="list-date">
					<?php echo JHTML::_('date',
					$item->modified, 
					$this->escape($this->params->get('date_format', JText::_('DATE_FORMAT_LC3')))); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
	<div class="pagination">

		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
		 	<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php endif; ?>

		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php endif; ?>

<?php endif; ?>