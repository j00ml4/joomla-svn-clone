<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_comment
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');
?>
	<div class="metadata">
		<p class="author">
			<a href="<?php echo $item->url;?>" target="_blank" rel="nofollow"><?php echo $item->subject; ?></a>
			<?php echo JText::sprintf('Comments_Makes_This_Comment', JRoute::_('#comment-'.$item->id)); ?>
		</p>
		<p class="date">
			<?php echo JHtml::_('date', $item->created_date, $params->get('date_format')); ?>
		</p>
	</div>
	<div class="comment">
		[...] <?php echo $item->body; ?> [...]
	</div>
