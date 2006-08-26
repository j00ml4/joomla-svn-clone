<?php if ($this->user->authorize('action', 'edit', 'content', 'all')) : ?>
	<div class="contentpaneopen_edit<?php echo $this->params->get( 'pageclass_sfx' ); ?>" style="float: left;">
		<?php JContentHTMLHelper::editIcon($this->article, $this->params, $this->access); ?>
	</div>
<?php endif; ?>
<?php if ($this->params->get('item_title') || $this->params->get('pdf') || $this->params->get('print') || $this->params->get('email')) : ?>
<table class="contentpaneopen<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<tr>
<?php
	// displays Item Title
	JContentHTMLHelper::title($this->article, $this->params, $this->article->readmore_link, $this->access);

	// displays PDF Icon
	JContentHTMLHelper::pdfIcon($this->article, $this->params, $this->article->readmore_link, false);

	// displays Print Icon
	mosHTML::PrintIcon($this->article, $this->params, false, $this->article->print_link);

	// displays Email Icon
	JContentHTMLHelper::emailIcon($this->article, $this->params, false);
?>
</tr>
</table>
<?php endif; ?>
<?php  if (!$this->params->get('intro_only')) :
	echo $this->article->event->afterDisplayTitle;
endif; ?>
<?php echo $this->article->event->beforeDisplayContent; ?>
<table class="contentpaneopen<?php echo $this->params->get( 'pageclass_sfx' ); ?>">	
<?php

	// displays Section & Category
	JContentHTMLHelper::sectionCategory($this->article, $this->params);

	// displays Author Name
	JContentHTMLHelper::author($this->article, $this->params);

	// displays Created Date
	JContentHTMLHelper::createDate($this->article, $this->params);

	// displays Urls
	JContentHTMLHelper::url($this->article, $this->params);
?>
<tr>
<td valign="top" colspan="2">
<?php

	// displays Table of Contents
	JContentHTMLHelper::toc($this->article);

	// displays Item Text
	echo ampReplace($this->article->text);
?>
</td>
</tr>
<?php

	// displays Modified Date
	JContentHTMLHelper::modifiedDate($this->article, $this->params);

	// displays Readmore button
	JContentHTMLHelper::readMore($this->params, $this->article->readmore_link, $this->article->readmore_text);
?>
</table>
<span class="article_seperator">&nbsp;</span>
<?php echo $this->article->event->afterDisplayContent; ?>