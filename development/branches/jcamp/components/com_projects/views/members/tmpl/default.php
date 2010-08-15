<?php
/**
 * @version     $Id$
 * @package     Joomla.Site
 * @subpackage	com_projects
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Vars
$params =  $this->params;
$pageClass = $this->escape($params->get('pageclass_sfx'));
$model = $this->getModel();
?>
<div class="projects <?php echo $pageClass;?>">
<div class="category-list">
 	<div>
 	<?php if($this->canDo->get('core.edit')){
 		echo $this->loadTemplate('buttons');
 	} ?> 
 	</div>
	<div class="cat-items">		
		<form action="<?php echo JFactory::getURI()->toString(); ?>" method="post" name="adminForm">
			<?php if ($this->params->get('show_filters')) :?>
			<fieldset class="filters">
				<legend class="hidelabeltxt">
					<?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?>
				</legend>
		
				<div class="filter-search">
					<label class="filter-search-lbl" for="filter-search"><?php echo JText::_('COM_CONTENT_'.$this->params->get('filter_field').'_FILTER_LABEL').'&#160;'; ?></label>
					<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
				</div>
			<?php endif; ?>
		
				<?php if ($this->params->get('show_pagination_limit')) : ?>
				<div class="display-limit">
					<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
				<?php endif; ?>
		
			<?php if ($this->params->get('filter_field') != 'hide') :?>
			</fieldset>
		<?php endif; ?>			
		<?php if(count($this->items)): ?>		
			<table class="category members" border="1">
				<thead>
					<tr>		
						<?php if($this->canDo->get('core.edit')): ?>	
						<th>
							<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
						</th>
						<?php endif;?>
						
						<th id="tableOrdering2">
							<?php echo JHTML::_('grid.sort', 'COM_PROJECTS_MEMBER_NAME', 'u.name', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
						</th>	
						
						<th id="tableOrdering3">
							<?php echo JHTML::_('grid.sort', 'JGLOBAL_USERNAME', 'u.username', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
						</th>							
					</tr>
				</thead>
				<tbody>		
				<?php foreach($this->items as $i => $item): ?>
					<tr>
						<?php if($this->canDo->get('core.edit')): ?>	
						<td>
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<?php endif; ?>
						
						<td>
							<label for="user-<?php echo $item->id;?>">
								<?php echo $item->name;?> 
							</label>
						</td>	
						
						<td>
							<?php echo $item->username; ?>
						</td>	
					 </tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>	
			<p><?php echo JText::_('COM_PROJECTS_NO_MEMBERS'); ?></p>
		<?php endif; ?>
			
			<input type="hidden" name="task" value="" />
			<?php echo JHTML::_( 'form.token' ); ?>
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="" />
			<input type="hidden" name="filter_order_Dir" value="" />
			<input type="hidden" name="limitstart" value="" />
		</form>
	</div>
		
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
</div>	
</div>