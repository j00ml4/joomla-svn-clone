<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<script type="text/javascript">
<!--
	Window.onDomReady(function(){
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
	});

function validateForm( frm ) {
	var valid = document.formvalidator.isValid(frm);
	if (valid == false) {
		// do field validation
		if (frm.name.invalid) {
			alert( "<?php echo JText::_( 'Please enter your name.', true );?>" );
		} else if (frm.username.invalid) {
			alert( "<?php echo JText::_( 'Please enter a user name.', true );?>" );
		} else if (frm.email.invalid) {
			alert( "<?php echo JText::_( 'Please enter a valid e-mail address.', true );?>" );
		} else if (frm.password.invalid) {
			alert( "<?php echo JText::_( 'REGWARN_PASS', true );?>" );
		} else if (frm.password2.invalid) {
			alert( "<?php echo JText::_( 'Please verify the password.', true );?>" );
		}
		return false;
	} else {
		frm.submit();
	}
}
// -->
</script>

<?php
	if(isset($this->message)){
		$this->display('message');
	}
?>

<form action="<?php echo JRoute::_( 'index.php?option=com_user' ); ?>" method="post" id="josForm" name="josForm" class="form-validate">

<div class="componentheading">
	<?php echo JText::_( 'Registration' ); ?>
</div>

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
<tr>
	<td width="30%" height="40">
		<label id="namemsg" for="name">
			<?php echo JText::_( 'Name' ); ?>:
		</label>
	</td>
  	<td>
  		<input type="text" name="name" id="name" size="40" value="<?php echo $this->user->get( 'name' );?>" class="inputbox required" maxlength="50" /> *
  	</td>
</tr>
<tr>
	<td height="40">
		<label id="usernamemsg" for="username">
			<?php echo JText::_( 'Username' ); ?>:
		</label>
	</td>
	<td>
		<input type="text" id="username" name="username" size="40" value="<?php echo $this->user->get( 'username' );?>" class="inputbox required validate-username" maxlength="25" /> *
	</td>
<tr>
	<td height="40">
		<label id="emailmsg" for="email">
			<?php echo JText::_( 'Email' ); ?>:
		</label>
	</td>
	<td>
		<input type="text" id="email" name="email" size="40" value="<?php echo $this->user->get( 'email' );?>" class="inputbox required validate-email" maxlength="100" /> *
	</td>
</tr>
<tr>
	<td height="40">
		<label id="pwmsg" for="password">
			<?php echo JText::_( 'Password' ); ?>:
		</label>
	</td>
  	<td>
  		<input class="inputbox required validate-password" type="password" id="password" name="password" size="40" value="" /> *
  	</td>
</tr>
<tr>
	<td height="40">
		<label id="pw2msg" for="password2">
			<?php echo JText::_( 'Verify Password' ); ?>:
		</label>
	</td>
	<td>
		<input class="inputbox required validate-passverify" type="password" id="password2" name="password2" size="40" value="" /> *
	</td>
</tr>
<tr>
	<td colspan="2" height="40">
		<?php echo JText::_( 'REGISTER_REQUIRED' ); ?>
	</td>
</tr>
</table>
<button class="button validate" type="submit"><?php echo JText::_('Register'); ?></button>
<input type="hidden" name="task" value="register_save" />
<input type="hidden" name="id" value="0" />
<input type="hidden" name="gid" value="0" />
<input type="hidden" name="<?php echo JUtility::getToken(); ?>" value="1" />
</form>
