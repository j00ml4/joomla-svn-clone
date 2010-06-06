<?php 
/**
 * Display media form, follow weblinks model 
 */
defined('_JEXEC') or die('Restricted access'); 
?>


<?php 
	// ToolBar
	$text = ($this->item->id)? 
		JText::_( 'Edit' ):	
			JText::_( 'New' ); 		
	
	JToolBarHelper::title(  'Media: <small><small>['. $text.' ]</small></small>' );

	if($this->item->id){
		//JToolBarHelper::preview( 'index.php?option=com_jmultimedia&controller=media&tmpl=component&id='.$this->item->id, true );
	}
	
//	JToolBarHelper::apply();// TODO
	JToolBarHelper::save();
	JToolBarHelper::cancel();	
	JToolBarHelper::help( 'jmultimedia', true );
?>

<script language="javascript" type="text/javascript">
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
			(form.url.value == "") 
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
	function selectUpyoutube(){
		var form = document.adminForm;	
		form.uplocal.value = '';
		form.url.value = '';
	}
	function selectURL(){
		var form = document.adminForm;	
		form.uplocal.value = '';
		form.upyoutube.value = '';
	}
	function selectUplocal(){
		var form = document.adminForm;	
		form.upyoutube.value = '';
		form.url.value = '';
	}
	
	
</script>


<style type="text/css">
	table.paramlist td.paramlist_key {
		width: 92px;
		text-align: left;
		height: 30px;
	}
	
	#player {
		width: 400px;
		min-height: 150px;
		background:  #000000;
		border: inset 10px #000;
		color: red;
	}
	#player .denvideo{
		width: 400px;
		height: 300px;
	}
	#player img.denvideo{
		height:auto;
		min-height: 150px;
	}
</style>

<form enctype="multipart/form-data" action="<?php echo $this->action; ?>" method="post" name="adminForm" id="adminForm">
	
	<!-- Properties -->
	<div class="col width-50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Title' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo $this->item->title;?>" />
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'Published' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
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
		<tr>
			<td valign="top" align="right" class="key">
				<label for="ordering">
					<?php echo JText::_( 'Ordering' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['ordering']; ?>
			</td>
		</tr>
		</table>
		
		<table class="admintable">
		<tr>
			<td valign="center" align="right" class="key">
				<label for="description">
					<?php echo JText::_( 'Description' ); ?>: 
				</label>
			</td>
			<td>
				<textarea class="inputbox" cols="32" rows="5" name="description" id="description"><?php echo $this->item->description; ?></textarea>
			</td>
		</tr>
		</table>
		

		<?php  if($this->item->id) { ?>	
		<table style="border: 1px dashed silver; padding: 5px; margin-bottom: 10px;" width="100%">
			<tbody>
			<tr>
				<td>
					<strong>Article ID:</strong>
				</td>
				<td><?php echo $this->item->id; ?></td>
			</tr>
			<tr>
				<td><strong>Hits:</strong></td>
				<td><?php echo $this->item->hits; ?></td>
			</tr>
			<tr>
				<td><strong>Rating:</strong></td>
				<td><?php echo $this->item->rating; ?></td>
			</tr>			
			<tr>
				<td><strong>Added:</strong></td>
				<td><span class="createdate"><?php echo $this->item->added; ?></span></td>
			</tr>
			</tbody>
		</table>
		<?php } ?>
		
	</fieldset>
	</div>
	
		<!-- Get Media Item -->
	<div class="col width-50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Get Media Item' ); ?></legend>
		
		<!-- Install from URL -->
		<table class="adminform">
		<tr>
			<th colspan="2"><?php echo JText::_( 'Load Media from URL' ); ?></th>
		</tr>
		<tr>
			<td>
				<input type="text" id="url" name="url" class="inputbox" 
					title="youtube, video.yahoo, video.google, brigthcove "
					onchange="selectURL()" size="64" value="<?php echo $this->item->url; ?>" />
			</td>
		</tr>
		</table>	
		<b><?php echo JText::_( 'or' ); ?></b>
		
		<!-- Upload Media File -->
		<?php /*if ($this->ftp) : ?>
			<?php echo $this->loadTemplate('ftp'); ?>
		<?php endif;*/ ?>
		<table class="adminform">
		<tr>
			<th colspan="2"><?php echo JText::_( 'Upload Media from File' ); ?>:</th>
		</tr>
		<tr>
			<td colspan="2">
				<input type="file" class="inputbox" id="uplocal" name="uplocal" 
					title=".FLV, .MOV, .SWF, .CLASS (java Applet)"
					onchange="selectUplocal()" accept="mov,flv,swf,txt"  size="64" />	
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="toFolder"><?php echo JText::_('To Folder'); ?>:</label>
				<input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="0" />
			</td>
			<td>	
				<input type="text" id="toFolder" name="toFolder" class="inputbox" 
					title="Destination directory"
					onchange="selectUpyoutube()" size="64" value="<?php echo $this->folder; ?>" />
			</td>
		</tr>
		</table>
		
		<!--TODO Add by youtube upload -->
		<!--b><?php echo JText::_( 'or' ); ?></b>		
		<table class="adminform" style="">
		<tr>
			<th><?php echo JText::_( 'Upload to youtube server' ); ?>:</th>
			</tr>
		<tr>
			<td>
				<input size="64" type="file" class="inputbox" id="upyoutube" name="upyoutube" 
					title="Add any video file to youtube server"
					onchange=" " accept="mov,flv,swf,txt"  />
			</td>
		</tr>
		</table-->
		
		<!-- Preview Video -->	
		<?php if($this->item->id): ?>
		<h3>Preview</h3>
		<div id="player">
			<?php echo $this->video; ?>
		</div>
		<?php endif; ?>
	</fieldset>
	</div>	
	
	<!-- TODO set Date -->
	<input type="hidden" id="added" name="added" value="<?php echo $this->item->added; ?>" />
<div class="clr"></div>	

	<input type="hidden" id="option" name="option" value="com_jmultimedia" />
	<input type="hidden" id="cid" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" id="id" name="id" value="<?php echo $this->item->id; ?>" />	
	<input type="hidden" id="userid" name="userid" value="<?php echo $this->item->userid; ?>" />
	<input type="hidden" id="task" name="task" value="save" />
	<input type="hidden" id="c" name="c" value="media" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
			
