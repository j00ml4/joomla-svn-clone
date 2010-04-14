<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	com_social
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include the HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
jimport('joomla.html.pane');
$pane = JPane::getInstance('sliders');
?>
<div style="float:left;width:60%;">

	<form name="adminForm" method="post" action="<?php echo JRoute::_('index.php?option=com_social'); ?>">
		<fieldset>
			<?php echo $this->form->getLabel('published'); ?>
			<?php echo $this->form->getInput('published'); ?>

			<input type="button" onclick="submitbutton('comment.moderate')" value="<?php echo $this->escape(JText::_('SOCIAL_MODERATE')); ?>" />
			<input type="hidden" name="id" value="<?php echo (int) $this->item->id; ?>" />
			<input type="hidden" name="return" value="<?php echo base64_encode('index.php?option=com_social&view=comment&id='.(int) $this->item->id);?>" />
			<input type="hidden" name="task" />
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</form>

	<fieldset>
		<legend><?php echo JText::_('SOCIAL_COMMENT'); ?>: <?php echo $this->item->id; ?></legend>

		<div class="comment-referrer">
			<span><?php echo JText::_('SOCIAL_PAGE'); ?>:</span>
			<a href="<?php echo $this->getContentRoute($this->thread->page_route); ?>" target="_blank"><?php echo htmlspecialchars($this->thread->page_title, ENT_QUOTES, 'UTF-8'); ?></a></div>

		<dl class="comment">
			<dt><?php echo JText::_('SOCIAL_AUTHOR'); ?></dt>
			<dd>
				<div class="comment-author-name">
					<strong class="author-name"><?php echo $this->item->name; ?></strong> <a href="index.php?option=com_social&amp;task=block&amp;block=name&amp;cid[]=<?php echo $this->item->name;?>&amp;view=moderate">[ <?php echo JText::_('SOCIAL_BLOCK');?> ]</a></div>
				<ul class="comment-author-data">
					<li class="email" title="<?php echo JText::_('SOCIAL_EMAIL'); ?>"><?php echo $this->item->email; ?></li>

					<li class="ip" title="<?php echo JText::_('SOCIAL_IP_ADDRESS'); ?>"><a href="http://ip-lookup.net/index.php?ip=<?php echo $this->item->address; ?>" target="_new"><?php echo $this->item->address; ?></a>
						<a href="index.php?option=com_social&amp;task=config.block&amp;block=address&amp;cid[]=<?php echo $this->item->id;?>">[ <?php echo JText::_('SOCIAL_BLOCK');?> ]</a></li>

					<li class="url" title="<?php echo JText::_('SOCIAL_WEBSITE_URL'); ?>"><?php echo ($this->item->url) ? $this->item->url : JText::_('SOCIAL_NOT_AVAILABLE'); ?></li>
				</ul>
			</dd>

			<dt class="date"><?php echo JText::_('SOCIAL_DATE'); ?></dt>
			<dd class="date"><?php echo JHTML::_('date',$this->item->created_time, JText::_('DATE_FORMAT_LC2')); ?></dd>

			<dt class="subject"><?php echo JText::_('SOCIAL_SUBJECT'); ?></dt>
			<dd class="subject"><?php echo ($this->item->subject) ? $this->item->subject : JText::_('SOCIAL_NOT_AVAILABLE'); ?></dd>

			<dt class="body"><?php echo JText::_('SOCIAL_BODY'); ?></dt>
			<dd class="body"><?php echo $this->escape($this->item->body); ?></dd>
		</dl>
	</fieldset>
</div>

<!-- RELATED POSTS COLUMN -->
<div style="float:left;width:40%;">
	<?php echo $pane->startPane('alternate-post-pane');
	echo $pane->startPanel(JText::_('SOCIAL_RECENT_POSTS_CONTEXT'), 'context-list-panel'); ?>

<?php if (count($this->threadList)) : ?>
	<ul class="comment-list">
