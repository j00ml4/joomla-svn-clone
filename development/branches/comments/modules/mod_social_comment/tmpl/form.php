<?php
/**
 * @version		$Id$
 * @package		Joomla.Site
 * @subpackage	mod_social_comment
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Invalid Request.');

// get the captcha data object
$captcha = modCommentsCommentHelper::getCaptcha($params);
?>

<form id="respond-form" method="post" action="<?php echo JRoute::_('index.php?option=com_comments&task=comment.add'); ?>" class="form-validate">
	<fieldset>
		<dl>
<?php if (!$user->get('id')) : ?>
			<dt><label for="comment_name"><?php echo JText::_('Comments_Name');?></label></dt>
			<dd><input id="comment_name" name="name" class="inputbox required" tabindex="1" /></dd>
<?php endif; ?>

<?php if (!$user->get('id')) : ?>
			<dt><label for="comment_email"><?php echo JText::_('Comments_Email');?></label></dt>
			<dd><input id="comment_email" name="email" class="inputbox required validate-email" tabindex="2" /></dd>
<?php endif; ?>

<?php if ($params->get('enable_website', 1)) : ?>
			<dt><label for="comment_url"><?php echo JText::_('Comments_Website');?></label></dt>
			<dd><input id="comment_url" name="url" class="inputbox" tabindex="3" /></dd>
<?php endif; ?>

<?php if ($params->get('enable_subject', 1)) : ?>
			<dt><label for="comment_subject"><?php echo JText::_('Comments_Subject');?></label></dt>
			<dd><input id="comment_subject" name="subject" class="inputbox" tabindex="4" value="<?php echo $params->get('title'); ?>" /></dd>
<?php endif; ?>

			<dt><label for="comment_body"><?php echo JText::_('Comments_Comment');?></label></dt>
			<dd>
<?php if ($params->get('enable_bbcode', 1)) : ?>
				<div id="bbcode-editor">
					<ul class="toolbar"></ul>
					<span class="help"></span>
					<br class="clear" />
					<textarea id="comment_body" name="body" class="editor inputbox required" tabindex="5" rows="8" cols="60" style="font-size:12px;"></textarea>
<?php if ($params->get('enable_smiley_palette', 1) && count($emoticons = modCommentsCommentHelper::getEmoticonList($params))) : ?>
					<ul class="emoticon-palette">
<?php foreach ($emoticons as $emoticon) : ?>
						<li>
							<img class="emoticon-palette" src="<?php echo $emoticon->path; ?>" alt="<?php echo $emoticon->code; ?>" title="<?php echo $emoticon->name; ?>" />
						</li>
<?php endforeach; ?>
					</ul>
					<br class="clear" />
<?php endif; ?>
				</div>
<?php else : ?>
				<textarea id="comment_body" name="body" class="bbcode required" tabindex="5" width="100%" height="200px"></textarea>
<?php endif; ?>
			</dd>
		</dl>

<?php if ($captcha->enabled) : ?>
		<div class="captcha">
<?php if ($captcha->recaptcha) : ?>
			<div id="recaptchaContainer">&nbsp;</div>
<?php else : ?>
			<img class="captcha-image" src="<?php echo JRoute::_('index.php?option=com_comments&task=getCaptcha&x='.$captcha->c_id); ?>" alt="" />

			<label for="comment_captcha"><?php echo JText::_('Comments_Captcha');?></label><br />
			<input id="comment_captcha" name="<?php echo $captcha->c_id; ?>" class="inputbox required" tabindex="6" />

			<br />
			<a href="#" class="captcha-image-refresh"><?php echo JText::_('Comments_Captcha_Refresh'); ?></a>
<?php endif; ?>
		</div>
<?php endif; ?>

		<input type="submit" name="button" value="<?php echo JText::_('Comments_Submit');?>" id="submitter" tabindex="7" />

		<input type="hidden" name="thread_id" value="<?php echo $thread->id; ?>" />
		<input type="hidden" name="redirect" value="<?php echo base64_encode($uri->toString(array('path', 'query', 'fragment'))); ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</fieldset>
</form>
