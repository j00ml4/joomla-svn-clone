<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_comment
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// attach the comments stylesheet to the document head
JHtml::stylesheet('social/comments.css', array(), true);

// load the comments form behavior
JHtml::_('behavior.formvalidation');
JHtml::script('social/comments.js', false, true);

// load the appropriate comment editor behavior
JHtml::script('social/posteditor.js', false, true);
?>

	<div id="comments">
		<h3 id="comments-num">
<?php echo JText::__('MOD_SOCIAL_COMMENT_N_COMMENTS', (int)$pagination->total); ?>
		</h3>
<?php if ($pagination->total && ($params->get('enable_comment_feeds', 1))) : ?>
		<a class="comments-feed" href="<?php echo JRoute::_('index.php?option=com_social&view=comments&thread_id='.$thread->id.'&format=feed') ?>"><?php echo JText::_('MOD_SOCIAL_COMMENT_Feed'); ?></a>
<?php endif; ?>
		<ol id="commentlist">
			<?php
			if (!empty($comments)) :
				foreach ($comments as $i => $item) :
			?>
			<li class="comment <?php echo $i % 2 ? 'odd' : 'even';?>" id="comment-<?php echo $item->id;?>">
				<?php require(JModuleHelper::getLayoutPath('mod_social_comment', $item->trackback ? 'default_trackback' : 'default_comment')); ?>
			</li>
			<?php
				endforeach;
			endif;
			?>
		</ol>
	</div>
<?php
	if ($params->get('pagination')) {
		echo $pagination->getPagesLinks();
	}
?>

<?php if (($params->get('guestcomment') == 0 && $user->guest == false) || $params->get('guestcomment')) :
		if ($params->get('show_form', 1)) :
?>
	<h3 id="leave-response">
		<?php echo JText::_('MOD_SOCIAL_COMMENT_ADD_COMMENT');?>
	</h3>
	<div id="respond-container">
		<?php require(JModuleHelper::getLayoutPath('mod_social_comment', 'form')); ?>
	</div>
<?php else : ?>
	<h3 id="leave-response">
		<a href="<?php echo JRoute::_(modSocialCommentHelper::getForceFormURL($params)); ?>" rel="nofollow {'base':'<?php echo JURI::base(); ?>','thread_id':'<?php echo $thread->id; ?>'}"><?php echo JText::_('MOD_SOCIAL_COMMENT_Add_Comment');?></a>
	</h3>
	<div id="respond-container"></div>
<?php endif;
	endif;
?>
