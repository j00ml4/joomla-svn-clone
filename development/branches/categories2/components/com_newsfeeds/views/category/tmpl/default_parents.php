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
<?php if (true) : //$this->parent->id != 'root') : ?>
	no parent
<?php else : ?>
	<h5>Parent</h5>
	<ul>
		<li>
			<a href="<?php echo JRoute::_(NewsfeedsHelperRoute::getCategoryRoute($this->parent->slug)); ?>">
				<?php echo $this->escape($this->parent->title); ?></a>
		</li>
	</ul>
<?php endif; ?>
