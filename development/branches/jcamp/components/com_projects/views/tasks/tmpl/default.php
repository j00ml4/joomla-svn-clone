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

// Vars
$params =  $this->params;
?>
<div class="projects<?php echo $params->get('pageclass_sfx'); ?>">
    <div class="tasks-list">
		<form action="<?php echo ProjectsHelper::getLink('form'); ?>" method="post" id="adminForm" name="adminForm">	
	        <?php 
	        if( 
	        	($this->canDo->get('task.view') || $params->get('show_tasks')) && 
	        	($this->canDo->get('ticket.view') || $params->get('show_tickets')) 
	        ){
				echo $this->loadTemplate('buttons');
	        }
			?>
	        <fieldset class="filters">
				<legend><?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?></legend>
		
				<div class="filter-search">
					<p>
						<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
						<input type="text" class="" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::_('COM_CATEGORIES_ITEMS_SEARCH_FILTER'); ?>" />
						
						<?php echo JHtml::_('filter.catid', '.task', $this->state->get('filter.catid')); ?>
						
						<?php echo JHtml::_('filter.state', $this->type, $this->state->get('filter.state')); ?>
						
						<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
					</p>
				</div>
			</fieldset>
			<?php if(empty($this->items)): ?>
	            <p><?php echo JText::_('COM_PROJECTS_TASKS_NO_'.$this->type.'S'); ?></p>
	       	 <?php else: ?>
	            <?php echo $this->loadTemplate($this->type); ?>  	
	            
	            <?php if ($this->params->get('show_pagination', 1) && ($this->pagination->get('pages.total') > 1)) : ?>
	            <div class="pagination">
	                    <?php  if ($this->params->get('show_pagination_results', 1)) : ?>
	                    <p class="counter">
	                    	<?php echo $this->pagination->getPagesCounter(); ?>
	                    </p>
	                    <?php endif; ?>
	                    <?php echo $this->pagination->getPagesLinks(); ?>
	            </div>
	            <?php  endif; ?>
			<?php endif; ?>	

            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="filter_order" value="<?php echo $this->state->get('list.ordering'); ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->get('list.direction'); ?>" />
            <input type="hidden" id="task" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
</div>