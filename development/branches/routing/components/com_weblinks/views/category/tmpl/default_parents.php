<?php
/**
 * @version		$Id: default_parents.php 12416 2009-07-03 08:49:14Z eddieajau $
 * @package		Joomla.Site
 * @subpackage	com_weblinks
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<?php if (empty($this->parent)) : ?>
<p><?php  echo JText::_('JContent_No_Parents'); ?></p>
<?php else : ?>
	<h3><?php  echo JText::_('JContent_Parents'); ?></h3>
	<ul>
		<?php while ($this->parent->getParent()) : ?>
		<li>
			<a href="<?php echo JRoute::_(WeblinksHelperRoute::getCategoryRoute($this->parent->slug)); ?>">
				<?php echo $this->escape($this->parent->title); ?></a>
		</li>
		<?php $this->parent = $this->parent->getParent(); ?>
		<?php endwhile; ?>
	</ul>

<?php endif; ?>
