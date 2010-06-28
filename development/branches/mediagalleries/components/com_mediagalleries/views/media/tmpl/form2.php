<?php 
/**
 * Display media form, follow weblinks model 
 */
defined('_JEXEC') or die('Restricted access'); 

// Behaviors
JHTML::_('behavior.tooltip');
JHTML::_('behavior.mootools');
JHTML::_('behavior.formvalidation');
?>

<script language="javascript" type="text/javascript">
	function submitform(pressbutton){
	   var form = document.adminForm;
	   if (pressbutton) {
	      form.task.value=pressbutton;
	   }
	   if (typeof form.onsubmit == "function") {
	      document.adminForm.onsubmit();
	   }
	   document.adminForm.submit();
	}
	
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// Title
		if (form.title.value == ""){
			alert( "<?php echo JText::_( JText::_('Your Media must contain a title.') ); ?>" );
			return;
		} 
		
		// Category 
		if (form.catid.value == "0"){
			alert( "<?php echo JText::_( 'You must select a category', true ); ?>" );
			return;
		} 
		
		// URL
		if ( 
			(form.url.value == '') 
			&& (form.uplocal.value == "") 
			&& (form.upyoutube.value == "") 
		){
			alert( "<?php echo JText::_('Please provide a valid URL'); ?>" );
			return;
		}
		

		submitform( pressbutton );
	}

	/**
	 * Clear upload field when URL is change
	 */
	function checkSendby(type){
		var form = document.adminForm;	
		
		if (type == 'url') {
			form.url.select();
			form.sendby.value = 'url';
		}
		else if (type == 'uplocal') {
			form.uplocal.select();
			form.sendby.value = 'uplocal';
			//form.upyoutube.disabled = true;
		}
		else if (type == 'upyoutube') {
			form.upyoutube.select();
			form.sendby.value = 'upyoutube';
			//form.upyoutube.disabled = false;
		}
	}
</script>

