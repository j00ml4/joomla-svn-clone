<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_rating
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');

// attach the comments stylesheet to the document head
JHtml::stylesheet('social/comments.css', array(), true);

// load the comments rating behavior
JHtml::script('social/ratings.js', false, true);

// Handle custom star files.
if ($params->get('star_file')) {
	$document->addStyleDeclaration(
		'	ul.rating-stars,ul.rating-stars a:hover,ul.rating-stars .current-rating{background-image: url('.JURI::base().'components/com_comments/media/images/'.$params->get('star_file', 'star').'.png)}'
	);
}
?>

<?php
$redirect = base64_encode($uri->toString(array('path', 'query', 'fragment')));
$degraded = 'index.php?option=com_comments&task=rating.add&thread_id='.$thread->id.'&category_id='.(int) $params->get('category_id').'&'.JUtility::getToken().'=1&redirect='.$redirect.'&score=';
?>

<div class="rating-container">
	<form id="rate-<?php echo $thread->id; ?>" class="addrating" method="post" action="<?php echo JRoute::_('index.php?option=com_comments&task=rating.add');?>">
		<ul class="rating-stars">
			<li id="current-<?php echo $thread->id; ?>" class="current-rating" style="width:<?php echo (int) ($rating->pscore*100); ?>%;"></li>
			<li><a title="1" rel="0.2" class="one-star rate" href="<?php echo JRoute::_($degraded.'0.2'); ?>" rel="nofollow">1</a></li>
			<li><a title="2" rel="0.4" class="two-stars rate" href="<?php echo JRoute::_($degraded.'0.4'); ?>" rel="nofollow">2</a></li>
			<li><a title="3" rel="0.6" class="three-stars rate" href="<?php echo JRoute::_($degraded.'0.6'); ?>" rel="nofollow">3</a></li>
			<li><a title="4" rel="0.8" class="four-stars rate" href="<?php echo JRoute::_($degraded.'0.8'); ?>" rel="nofollow">4</a></li>
			<li><a title="5" rel="1.0" class="five-stars rate" href="<?php echo JRoute::_($degraded.'1.0'); ?>" rel="nofollow">5</a></li>
		</ul>

		<input type="hidden" name="score" value="" />
		<input type="hidden" name="thread_id" value="<?php echo $thread->id; ?>" />
		<input type="hidden" name="category_id" value="<?php echo (int) $params->get('category_id'); ?>" />
		<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>

	<div id="rating-count-<?php echo $thread->id; ?>" class="rating-count">
		<small><span class="count"><?php echo (int) $rating->pscore_count;?></span>
			<span class="string"><?php echo ($rating->pscore_count == 1 ? JText::_('Comments_Vote') : JText::_('Comments_Votes'));?></span></small>
	</div>
</div>
