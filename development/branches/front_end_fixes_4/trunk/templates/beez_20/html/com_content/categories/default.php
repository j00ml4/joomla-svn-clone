<?php
/**
 * @version		$Id: default.php 12416 2009-07-03 08:49:14Z eddieajau $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers');

// If the page class is defined, wrap the whole output in a div.
$pageClass = $this->params->get('pageclass_sfx');
?>
<?php if ($pageClass) : ?>
<div class="<?php echo $pageClass;?>">
<?php endif;?>
<section>
<?php if ($this->params->get('show_page_title', 1)) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_title')); ?>
</h1>
<?php endif; ?>

<?php if (!empty($this->items)) : ?>
<ol>
	<?php foreach ($this->items as &$item) : ?>
	<li>
		<a href="<?php echo ContentRoute::category('index.php?option=com_content&view=category&id='.$this->escape($item->slug));?>">
			<?php echo $this->escape($item->title); ?></a>
	</li>
	<?php endforeach; ?>
</ol>
<?php endif; ?>

<?php if ($pageClass) : ?>
</div>
<?php endif;?>

</section>
