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
<div class="projects<?php echo $pageClass;?>">
	<div class="item-page">
		<div class="item">
			<h1><?php echo $this->title;?></h1>
			<?php if($model->getState('type') != 'list') :?>
				<form action="<?php echo JRoute::_('index.php?option=com_projects&id='.$model->getState('project.id')); ?>" method="post">
			<?php endif; ?>
	
			<?php echo $this->loadTemplate('buttons');?>
			
			<table class="category" border="1">
			<thead>
				<tr>		
					<?php if($model->getState('type') != 'list'): ?>
						<th><input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" /></th>
					<?php endif;?>
					
					<th id="tableOrdering2">
						<?php echo JHTML::_('grid.sort', 'COM_PROJECTS_MEMBERS_NAME', 'a.name', $listDirn, $listOrder); ?>
					</th>	
					
					<th id="tableOrdering">
						<?php  echo JHTML::_('grid.sort', 'COM_PROJECTS_MEMBERS_ROLE', 'a.role', $listDirn, $listOrder) ; ?>
					</th>		
				</tr>
			</thead>
			<tbody>		
				<?php foreach($this->items as $item):
					$this->item = &$item;
					echo $this->loadTemplate('item');
					?>
<!-- This part should be put into a separate default_item.php file :)
					<tr>

						<?php if($model->getState('type') != 'list'): ?>
						<td>
							<input type="checkbox" value="<?php echo $item->id;?>" name="usr[]" id="user-<?php echo $item->id;?>"/>
						</td>
						<?php endif; ?>
						
						<td>
							<label for="user-<?php echo $item->id;?>"><?php echo $item->name;?></label>
						</td>
						<td>
							Role
						</td>
 					</tr>	
 -->
				<?php endforeach; ?>
			</tbody>
			</table>
			<?php echo $this->loadTemplate('buttons');?>
			
			<?php if($model->getState('type') != 'list') :?>
				<input type="hidden" name="task" value="" />
				<?php echo JHTML::_( 'form.token' ); ?>
				<input type="hidden" name="boxchecked" value="0" />
				</form>
			<?php endif; ?>
		</div>
		
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
	</div>	

</div>