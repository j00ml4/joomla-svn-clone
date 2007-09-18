<?php // @version $Id$
defined('_JEXEC') or die('Restricted access');
?>

<?php if ($this->user->authorize('com_content', 'edit', 'content', 'all')) : ?>
<div class="contentpaneopen_edit<?php echo $this->params->get('pageclass_sfx'); ?>" style="float: left;">
	<?php echo JHTML::_('icon.edit', $this->item, $this->params, $this->access); ?>
</div>
<?php endif; ?>

<?php if ($this->params->get('show_title')) : ?>
<h2 class="contentheading<?php echo $this->params->get('pageclass_sfx'); ?>">
	<?php if ($this->params->get('link_titles') && $this->item->readmore_link != '') : ?>
		<a href="<?php echo $this->item->readmore_link; ?>" class="contentpagetitle<?php echo $this->params->get('pageclass_sfx'); ?>">
			<?php echo $this->item->title; ?>
		</a>
	<?php else :
		echo $this->item->title;
	endif; ?>
</h2>
<?php endif; ?>

<?php if (!$this->params->get('show_intro')) :
	echo $this->item->event->afterDisplayTitle;
endif; ?>

<?php if ($this->params->get('show_pdf_icon') || $this->params->get('show_print_icon') || $this->params->get('show_email_icon')) : ?>
<p class="buttonheading">
	<img src="templates/<?php echo $mainframe->getTemplate(); ?>/images/trans.gif" alt="<?php echo JText::_('attention open in a new window'); ?>" />
	<?php if ($this->params->get('show_pdf_icon')) :
		echo JHTML::_('icon.pdf', $this->item, $this->params, $this->access);
	endif;
	if ($this->params->get('show_print_icon')) :
		echo JHTML::_('icon.print_popup', $this->item, $this->params, $this->access);
	endif;
	if ($this->params->get('show_email_icon')) :
		echo JHTML::_('icon.email', $this->item, $this->params, $this->access);
	endif; ?>
</p>
<?php endif; ?>

<?php if (($this->params->get('show_section') && $this->item->sectionid) || ($this->params->get('show_category') && $this->item->catid)) : ?>
<p class="pageinfo">
	<?php if ($this->params->get('show_section') && $this->item->sectionid) : ?>
	<span>
		<?php echo $this->item->section;
		if ($this->params->get('show_category')) :
			echo ' - ';
		endif; ?>
	</span>
	<?php endif; ?>

	<?php if ($this->params->get('show_category') && $this->item->catid) : ?>
	<span>
		<?php echo $this->item->category; ?>
	</span>
	<?php endif; ?>
</p>
<?php endif; ?>

<?php if ((!empty ($this->item->modified) && $this->params->get('show_modify_date')) || ($this->params->get('show_author') && ($this->item->author != "")) || ($this->params->get('show_create_date'))) : ?>
<p class="iteminfo">
	<?php if (!empty ($this->item->modified) && $this->params->get('show_modify_date')) : ?>
	<span class="modifydate">
		<?php echo JText::_('Last Updated').' ('.JHTML::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2')).')'; ?>
	</span>
	<?php endif; ?>

	<?php if (($this->params->get('show_author')) && ($this->item->author != "")) : ?>
	<span class="createdby">
		<?php JText::printf('Written by', ($this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author)); ?>
	</span>
	<?php endif; ?>

	<?php if ($this->params->get('show_create_date')) : ?>
	<span class="createdate">
		<?php echo JHTML::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2')); ?>
	</span>
	<?php endif; ?>
</p>
<?php endif; ?>

<?php echo $this->item->event->beforeDisplayContent; ?>

<?php if ($this->params->get('show_url') && $this->item->urls) : ?>
<span class="small">
	<a href="<?php echo $this->item->urls; ?>" target="_blank">
		<?php echo $this->item->urls; ?>
	</a>
</span>
<?php endif; ?>

<?php if (isset ($this->item->toc)) :
	echo $this->item->toc;
endif; ?>

<?php echo JFilterOutput::ampReplace($this->item->text); ?>

<?php if ($this->params->get('show_readmore') && $this->item->readmore) : ?>
<p>
	<a href="<?php echo $this->item->readmore_link; ?>" class="readon<?php echo $this->params->get('pageclass_sfx'); ?>">
			<?php if ($this->item->readmore_text) : ?>
				<?php echo JText::sprintf('Read more', $this->params->get('readmore', $this->item->title)); ?>
			<?php else : ?>
				<?php echo JText::_('Register to read more...'); ?>
			<?php endif; ?>
	</a>
</p>
<?php endif; ?>

<?php echo $this->item->event->afterDisplayContent;