<?php foreach ($this->threadList as $item) : ?>
		<li>
			<fieldset>
				<legend><?php echo JText::_('SOCIAL_COMMENT'); ?>: <?php echo $item->id; ?></legend>
				<dl class="comment-summary">
					<dt class="author"><?php echo JText::_('SOCIAL_AUTHOR'); ?></dt>
					<dd class="author"><?php echo $item->name; ?></dd>

					<dt class="date"><?php echo JText::_('SOCIAL_DATE'); ?></dt>
					<dd class="date"><?php echo JHTML::_('date',$item->created_time, JText::_('DATE_FORMAT_LC2')); ?></dd>

					<dt class="subject"><?php echo JText::_('SOCIAL_SUBJECT'); ?></dt>
					<dd class="subject"><?php echo ($item->subject) ? $item->subject : JText::_('SOCIAL_NOT_AVAILABLE'); ?></dd>

					<dt class="summary"><?php echo JText::_('SOCIAL_BODY'); ?></dt>
					<dd class="summary"><?php echo $this->escape($item->body); ?></dd>
				</dl>
			</fieldset>
		</li>
<?php endforeach; ?>
	</ul>
<?php endif; ?>

	<?php echo $pane->endPanel();
	echo $pane->startPanel(JText::_('SOCIAL_RECENT_POSTS_NAME'), 'name-list-panel'); ?>

<?php if (count($this->nameList)) : ?>
	<ul class="comment-list">
<?php foreach ($this->nameList as $item) : ?>
		<li>
			<fieldset>
				<legend><?php echo JText::_('SOCIAL_COMMENT'); ?>: <?php echo $item->id; ?></legend>
				<dl class="comment-summary">
					<dt class="author"><?php echo JText::_('SOCIAL_AUTHOR'); ?></dt>
					<dd class="author"><?php echo $item->name; ?></dd>

					<dt class="date"><?php echo JText::_('SOCIAL_DATE'); ?></dt>
					<dd class="date"><?php echo JHTML::_('date',$item->created_time, JText::_('DATE_FORMAT_LC2')); ?></dd>

					<dt class="subject"><?php echo JText::_('SOCIAL_SUBJECT'); ?></dt>
					<dd class="subject"><?php echo ($item->subject) ? $item->subject : JText::_('SOCIAL_NOT_AVAILABLE'); ?></dd>

					<dt class="summary"><?php echo JText::_('SOCIAL_BODY'); ?></dt>
					<dd class="summary"><?php echo $this->escape($item->body); ?></dd>
				</dl>
			</fieldset>
		</li>
<?php endforeach; ?>
	</ul>
<?php endif; ?>

	<?php echo $pane->endPanel();
	echo $pane->startPanel(JText::_('SOCIAL_RECENT_POSTS_IP_ADDRESS'), 'address-list-panel'); ?>

<?php if (count($this->addressList)) : ?>
	<ul class="comment-list">
<?php foreach ($this->addressList as $item) : ?>
		<li>
			<fieldset>
				<legend><?php echo JText::_('SOCIAL_COMMENT'); ?>: <?php echo $item->id; ?></legend>
				<dl class="comment-summary">
					<dt class="author"><?php echo JText::_('SOCIAL_AUTHOR'); ?></dt>
					<dd class="author"><?php echo $item->name; ?></dd>

					<dt class="date"><?php echo JText::_('SOCIAL_DATE'); ?></dt>
					<dd class="date"><?php echo JHTML::_('date',$item->created_time, JText::_('DATE_FORMAT_LC2')); ?></dd>

					<dt class="subject"><?php echo JText::_('SOCIAL_SUBJECT'); ?></dt>
					<dd class="subject"><?php echo ($item->subject) ? $item->subject : JText::_('SOCIAL_NOT_AVAILABLE'); ?></dd>

					<dt class="summary"><?php echo JText::_('SOCIAL_BODY'); ?></dt>
					<dd class="summary"><?php echo $this->escape($item->body); ?></dd>
				</dl>
			</fieldset>
		</li>
<?php endforeach; ?>
	</ul>
<?php endif; ?>

	<?php echo $pane->endPanel();
	echo $pane->endPane(); ?>
</div>