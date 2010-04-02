<?php

/**
 * @version		$Id: default_items.php 15048 2010-02-25 17:24:37Z hackwar $
 * @package		Joomla.Site
 * @subpackage	com_newsfeeds
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$class = ' class="first"';
if (count($this->items[$this->parent->id]) > 0)
	:
?>
<ul>
<?php foreach($this->items[$this->parent->id] as $id => $item) : ?>
	<?php
	if($this->params->get('show_empty_categories') || $item->numitems || count($item->getChildren())) :
	$maxlevel = $this->params->get('max_levels',0);
	if ($maxlevel > 0 && $item->level > $maxlevel)
	{
		continue;		
	} 
	if(!isset($this->items[$this->parent->id][$id + 1]))
	{
		$class = ' class="last"';
	}
	?>
	<li<?php echo $class; ?>>
	<?php $class = ''; ?>
		<span class="jitem-title"><a href="<?php echo JRoute::_(NewsfeedsHelperRoute::getCategoryRoute($item->id));?>">
			<?php echo $this->escape($item->title); ?></a>
		</span>
		<?php if ($item->description) : ?>
			<div class="category-desc">
				<?php echo JHtml::_('content.prepare', $item->description); ?>
			</div>
		<?php endif; ?>
		<?php if ($this->params->get('show_item_count') == 1) :?>
			<dl class="newsfeed-count"><dt>
				<?php echo JText::_('COM_NEWSFEED_COUNT:'); ?></dt>
				<dd><?php echo $item->numitems; ?></dd>
			</dl>
		<?php endif; ?>
		
		<?php if(count($item->getChildren()) > 0 && $this->params->get('show_children', 0)) :
			$this->items[$item->id] = $item->getChildren();
			$this->parent = $item;
			echo $this->loadTemplate('items');
			$this->parent = $item->getParent();
		endif; ?>

	</li>
	<?php endif; ?>
<?php endforeach; ?>
</ul>
<?php endif; ?>