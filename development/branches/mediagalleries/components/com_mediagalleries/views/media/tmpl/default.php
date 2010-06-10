<?php
/*	echo JHTML::_('calendar', time(), 'data', 'porra');//', $this->section, 'title');
	echo '<br />';
	echo JHTML::_('behavior.tooltip');//, 'content tool', 'spacer.png', 'Spacer', 'http://asda', 'images');
	echo '<br />';
	echo JHTML::_('link', 'index.php?option=com_somecom', 'Some Component');
	echo '<br />';
	echo JHTML::_('email.cloak', 'example@example.org');
	echo '<br />';	
//	echo JHTML::_('iframe', 'http://www.joomla.org', 'google');
//	echo '<br />';		
	*/
// Behavior
JHTML::_('behavior.tooltip');
JHTML::_('behavior.mootools');
JHTML::_('behavior.formvalidation');
?>
<?php	 // Custom size
	echo '<style>';
		echo '#mediagalleries #player{';
			if($this->params->get('width') ){
				echo 'width: '.$this->params->get('width').'px;';
	 		}
			if($this->params->get('height') ){
				echo 'height: '.$this->params->get('height').'px;';
	 		}
		echo '}';
	echo '</style>'; 
 ?>	

<div id="mediagalleries" class="contentpaneopen">
<div class="watch">	
	<h1 class="componentheading"><?php echo $this->item->title; ?></h1>

	<div class="videodata">	
		<div id="player">
			<div class="up-ll"><div class="up-rt">
				<div class="down-lt"><div class="down-rt">
					<?php echo $this->video; ?>
				</div></div>
			</div></div>
		</div>
		
		
		<div><br />
<?php // Show Embed Code
	if($this->params->get('show_embed')){
?>	<form>
			<label for="embed">Get Video Code: </label>
			<input type="text" class="inputbox" name="embed" id="embed" 
				readonly="readonly" 
				value="<?php echo $this->embed; ?>" onfocus="" />	
		</form>		
<?php
	} // [end]if
?>	
			<div class="contentdescription">
				<strong class="createby"><?php echo $this->item->author; ?>:&nbsp;</strong>
				<?php echo $this->item->description; ?>
			</div>
			<ul class="mediadetails">
				<li class="createdate"><?php echo $this->item->added; ?></li>
				<li><strong><?php echo JText::_('Category'); ?>: </strong> <?php echo $this->item->category; ?></li>
				<li><strong><?php echo JText::_('Views'); ?>: </strong> <?php echo $this->item->hits; ?></li>
				<!--li><strong><?php echo JText::_('Rating'); ?>: </strong> <span class="mediarating"><?php echo ceil($this->item->rating); ?></span></li-->
			</ul>
		</div>
	
	</div>
	
	
	<div class="channelinfo">
		<ul>
			<li></li>			
		</ul>
	</div>
	
	<?php // Comments
		if($this->params->get('allow_comments', 1)){
			echo $this->comments;
		}
	?>
</div>	
</div>
