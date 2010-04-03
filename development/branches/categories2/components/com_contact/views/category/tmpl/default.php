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

$pageClass = $this->params->get('pageclass_sfx');
?>
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>

<div class="newsfeed-category<?php echo $pageClass;?>">
<?php if($this->params->get('show_category_title', 1) && $this->params->get('page_subheading')) : ?>
<h2>
	<?php echo $this->escape($this->params->get('page_subheading')); ?>
</h2>
<?php endif; ?>
	<?php if ($this->params->def('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
		<div class="category_desc">
			<!-- @TODO Verify image path defaults/overrides/positions + category_params breaks display-->
			<?php if ($this->params->get('show_description_image') 
			&& $this->category->getParams()->get('image')) : ?>
				<img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
			<?php endif; ?>
			<?php if ($this->params->get('show_description') && $this->category->description) : ?>
				<?php echo $this->category->description; ?>
			<?php endif; ?>
			<div class="clr"></div>
		</div>
	<?php endif; ?>

<?php echo $this->loadTemplate('items'); ?>

<!-- <div class="cat-siblings">  -->
<?php  echo $this->loadTemplate('siblings');  ?>
<!-- </div>  -->

<!--   -->
<?php if (!empty($this->children)) : ?>
<div class="cat-children">
	<h5>Sub Categories</h5>
	<?php echo $this->loadTemplate('children'); ?>
</div>	
<?php endif; ?>

<!--  <div class="cat-parents"> -->
<?php  echo $this->loadTemplate('parents');  ?>
<!--  </div> -->

</div>
