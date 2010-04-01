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
?>
<?php if (!$this->category->getSibling() && !$this->category->getSibling(false)) : ?>
	no siblings
<?php else : ?>
	<h5>Siblings</h5>
	<ul>
		<?php if($leftSibling = $this->category->getSibling(false)) : ?>
			<li>
			<a href="<?php echo JRoute::_(ContactHelperRoute::getCategoryRoute($leftSibling->slug)); ?>">
				<?php echo $this->escape($leftSibling->title); ?></a>
			</li>
		<?php endif; ?>
		<?php if($rightSibling = $this->category->getSibling()) : ?>
			<li>
			<a href="<?php echo JRoute::_(ContactHelperRoute::getCategoryRoute($rightSibling->slug)); ?>">
				<?php echo $this->escape($rightSibling->title); ?></a>
			</li>
		<?php endif; ?>
	</ul>
<?php endif; ?>
