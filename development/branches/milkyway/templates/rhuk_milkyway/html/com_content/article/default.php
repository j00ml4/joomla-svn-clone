<?php // no direct access
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers');
$params		= $this->params;
$canEdit	= $params->get('access-edit');
?>
<?php if ($this->params->get('show_page_heading', 1) && $this->params->get('page_title') != $this->article->title) : ?>
	<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</div>
<?php endif; ?>
<?php if ($canEdit || $this->params->get('show_title') || $this->params->get('show_print_icon') || $this->params->get('show_email_icon')) : ?>
<table class="contentpaneopen<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<tr>
	<?php if ($this->params->get('show_title')) : ?>
	<td class="contentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" width="100%">
		<?php if ($this->params->get('link_titles') && $this->item->readmore_link != '') : ?>
		<a href="<?php echo $this->item->readmore_link; ?>" class="contentpagetitle<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
			<?php echo $this->escape($this->item->title); ?></a>
		<?php else : ?>
			<?php echo $this->escape($this->item->title); ?>
		<?php endif; ?>
	</td>
	<?php endif; ?>
	<?php if (!$this->print) : ?>

		<?php if ($params->get('show_print_icon')) : ?>
		<td align="right" width="100%" class="buttonheading">
		<?php echo JHtml::_('icon.print_popup',  $this->item, $params); ?>
		</td>
		<?php endif; ?>

		<?php if ($params->get('show_email_icon')) : ?>
		<td align="right" width="100%" class="buttonheading">
		<?php echo JHtml::_('icon.email',  $this->item, $params); ?> 
		</td>
		<?php endif; ?>
		<?php if ($canEdit) : ?>
		<td align="right" width="100%" class="buttonheading">
			<?php echo JHtml::_('icon.edit', $this->item, $params); ?>
		</td>
		<?php endif; ?>
	<?php else : ?>
		<td align="right" width="100%" class="buttonheading">
		<?php echo JHtml::_('icon.print_screen',  $this->item, $params); ?>
		</td>
	<?php endif; ?>
</tr>
</table>
<?php endif; ?>

<?php  if (!$this->params->get('show_intro')) :
	echo $this->item->event->afterDisplayTitle;
endif; ?>
<?php echo $this->item->event->beforeDisplayContent; ?>
<table class="contentpaneopen<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<?php if (($this->params->get('show_section') && $this->item->sectionid) || ($this->params->get('show_category') && $this->item->catid)) : ?>
<tr>
	<td>
		<?php if ($this->params->get('show_section') && $this->item->sectionid && isset($this->item->section)) : ?>
		<span>
			<?php if ($this->params->get('link_section')) : ?>
				<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->item->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->escape($this->item->section); ?>
			<?php if ($this->params->get('link_section')) : ?>
				<?php echo '</a>'; ?>
			<?php endif; ?>
				<?php if ($this->params->get('show_category')) : ?>
				<?php echo ' - '; ?>
			<?php endif; ?>
		</span>
		<?php endif; ?>
		<?php if ($this->params->get('show_category') && $this->item->catid) : ?>
		<span>
			<?php if ($this->params->get('link_category')) : ?>
				<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug, $this->item->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->escape($this->item->category); ?>
			<?php if ($this->params->get('link_category')) : ?>
				<?php echo '</a>'; ?>
			<?php endif; ?>
		</span>
		<?php endif; ?>
	</td>
</tr>
<?php endif; ?>
<?php if (($this->params->get('show_author')) && ($this->item->author != "")) : ?>
<tr>
	<td valign="top">
		<span class="small">
			<?php JText::printf( 'COM_CONTENT_WRITTEN_BY', ($this->escape($this->item->created_by_alias) ? $this->escape($this->item->created_by_alias) : $this->escape($this->item->author)) ); ?>
		</span>
		&#160;&#160;
	</td>
</tr>
<?php endif; ?>

<?php if ($this->params->get('show_create_date')) : ?>
<tr>
	<td valign="top" class="createdate">
		<?php echo JHTML::_('date',$this->item->created, JText::_('DATE_FORMAT_LC2')) ?>
	</td>
</tr>
<?php endif; ?>

<?php if ($this->params->get('show_url') && $this->item->urls) : ?>
<tr>
	<td valign="top">
		<a href="http://<?php echo $this->item->urls ; ?>" target="_blank">
			<?php echo $this->escape($this->item->urls); ?></a>
	</td>
</tr>
<?php endif; ?>

<tr>
<td valign="top">
	<?php if (isset ($this->item->toc)) : ?>
		<?php echo $this->item->toc; ?>
<?php endif; ?>
	<?php echo $this->item->text; ?>

</td>
</tr>

<?php if ( intval($this->item->modified) !=0 && $this->params->get('show_modify_date')) : ?>
<tr>
	<td class="modifydate">
		<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHTML::_('date',$this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?>
	</td>
</tr>
<?php endif; ?>
</table>
<span class="article_separator">&#160;</span>
<?php echo $this->item->event->afterDisplayContent; ?>
