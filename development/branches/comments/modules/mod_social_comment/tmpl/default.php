<?php
/**
 * @version		$Id$
 * @package		JXtended.Comments
 * @subpackage	mod_social_comment
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://jxtended.com
 */

defined('_JEXEC') or die('Invalid Request.');

JHtml::addIncludePath(JPATH_ROOT.'/plugins/system/jxtended/html/html');


// attach the comments stylesheet to the document head
JHtml::stylesheet('social/comments.css', array(), true);

// load the comments form behavior
JHtml::_('behavior.formvalidation');
JHtml::script('social/comments.js', false, true);

// load the appropriate comment editor behavior
if ($params->get('enable_bbcode')) {
	modCommentsCommentHelper::loadBBCodeEditor();
} else {
	JHtml::script('social/posteditor.js', false, true);
}

// load the highlighter behavior
JHtml::script('highlighter.js', 'media/jxtended/js/');
JHtml::stylesheet('highlighter.css', 'media/jxtended/css/');

// get the captcha data object
$captcha = modCommentsCommentHelper::getCaptcha($params);
?>

	<div id="comments">
		<h3 id="comments-num">
<?php
	if ($pagination->total == 1) {
		echo JText::sprintf('Comment_Num', (int)$pagination->total);
	} else {
		echo JText::sprintf('Comments_Num', (int)$pagination->total);
	}
?>
		</h3>
<?php if ($pagination->total && ($params->get('enable_comment_feeds', 1))) : ?>
		<a class="comments-feed" href="<?php echo JRoute::_('index.php?option=com_comments&view=comments&thread_id='.$thread->id.'&format=feed') ?>"><?php echo JText::_('Comments_Feed'); ?></a>
<?php endif; ?>
		<ol id="commentlist">
			<?php
			if (!empty($comments)) :
				$k = 1;
				foreach ($comments as $item) :
			?>
			<li class="comment <?php echo $k ? 'odd' : 'even';?>" id="comment-<?php echo $item->id;?>">
				<?php require(JModuleHelper::getLayoutPath('mod_social_comment', $item->trackback ? 'default_trackback' : 'default_comment')); ?>
			</li>
			<?php
				$k = 1 - $k;
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

<?php if (modCommentsCommentHelper::isBlocked($params)) : ?>
	<p>
		<?php echo JText::_('Comments_Blocked'); ?>
	</p>
<?php elseif (($params->get('guestcomment') == 0 && $user->guest == false) || $params->get('guestcomment')) :
		if ($params->get('show_form', 1)) :
?>
	<h3 id="leave-response">
		<?php echo JText::_('Comments_Add_Comment');?>
	</h3>
	<div id="respond-container">
		<?php require(JModuleHelper::getLayoutPath('mod_social_comment', 'form')); ?>
	</div>
<?php else : ?>
	<h3 id="leave-response">
		<a href="<?php echo JRoute::_(modCommentsCommentHelper::getForceFormURL($params)); ?>" rel="nofollow {'base':'<?php echo JURI::base(); ?>','thread_id':'<?php echo $thread->id; ?>'}"><?php echo JText::_('Comments_Add_Comment');?></a>
	</h3>
	<div id="respond-container"></div>
<?php endif;
	endif;
?>
