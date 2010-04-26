<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_rating
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// attach the comments stylesheet to the document head
JHtml::stylesheet('social/comments.css', array(), true);

// Handle custom star files.
if ($params->get('star_file')) {
	$document->addStyleDeclaration(
		'	ul.rating-stars,ul.rating-stars a:hover,ul.rating-stars .current-rating{background-image: url('.JURI::base().'components/com_social/media/images/'.$params->get('star_file', 'star').'.png)}'
	);
}
?>

<div class="rating-container">
	<ul class="rating-stars">
		<li id="current-<?php echo $params->get('context'); ?>" class="current-rating" style="width:<?php echo (int) ($rating->score*100); ?>%;"></li>
		<li><a title="1" rel="0.2" class="one-star" style="cursor:default;">1</a></li>
		<li><a title="2" rel="0.4" class="two-stars" style="cursor:default;">2</a></li>
		<li><a title="3" rel="0.6" class="three-stars" style="cursor:default;">3</a></li>
		<li><a title="4" rel="0.8" class="four-stars" style="cursor:default;">4</a></li>
		<li><a title="5" rel="1.0" class="five-stars" style="cursor:default;">5</a></li>
	</ul>

	<div id="rating-count-<?php echo $params->get('context'); ?>" class="rating-count">
		<small><?php echo JText::plural('MOD_SOCIAL_RATING_RATING_N_VOTES', $rating->count);?></small>
	</div>
</div>
