<?php /* $Id$ */ defined('_JEXEC') or die('Restricted access'); ?>

<?php
JHTML::_('behavior.formvalidation');
if ($this->params->get('validate_email_form')=='1') {
	// validate all fields on submit
	$formValidationType='';
	//$formValidationType=' onsubmit="return validateForm(this);" ';
} else {
	// immediate validation on every field
	$formValidationType=' class="form-validate" ';
}
?>

<script type="text/javascript">
<!--
	function validateForm( frm ) {
		var valid = document.formvalidator.isValid(frm);
		if (valid == false) {
			// do field validation
			if (frm.email.hasClass('invalid')) {
				alert( "<?php echo JText::_( 'Please enter a valid e-mail address.', true );?>" );
			} else if (frm.text.hasClass('invalid')) {
				alert( "<?php echo JText::_( 'Please make sure the form is complete and valid.', true ); ?>" );
			}
		} else {
			frm.submit();
		}
	}
// -->
</script>

<?php if(isset($this->error) && $this->error != null) : ?>
	<div class="error"><?php echo $this->error; ?></div>
<?php endif; ?>

<div>
	<form action="<?php echo JRoute::_( 'index.php' );?>" method="post" name="emailForm" id="emailForm" <?php echo $formValidationType; ?> >
		<div class="contact_email<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
			<label for="contact_name">
				&nbsp;<?php echo JText::_( 'Enter your name' );?>:
			</label>
			<br />
			<input type="text" name="name" id="contact_name" size="30" class="inputbox" value="<?php echo $this->data->name; ?>" />
			<br />
			<label id="contact_emailmsg" for="contact_email">
				&nbsp;<?php echo JText::_( 'Email address' );?>:
			</label>
			<br />
			<input type="text" id="contact_email" name="email" size="30" value="<?php echo $this->data->email; ?>" class="inputbox required validate-email" maxlength="100" />
			<br />
			<label for="contact_subject">
				&nbsp;<?php echo JText::_( 'Message subject' );?>:
			</label>
			<br />
			<input type="text" name="subject" id="contact_subject" size="30" class="inputbox" value="<?php echo $this->data->subject; ?>" />
			<br /><br />
			<label id="contact_textmsg" for="contact_text">
				&nbsp;<?php echo JText::_( 'Enter your message' );?>:
			</label>
			<br />
			<textarea cols="50" rows="10" name="body" id="contact_text" class="inputbox required"><?php echo $this->data->body; ?></textarea>
			<?php if ($this->contact->params->get( 'show_email_copy' )) : ?>
			<br />
				<input type="checkbox" name="email_copy" id="contact_email_copy" value="1"  />
				<label for="contact_email_copy">
					<?php echo JText::_( 'E-mail a copy of this message to your own address' ); ?>
				</label>
			<?php endif; ?>
			<br />
			<br />
			<button class="button validate" type="submit"><?php echo JText::_('Send'); ?></button>
		</div>

	<input type="hidden" name="option" value="com_contacts" />
	<input type="hidden" name="view" value="contact" />
	<input type="hidden" name="id" value="<?php echo $this->contact->id; ?>" />
	<input type="hidden" name="task" value="submit" />
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<br />
</div>