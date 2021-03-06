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

JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers');

$pageClass = $this->params->get('pageclass_sfx');
?>

<div class="category-list <?php echo $pageClass;?>">
	<?php if ($this->params->get('show_page_title', 1)) : ?>
		<h1>
			<?php if ($this->escape($this->params->get('page_heading'))) :?>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			<?php else : ?>
				<?php echo $this->escape($this->params->get('page_title')); ?>
			<?php endif; ?>
		</h1>
	<?php endif; ?>
	<h2>
		<?php echo $this->escape($this->item->title); ?>
	</h2>
	<?php if ($this->params->def('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
		<div class="category-desc">
			<!-- @TODO Verify image path defaults/overrides/positions -->
			<?php if ($this->params->get('show_description_image') && $this->item->category_params->get('image')) : ?>
				<img src="<?php echo $this->item->category_params->get('image'); ?>" />
			<?php endif; ?>
			<?php if ($this->params->get('show_description') && $this->item->description) : ?>
				<?php echo $this->item->description; ?>
			<?php endif; ?>
			<div class="clr"></div>
		</div>
	<?php endif; ?>
	
	<?php if (is_array($this->children) && count($this->children) > 0) : ?>
		<div class="jcat-children">
			<?php echo $this->loadTemplate('children'); ?>
		</div>
	<?php endif; ?>
	
	<div class="cat-items">
		<?php echo $this->loadTemplate('articles'); ?>
	</div>

</div>
