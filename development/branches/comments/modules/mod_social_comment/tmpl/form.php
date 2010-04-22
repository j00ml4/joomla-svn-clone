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

<form id="respond-form" method="post" action="<?php echo JRoute::_('index.php?option='.$extension.'&task=social.addcomment'); ?>" class="form-validate">
	<fieldset>
		<dl>
<?php if (!$user->get('id')) : ?>
			<dt><?php echo $form->getLabel('name'); ?></dt>
			<dd><?php echo $form->getInput('name'); ?></dd>
<?php endif; ?>

<?php if (!$user->get('id')) : ?>
			<dt><?php echo $form->getLabel('email'); ?></dt>
			<dd><?php echo $form->getInput('email'); ?></dd>
<?php endif; ?>

<?php if ($params->get('enable_website', 1)) : ?>
			<dt><?php echo $form->getLabel('url'); ?></dt>
			<dd><?php echo $form->getInput('url'); ?></dd>
<?php endif; ?>

<?php if ($params->get('enable_subject', 1)) : ?>
			<dt><?php echo $form->getLabel('subject'); ?></dt>
			<dd><?php echo $form->getInput('subject'); ?></dd>
<?php endif; ?>

			<dt><?php echo $form->getLabel('body'); ?></dt>
			<dd><?php echo $form->getInput('body'); ?></dd>
		</dl>

		<input type="submit" name="button" value="<?php echo JText::_('MOD_SOCIAL_COMMENT_SUBMIT');?>" id="submitter" tabindex="7" />

		<?php echo $form->getInput('context'); ?>
		<?php echo $form->getInput('redirect'); ?>
		<?php echo JHtml::_('form.token'); ?>
	</fieldset>
</form>
