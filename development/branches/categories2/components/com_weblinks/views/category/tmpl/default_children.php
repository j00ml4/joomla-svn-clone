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
$class = ' class="first"';
if(count($this->children[$this->category->id])) : ?>

	<ul>
	<?php foreach($this->children[$this->category->id] as $id => $child) : ?>

		<?php
		$maxlevel = $this->params->get('max_levels',0);
		if ($maxlevel > 0 && $child->level > $maxlevel)
		{
			continue;
		}
		if(!isset($this->children[$this->category->id][$id + 1]))
		{
			$class = ' class="last"';
		}
		?>
		<li<?php echo $class; ?>>

		<?php $class = ''; ?>
			<span class="jitem-title"><a href="<?php echo JRoute::_(WeblinksHelperRoute::getCategoryRoute($child->id));?>">
				<?php echo $this->escape($child->title); ?></a>
			</span>
			<?php if ($child->description) : ?>
				<div class="category-desc">
					<?php echo JHtml::_('content.prepare', $child->description); ?>
				</div>
			<?php endif; ?>
			<?php if(count($child->getChildren()) > 0 ) :
				$this->children[$child->id] = $child->getChildren();
				$this->category = $child;
				echo $this->loadTemplate('children');
				$this->category = $child->getParent();
			endif; ?>
		</li>
	<?php endforeach; ?>
	</ul>
<?php endif;