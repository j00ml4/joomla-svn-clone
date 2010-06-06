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
		if ( (form.url.value == "") && (form.upmedia.value == "") ){
			alert( "<?php echo JText::_('Please provide a valid URL'); ?>" );
			return;
		}
		
		submitform( pressbutton );
	}

	
	/**
	 * Set URL when change upload file
	 */
	function autoChangeUrl(){
		var form = document.adminForm;	
		var fname = form.upmedia.value;
		
		form.url.value = '';		
	}
	
	/**
	 * Clear upload field when URL is change
	 */
	function autoClearUpload(){
		var form = document.adminForm;	
		form.upmedia.value = '';
	}
	
</script>


<style type="text/css">
	table.paramlist td.paramlist_key {
		width: 92px;
		text-align: left;
		height: 30px;
	}
	
	#videoPreview {
		background: #000000;
		border: inset 10px #000;
		color: red;
	}
	.denvideo{
		width: 100%;
		min-height:330px;
		display:block;
		text-align: center
	}
</style>

<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm" id="adminForm">
	
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
			<td width="100" align="right" class="key">
				<label for="alias">
					<?php echo JText::_( 'Alias' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="alias" id="alias" size="32" maxlength="250" value="<?php echo $this->item->alias;?>" />
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
				<td><strong>Created:</strong></td>
				<td><?php echo $this->item->date; ?></td>
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
		
		<?php /*if ($this->ftp) : ?>
			<?php echo $this->loadTemplate('ftp'); ?>
		<?php endif;*/ ?>
	
		<!-- Install from URL -->
		<table class="adminform">
		<tr>
			<th colspan="2"><?php echo JText::_( 'Load Media from URL' ); ?></th>
		</tr>
		<tr>
			<td>
				<input type="text" id="url" name="url" class="inputbox" 
					title="youtube, video.yahoo, video.google, brigthcove "
					onchange="autoClearUpload()" size="64" value="<?php echo $this->item->url; ?>" />
			</td>
		</tr>
		</table>	
		<b><?php echo JText::_( 'or' ); ?></b>
		<!-- Upload Media File -->
		<table class="adminform">
		<tr>
			<th colspan="2"><?php echo JText::_( 'Upload Media from File' ); ?>:</th>
		</tr>
		<tr>
			<td>
				<input type="file" class="inputbox" id="upmedia" name="upmedia" 
					title=".FLV, .MOV, .SWF, .CLASS (java Applet)"
					onchange="autoChangeUrl()" accept="mov,flv,swf,txt"  size="64" />
			</td>
		</tr>
		</table>

		
		<!-- Preview Video -->	
		<h3>Preview</h3>
		<div id="videoPreview">
			<?php 
				// Display video preview
				echo $this->video;	
			?>
		</div>
	</fieldset>
	</div>	
	
	<!-- TODO set Date -->
	<input type="hidden" name="date" value="<?php echo $this->item->date; ?>" />
<div class="clr"></div>	

	<input type="hidden" name="option" value="com_jmultimedia" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="userid" value="<?php echo $this->item->userid; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="media" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
