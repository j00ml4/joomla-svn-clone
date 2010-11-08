<?php
/**
 * @version		
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;?>
<?php 
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
JHtml::_('behavior.tooltip');
JHtml::core();
?>
<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</div>
<?php endif; ?>
<table class="blog<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" cellpadding="0" cellspacing="0">
<?php  ?>	
	<?php ?>
<?php if (!empty($this->lead_items)) : ?>
	<?php ?>
		<tr>
			<td valign="top">
			<?php // for ($i = $this->pagination->limitstart; $i < ($this->pagination->limitstart + $this->params->get('num_leading_articles')); $i++) : ?>
				<?php $leadingcount=0 ; ?>

				<?php if (!empty($this->lead_items)) : ?>
					<?php foreach ($this->lead_items as &$item) : ?>
						<div>
						<?php
							$this->item = &$item;
							echo $this->loadTemplate('item');
						?>
						</div>
						<?php
							$leadingcount++;
						?>
					<?php endforeach; ?>

					</td>
				</tr>		
			<?php else : $i = $this->pagination->limitstart; ?>
			<?php endif; ?>
<?php endif; ?>

	<?php
	$startIntroArticles = $this->pagination->limitstart + $this->params->get('num_leading_articles');
	
		$introcount=(count($this->intro_items));
		$counter=0;
	?>
	<?php if (!empty($this->intro_items)) : ?>
		<?php foreach ($this->intro_items as $key => &$item) : ?>
			<?php
				$key= ($key-$leadingcount)+1;
				$rowcount=( ((int)$key-1) %	(int) $this->columns) +1;
				$row = $counter / $this->columns ;
		
				if ($rowcount==1) : ?>
					<div></div>
						<?php $counter++; ?>
						<?php if (($rowcount == $this->columns) or ($counter ==$introcount)): ?>
							<span class="row-separator"></span>
							
			
						<?php endif; ?>
					<table>
						<tr>
							<td valign="top">
								<table width="100%"  cellpadding="0" cellspacing="0">
								<tr>
								<?php
									$divider = '';?>
							</td>
						</tr>
					</table>
				<?php endif; ?>	
				
		
		<?php endforeach; ?>
	<?php endif; ?>
	<?php if ($this->params->def('num_links', 4) && ($i < $this->total)) : ?>
			<tr>
				<td valign="top">
					<div class="blog_more<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
						<?php
							$this->links = array_splice($this->items, $i - $this->pagination->limitstart);
				//			echo $this->loadTemplate('links');
						?>
					</div>
				</td>
			</tr>
	<?php endif; ?>
	
	<?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->get('pages.total') > 1)) : ?>
		<tr>
		<td valign="top" align="center">
			<?php echo $this->pagination->getPagesLinks(); ?>
			<br /><br />
		</td>
		</tr>
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<tr>
				<td valign="top" align="center">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</td>
			</tr>
		<?php endif; ?>
	<?php endif; ?>
</table>