<div id="mediagalleries" class="contentpane">
<form enctype="multipart/form-data" action="<?php echo $this->action; ?>" method="post" name="adminForm" id="adminForm">
	<!-- Title -->		
	<?php if ($this->params->get('show_page_title')) : ?>
		<h1 class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
			<?php echo $this->params->get('page_title'); ?>
		</h1>
	<?php endif; ?>
	
	<!-- Properties -->
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable" >
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Title' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="title" id="title" 
					size="64" maxlength="250" value="<?php echo $this->item->title;?>" />
			</td>
		</tr>		
		<?php // Allow user set Published
			if( false ){
		?>		
		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'Published' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
		<?php // Auto Published
			}else{
				echo '<input type="hidden" name="published" value="1" />';
			}
		?>
		<!-- Category -->		
		<tr>
			<td valign="top" align="right" class="key">
				<label for="catid">
					<?php echo JText::_( 'Category' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['catid']; ?>
			</td>
		</tr>
		<!-- Desc -->
		<tr>
			<td valign="center" align="right" class="key">
				<label for="description">
					<?php echo JText::_( 'Description' ); ?>: 
				</label>
			</td>
			<td>
				<textarea class="inputbox" name="description" id="description"
					cols="64" rows="5" wrap="soft"  ><?php echo $this->item->description; ?></textarea>
			</td>
		</tr>
		</table>
	</fieldset>
		
	<!-- Get Media -->
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Send Media by' ); ?></legend>	
					
		<?php // Add from Remote URL
			$checked= ' checked="checked" ';
			$check = 1;
			$display = 'display:none;';
			if($this->params->get('allow_remoteurl')){		
				$display = '';
				if(!$check) $checked= '';
				$check = 0; 
			}
		?>		
		<table class="input" style="<?php echo $display; ?>">
		<tr>
			<th>
				<input  type="radio" name="sendby" id="sendby" <?php echo $checked; ?> value="url" onchange="checkSendby(this.value)" />
				<?php echo JText::_( 'Remote server URL' ); ?>
			</th>
		</tr>
		<tr>
			<td>
				<input type="text" id="url" name="url" class="inputbox"  size="64"
					title="youtube, video.yahoo, video.google, brigthcove "
					onchange="" value="<?php echo $this->item->url; ?>" />
			</td>
		</tr>
		</table>	
		
		
		<?php // Add by local Upload
			$display = 'display:none;';
			if($this->params->get('allow_localupload')){		
				$display = '';
				if(!$check) $checked= '';
				$check = 0; 
			}
		?>		
		<table class="input" style="<?php echo $display; ?>">
		<tr>
			<th>
				<input <?php echo $checked; ?> type="radio" name="sendby" id="sendby" value="uplocal" onchange="checkSendby(this.value)">
				<?php echo JText::_( 'File Upload' ); ?>
			</th>
		</tr>
		<tr>
			<td>
				<input type="file" class="inputbox" id="uplocal" name="uplocal" size="64"
					title=".FLV, .MOV, .SWF, .CLASS (java Applet)"
					onchange=" " accept="" />
				<input type="hidden" name="toFolder" id="toFolder" value="<?php echo $this->params->get('toFolder'); ?>" />
				<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $this->params->get('maxfilesize') * 1024; ?>">
			</td>
		</tr>
		</table>

		<?php // Add by youtube upload
			$display = 'display:none;';
			if($this->params->get('allow_youtubeupload')){		
				$display = '';
				if(!$check) $checked= '';
				$check = 0; 
			}
		?>		
		<table class="input" style="<?php echo $display; ?>">
		<tr>
			<th>
				<input <?php echo $checked; ?> type="radio" name="sendby" id="sendby" value="upyoutube"  onclick="checkSendby(this.value)">
				<?php echo JText::_( 'Upload to youtube server' ); ?>:
			</th>
		</tr>
		<?php // site youtubeuser not set
			if( !$this->params->get('youtube_key') ):
		?>
		<tr>
			<td>
				<table border="0">
				<tr>
					<td width="30">
						<input type="text" size="32" class="inputbox" id="youtube_login" name="youtube_login" value="" />
					</td>
					<td align="left">
						<label style="text-align:left;"  for="youtube_login"><?php echo JText::_('Youtube Login'); ?></label>
					</td>					
				</tr>	
				<tr>
					<td>
						<input type="password" size="32" class="inputbox" id="youtube_password" name="youtube_password" value="" />
					</td>
					<td align="left">
						<label style="text-align:left;" for="youtube_password"><?php echo 'Youtube  Password'; ?></label>
					</td>
				</tr>
				</table>		
			</td>
		</tr>
		<?php //default youtube user
			else:
				echo '<input type="hidden" id="youtube_key" name="youtube_key" value="'. $this->params->get('youtube_key') .'" />';
			endif;
		?>			
		<tr>
			<td>
				<input type="file" size="64"  class="inputbox" id="upyoutube" name="upyoutube" 
					title="Add any video file to youtube server"
					onchange="" accept="mov,flv,swf,txt"  />
			</td>
		</tr>
		</table>		
	</fieldset>
	
<!-- Buttons -->
	<div class="buttons">
		<button type="button" class="save"  onclick="submitbutton('save')">
			<?php echo JText::_('Save') ?>
		</button>
		<button type="button" class="cancel" onclick="submitbutton('cancel')">
			<?php echo JText::_('Cancel') ?>
		</button>
		
	<?php if($this->item->id) : ?>	
		<a id="preview" class="preview" href="<?php echo $this->links['media']; ?>">
			<?php echo JText::_('Preview') ?>
		</a>
	<?php endif; ?>
	</div>


	<div class="clr"></div>	
	<input type="hidden" name="added" value="<?php echo $this->item->added; ?>" />
	<input type="hidden" id="option" name="option" value="com_mediagalleries" />
	<input type="hidden" id="cid" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" id="id" name="id" value="<?php echo $this->item->id; ?>" />	
	<input type="hidden" id="userid" name="userid" value="<?php echo $this->item->userid; ?>" />
	<input type="hidden" id="task" name="task" value="save" />
	<input type="hidden" id="c" name="c" value="media" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>