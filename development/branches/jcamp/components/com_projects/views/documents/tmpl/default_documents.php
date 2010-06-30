<?php
/**
 * @version		$Id: default_articles.php 17873 2010-06-25 18:24:21Z 3dentech $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

//JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
JHtml::_('behavior.tooltip');
JHtml::core();
require_once 'components'.DS.'com_content'.DS.'helpers'.DS.'route.php';

$n = count($this->items);
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

$params = $this->params;
?>

<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" name="adminForm">
<?php echo $this->loadTemplate('buttons');?>
<?php if (empty($this->items)) : ?>
	<p><?php echo JText::_('COM_PROJECTS_DOCUMENTS_NO_DOCUMENT'); ?></p>
<?php endif ?>

		<?php if ($params->get('show_pagination_limit')) : ?>
		<div class="display-limit">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
		<?php endif; ?>

	<table class="category" border="1">
		<thead>
			<tr>
				<?php if($this->canDo->get('project.edit')) :?>
				<th>&nbsp;</th>
				<?php endif; ?>				

				<th class="list-title" id="tableOrdering">
					<?php  echo JHTML::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder) ; ?>
				</th>

				<th class="list-date" id="tableOrdering2">
					<?php echo JHTML::_('grid.sort', 'COM_PROJECTS_DOCUMENTS_CREATED_DATE', 'a.created', $listDirn, $listOrder); ?>
				</th>

				<th class="list-author" id="tableOrdering3">
					<?php echo JHTML::_('grid.sort', 'JAUTHOR', 'author', $listDirn, $listOrder); ?>
				</th>
				<?php if($this->canDo->get('project.edit')) :?>
				<th>
					<?php echo JText::_('COM_PROJECTS_DOCUMENTS_TOOLS_HEADER'); ?>
				</th>
				<?php endif; ?>				
			</tr>
		</thead>
		<tbody>

			<?php foreach ($this->items as $i => $this->article) : ?>
			<tr class="cat-list-row<?php echo $i % 2; ?> <?php echo $this->article->state > 0 ? '' : "artUnpublished";?>">

				<?php if (in_array($this->article->access, $this->user->authorisedLevels())) : ?>
					<?php echo $this->loadTemplate('item_access');?>
				<?php else : ?>
					<?php echo $this->loadTemplate('item_noaccess');?>
				<?php endif; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if(($params->def('show_pagination', 1) == 1 || ($params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) { ?>
	<div class="pagination">
		<?php  if ($this->params->def('show_pagination_results', 1)) { ?>
		<p class="counter">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</p>
	<?php } ?>
	<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php  } ?>
	<?php echo $this->loadTemplate('buttons');?>
	
		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="limitstart" value="" />
		<input type="hidden" name="task" value="" />
</form>