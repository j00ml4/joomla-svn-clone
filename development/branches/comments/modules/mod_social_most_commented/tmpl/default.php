<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_most_commented
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');

// If there are no items in the list, do not display anything.
if (empty($list)) {
	return;
}

// Attach the comments stylesheet to the document head.
JHtml::stylesheet('social/comments.css', array(), true);
?>

<ol class="comments-list most-commented">
<?php foreach($list as $item) : ?>
	<li>
		<h4>
			<a class="item" href="<?php echo JRoute::_($item->page_route); ?>" title="<?php echo htmlspecialchars($item->page_title, ENT_QUOTES, 'UTF-8'); ?>">
				<?php echo htmlspecialchars($item->page_title, ENT_QUOTES, 'UTF-8'); ?></a>
		</h4>
<?php if ($params->get('show_comment_number', 1)) : ?>
		<small>
<?php
	if ($item->comment_count == 1) {
		echo JText::sprintf('Comment_Num', (int)$item->comment_count);
	} else {
		echo JText::sprintf('Comments_Num', (int)$item->comment_count);
	}
?>
		</small>
<?php endif; ?>
	</li>
<?php endforeach; ?>
</ol>