<?php
/**
* @version $Id: login.php 3 2005-09-06 15:11:03Z akede $
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Joomla! is Free Software and contains or is derived from copyrighted works
* licensed under the GNU General Public License.
* See docs/COPYRIGHT.php for copyright notices and details.
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Restricted access' );

$tstart = mosProfiler::getmicrotime();
?>
<?php echo "<?xml version=\"1.0\"?>\r\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $mosConfig_sitename; ?> - Administration [Joomla]</title>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<style type="text/css">
@import url(templates/joomla_admin/css/admin_login.css);
</style>
<script language="javascript" type="text/javascript">
	function setFocus() {
		document.loginForm.usrname.select();
		document.loginForm.usrname.focus();
	}
</script>
</head>
<body onload="setFocus();">
<div id="wrapper">
	<div id="header">
			<div id="joomla"><img src="templates/joomla_admin/images/header_text.png" alt="Joomla! Logo" /></div>
	</div>
</div>
<div id="ctr" align="center">
	<div class="login">
		<div class="login-form">
			<img src="templates/joomla_admin/images/login.gif" alt="Login" />
			<form action="index.php" method="post" name="loginForm" id="loginForm">
			<div class="form-block">
				<div class="inputlabel">Username</div>
				<div><input name="usrname" type="text" class="inputbox" size="15" /></div>
				<div class="inputlabel">Password</div>
				<div><input name="pass" type="password" class="inputbox" size="15" /></div>
				<div align="left"><input type="submit" name="submit" class="button" value="Login" /></div>
			</div>
			</form>
		</div>
		<div class="login-text">
			<div class="ctr"><img src="templates/joomla_admin/images/security.png" width="64" height="64" alt="security" /></div>
			<p>Welcome to Joomla!</p>
			<p>Use a valid username and password to gain access to the administration console.</p>
		</div>
		<div class="clr"></div>
	</div>
</div>
<div id="break"></div>
<noscript>
!Warning! Javascript must be enabled for proper operation of the Administrator
</noscript>
<div class="footer" align="center">
<?php
	include ($mosConfig_absolute_path . "/includes/footer.php");
?>
</div>
</body>
</html>
