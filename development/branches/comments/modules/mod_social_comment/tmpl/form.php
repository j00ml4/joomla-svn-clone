<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_comment
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<form id="respond-form" method="post" action="<?php echo JRoute::_('index.php?option=com_social&task=comment.add'); ?>" class="form-validate">
	<fieldset>
		<dl>
<?php if (!$user->get('id')) : ?>
			<dt><label for="comment_name"><?php echo JText::_('MOD_SOCIAL_COMMENT_NAME');?></label></dt>
			<dd><input id="comment_name" name="name" class="inputbox required" tabindex="1" /></dd>
<?php endif; ?>

<?php if (!$user->get('id')) : ?>
			<dt><label for="comment_email"><?php echo JText::_('MOD_SOCIAL_COMMENT_EMAIL');?></label></dt>
			<dd><input id="comment_email" name="email" class="inputbox required validate-email" tabindex="2" /></dd>
<?php endif; ?>

<?php if ($params->get('enable_website', 1)) : ?>
			<dt><label for="comment_url"><?php echo JText::_('MOD_SOCIAL_COMMENT_WEBSITE');?></label></dt>
			<dd><input id="comment_url" name="url" class="inputbox" tabindex="3" /></dd>
<?php endif; ?>

<?php if ($params->get('enable_subject', 1)) : ?>
			<dt><label for="comment_subject"><?php echo JText::_('MOD_SOCIAL_COMMENT_SUBJECT');?></label></dt>
			<dd><input id="comment_subject" name="subject" class="inputbox" tabindex="4" value="<?php echo $params->get('title'); ?>" /></dd>
<?php endif; ?>

			<dt><label for="comment_body"><?php echo JText::_('MOD_SOCIAL_COMMENT_COMMENT');?></label></dt>
			<dd>
				<textarea id="comment_body" name="body" class="bbcode required" tabindex="5" width="100%" height="200px"></textarea>
			</dd>
		</dl>

		<input type="submit" name="button" value="<?php echo JText::_('MOD_SOCIAL_COMMENT_SUBMIT');?>" id="submitter" tabindex="7" />

		<input type="hidden" name="thread_id" value="<?php echo $thread->id; ?>" />
		<input type="hidden" name="redirect" value="<?php echo base64_encode($uri->toString(array('path', 'query', 'fragment'))); ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</fieldset>
</form>
