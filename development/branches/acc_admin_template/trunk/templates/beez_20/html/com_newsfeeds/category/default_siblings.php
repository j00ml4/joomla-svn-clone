<?php
/**
 * @version
 * @package		Joomla.Site
 * @subpackage	com_newsfeeds
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<?php if (empty($this->siblings)) : ?>
	<?php echo JText::_('NO SIBLINGS'); ?>
<?php else : ?>
	<h3><?php echo JText::_('NO SIBLINGS'); ?></h3>
	<ul>
		<?php foreach ($this->siblings as &$item) : ?>
		<li>
			<?php if ($item->id != $this->item->id) : ?>
			<a href="<?php echo JRoute::_(NewsfeedsRoute::category($item->slug)); ?>">
				<?php echo $this->escape($item->title); ?></a>
			<?php else : ?>
				<?php echo $this->escape($item->title); ?>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>

<?php endif; ?>
