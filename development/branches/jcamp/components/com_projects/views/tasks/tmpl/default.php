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
JHtml::_('behavior.tooltip');
?>
<div class="projects<?php echo $params->get('pageclass_sfx'); ?>">
	<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" name="adminForm">
	<?php echo $this->loadTemplate('buttons'); ?>	
	<table class="adminlist">
		<thead>
			<?php echo $this->loadTemplate('header');?>
		</thead>
		<tbody>
			<?php
				$c = count($this->items);
				for ($i = 0; $i <$c;$i++) {
					$this->item = &$this->items[$i];
					$this->item->i = $i;
					echo $this->loadTemplate('item');
//					$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out==$user->get('id');
//					$canChange = $canCheckin;
					?>
				<tr class="row<?php echo $i % 2; ?>">
				</tr>
			<?php } ?>
		</tbody>
	</table>
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

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->getModel()->getState('list.ordering'); ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->getModel()->getState('list.direction'); ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	</form>
</div>