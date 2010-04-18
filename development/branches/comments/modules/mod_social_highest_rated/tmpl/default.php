<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_highest_rated
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

// Handle custom star files.
if ($params->get('star_file')) {
	$document->addStyleDeclaration(
		'	ol.highest-rated div.item-rating,ol.highest-rated div.item-rating span.current-rating{background-image: url('.JURI::base().'components/com_social/media/images/'.$params->get('star_file', 'star').'-small.png)}'
	);
}
?>

<ol class="comments-list highest-rated">
<?php foreach($list as $item) : ?>
	<li>
		<h4>
			<a class="item" href="<?php echo JRoute::_($item->page_route); ?>" title="<?php echo htmlspecialchars($item->page_title, ENT_QUOTES, 'UTF-8'); ?>">
				<?php echo htmlspecialchars($item->page_title, ENT_QUOTES, 'UTF-8'); ?></a>
		</h4>
<?php if ($params->get('show_rating_stars', 1)) : ?>
		<div class="item-rating">
			<span class="current-rating" style="width:<?php echo (int) ($item->rating_pscore*100); ?>%;">
<?php if (!$params->get('show_rating_text', 1)) : ?>
				<?php echo JText::sprintf(($item->rating_pscore_count == 1) ? 'Comments_Rating_Text_S' : 'Comments_Rating_Text_P', round($item->rating_pscore*5, 1), 5, $item->rating_pscore_count); ?>
<?php endif; ?>
			</span>
		</div>
<?php endif; ?>
<?php if ($params->get('show_rating_text', 1)) : ?>
		<span class="current-rating">
			<?php echo JText::sprintf(($item->rating_pscore_count == 1) ? 'Comments_Rating_Text_S' : 'Comments_Rating_Text_P', round($item->rating_pscore*5, 1), 5, $item->rating_pscore_count); ?>
		</span>
<?php endif; ?>
	</li>
<?php endforeach; ?>
</ol>
